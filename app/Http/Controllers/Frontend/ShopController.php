<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('book_type')) {
            $query->where('book_type', $request->book_type);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        // Get distinct levels for the filter dropdown
        $levels = Product::whereNotNull('level')->where('level', '!=', '')->distinct()->pluck('level');

        return view('shop.index', compact('products', 'categories', 'levels'));
    }

    public function show(Product $product)
    {
        $product->load(['reviews.user']);

        // Fetch related accessories for the same level and category, excluding this exact product
        $relatedAccessories = collect();
        if ($product->level && $product->category_id) {
            $relatedAccessories = Product::where('category_id', $product->category_id)
                ->where('level', $product->level)
                ->where('id', '!=', $product->id)
                ->get();
        }

        return view('shop.show', compact('product', 'relatedAccessories'));
    }
}
