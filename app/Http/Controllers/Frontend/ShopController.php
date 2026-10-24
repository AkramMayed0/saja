<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $priceColumn = $currencyService->isYemen() ? 'price_sar' : 'price_usd';
        $categorySlug = $request->category;
        $selectedCategory = $categorySlug ? Category::where('slug', $categorySlug)->with('parent', 'children')->first() : null;

        // 1. Build Bundles query
        $bundlesQuery = Bundle::active()->with(['category.parent', 'products']);

        // 2. Build Products (Books) query
        $productsQuery = Product::with(['category.parent', 'bundle']);

        // Common search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $bundlesQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'))
                  ->orWhereHas('products', fn($pq) => $pq->where('title', 'like', '%' . $searchTerm . '%'));
            });

            $productsQuery->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'));
            });
        }

        // Common category filter
        if ($categorySlug) {
            $bundlesQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhereHas('parent', fn($pq) => $pq->where('slug', $categorySlug));
            });

            $productsQuery->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhereHas('parent', fn($pq) => $pq->where('slug', $categorySlug));
            });
        }

        // Common level filter
        if ($request->filled('level')) {
            $bundlesQuery->where('level', $request->level);
            $productsQuery->where('level', $request->level);
        }

        // Common price filter
        if ($request->filled('min_price')) {
            $minPrice = (float) $request->min_price;
            $bundlesQuery->where(function ($q) use ($priceColumn, $minPrice) {
                $q->where($priceColumn, '>=', $minPrice)->orWhereNull($priceColumn);
            });
            $productsQuery->where(function ($q) use ($priceColumn, $minPrice) {
                $q->where($priceColumn, '>=', $minPrice)->orWhere('price', '>=', $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $bundlesQuery->where(function ($q) use ($priceColumn, $maxPrice) {
                $q->where($priceColumn, '<=', $maxPrice)->orWhereNull($priceColumn);
            });
            $productsQuery->where(function ($q) use ($priceColumn, $maxPrice) {
                $q->where($priceColumn, '<=', $maxPrice)->orWhere('price', '<=', $maxPrice);
            });
        }

        // 1. Fetch matching bundles
        $bundlesCollection = $bundlesQuery->get();

        // 2. Fetch matching standalone products (e.g. Computer Science books, Ashbal KG, etc.)
        if ($selectedCategory && $bundlesCollection->isEmpty()) {
            $productsCollection = $productsQuery->get();
        } elseif ($request->filled('search')) {
            $productsCollection = $productsQuery->get();
        } else {
            // General catalog: include standalone textbooks not duplicated inside bundles
            $productsCollection = $productsQuery->whereNull('bundle_id')->get();
        }

        // 3. Merge into unified catalog collection
        $mergedItems = $bundlesCollection->concat($productsCollection);

        // 4. Sort collection
        $sortOption = $request->get('sort');
        $mergedItems = $mergedItems->sort(function ($a, $b) use ($sortOption) {
            if ($sortOption === 'newest') {
                return $b->created_at <=> $a->created_at;
            }
            if ($sortOption === 'price_asc') {
                return ($a->current_price ?? 0) <=> ($b->current_price ?? 0);
            }
            if ($sortOption === 'price_desc') {
                return ($b->current_price ?? 0) <=> ($a->current_price ?? 0);
            }

            // Default hierarchy:
            // 1. Category ID
            $catA = $a->category_id ?? 999;
            $catB = $b->category_id ?? 999;
            if ($catA !== $catB) {
                return $catA <=> $catB;
            }

            // 2. Level
            $levelA = (int)($a->level ?? 0);
            $levelB = (int)($b->level ?? 0);
            if ($levelA !== $levelB) {
                return $levelA <=> $levelB;
            }

            // 3. Student bundle before teacher bundle
            if ($a instanceof Bundle && $b instanceof Bundle) {
                return ($a->isStudentBundle() ? 0 : 1) <=> ($b->isStudentBundle() ? 0 : 1);
            }

            return 0;
        })->values();

        // 5. Paginate unified collection
        $perPage = 12;
        $currentPage = max(1, (int) $request->input('page', 1));
        $currentSlice = $mergedItems->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $items = new LengthAwarePaginator(
            $currentSlice,
            $mergedItems->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        // For full compatibility with views/tests
        $bundles = $items;
        $products = $items;
        $viewMode = 'unified';
        $totalBundlesMatching = $bundlesCollection->count();
        $totalProductsMatching = $productsCollection->count();

        // Categories for filter dropdown
        $categories = Category::with(['children' => fn($q) => $q->withCount(['bundles' => fn($bq) => $bq->active(), 'products'])])
            ->withCount(['bundles' => fn($q) => $q->active(), 'products'])
            ->get();

        // Distinct levels across both bundles and products
        $bundleLevels = Bundle::active()->whereNotNull('level')->where('level', '!=', '')->pluck('level');
        $productLevels = Product::whereNotNull('level')->where('level', '!=', '')->pluck('level');
        $levels = $bundleLevels->merge($productLevels)->unique()->sort()->values();

        return view('shop.index', compact(
            'items',
            'bundles',
            'products',
            'viewMode',
            'totalBundlesMatching',
            'totalProductsMatching',
            'selectedCategory',
            'categories',
            'levels'
        ));
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
