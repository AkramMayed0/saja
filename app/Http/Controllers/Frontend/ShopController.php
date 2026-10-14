<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Bundle::active()->with(['category', 'products']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function ($catQ) use ($searchTerm) {
                      $catQ->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('products', function ($prodQ) use ($searchTerm) {
                      $prodQ->where('title', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $priceColumn = app(\App\Services\CurrencyService::class)->isYemen() ? 'price_sar' : 'price_usd';

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($priceColumn, $request) {
                $q->where($priceColumn, '>=', $request->min_price)
                  ->orWhereNull($priceColumn);
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($priceColumn, $request) {
                $q->where($priceColumn, '<=', $request->max_price)
                  ->orWhereNull($priceColumn);
            });
        }

        if ($request->get('sort') === 'newest') {
            $query->latest();
        } else {
            $query->orderBy('category_id', 'asc')
                  ->orderBy('level', 'asc');
        }

        $bundles = $query->paginate(12)->withQueryString();
        $categories = Category::withCount(['bundles' => fn($q) => $q->active()])->get();

        // Get distinct levels for the filter dropdown
        $levels = Bundle::active()->whereNotNull('level')
            ->where('level', '!=', '')
            ->distinct()
            ->orderBy('level', 'asc')
            ->pluck('level');

        return view('shop.index', compact('bundles', 'categories', 'levels'));
    }

    public function showBundle(Bundle $bundle)
    {
        abort_unless($bundle->is_active, 404);

        $bundle->load([
            'category',
            'products' => function ($q) {
                $q->orderByRaw("CASE book_type 
                    WHEN 'student' THEN 1 
                    WHEN 'workbook' THEN 2 
                    WHEN 'combined' THEN 3 
                    WHEN 'teacher' THEN 4 
                    WHEN 'flashcard' THEN 5 
                    WHEN 'audio' THEN 6 
                    ELSE 7 END ASC");
            },
            'products.reviews.user'
        ]);

        // Fetch sibling bundles in the same curriculum category
        $otherBundles = Bundle::active()
            ->where('category_id', $bundle->category_id)
            ->where('id', '!=', $bundle->id)
            ->orderBy('level', 'asc')
            ->get();

        return view('shop.bundle', compact('bundle', 'otherBundles'));
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'bundle.products',
            'reviews' => function ($q) {
                $q->where('is_approved', true)->with('user')->latest();
            }
        ]);

        // Fetch related accessories for the same level and category, excluding this exact product
        $relatedAccessories = collect();
        if ($product->bundle_id) {
            $relatedAccessories = Product::where('bundle_id', $product->bundle_id)
                ->where('id', '!=', $product->id)
                ->get();
        } elseif ($product->level && $product->category_id) {
            $relatedAccessories = Product::where('category_id', $product->category_id)
                ->where('level', $product->level)
                ->where('id', '!=', $product->id)
                ->get();
        }

        return view('shop.show', compact('product', 'relatedAccessories'));
    }

    public function downloadSample(Product $product)
    {
        if (!$product->sample_pdf || !\Illuminate\Support\Facades\Storage::disk('public')->exists($product->sample_pdf)) {
            return back()->with('error', __('عينة الكتاب غير متوفرة حالياً'));
        }

        $filename = 'عينة_' . \Illuminate\Support\Str::slug($product->title) . '.pdf';
        return \Illuminate\Support\Facades\Storage::disk('public')->download($product->sample_pdf, $filename);
    }
}
