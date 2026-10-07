<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        
        // جلب أحدث 4 منتجات
        $newProducts = \App\Models\Product::with('category')->latest()->take(4)->get();
        
        // جلب 4 منتجات عشوائية أو مميزة لتكون "رائجة"
        $trendingProducts = \App\Models\Product::with('category')->inRandomOrder()->take(4)->get();
        
        return view('home', compact('categories', 'newProducts', 'trendingProducts'));
    }
}
