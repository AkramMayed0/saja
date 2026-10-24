<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => fn($q) => $q->withCount(['products', 'bundles'])])
            ->withCount(['products', 'bundles'])
            ->get();

        if ($categories->isEmpty()) {
            $categories = Category::withCount(['products', 'bundles'])->get();
        }
        
        // جلب أحدث 4 منتجات
        $newProducts = \App\Models\Product::with('category')->latest()->take(4)->get();
        
        // جلب 4 منتجات عشوائية أو مميزة لتكون "رائجة"
        $trendingProducts = \App\Models\Product::with('category')->inRandomOrder()->take(4)->get();

        // إحصائيات حقيقية لعرضها في الواجهة
        $productsCount = \App\Models\Product::count();

        // شريط الكتب المميزة: مرتبة حسب السلسلة والنوع وسنة/مستوى الكتاب
        $sliderProducts = \App\Models\Product::where('title', 'not like', '%Cards%')
            ->whereNotNull('image')
            ->orderBy('category_id', 'asc')
            ->orderByRaw("CASE book_type 
                WHEN 'student' THEN 1 
                WHEN 'combined' THEN 2 
                WHEN 'workbook' THEN 3 
                WHEN 'teacher' THEN 4 
                ELSE 5 END ASC")
            ->orderBy('level', 'asc')
            ->get();
        
        return view('home', compact('categories', 'newProducts', 'trendingProducts', 'productsCount', 'sliderProducts'));
    }
}
