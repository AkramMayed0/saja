<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        $query = Bundle::with('category')->withCount('products')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        $bundles = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.bundles.index', compact('bundles', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->orderBy('category_id')->orderBy('level')->get();

        return view('admin.bundles.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'level' => 'nullable|string|max:50',
            'price_sar' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $data = $request->except(['image', 'product_ids']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        // Generate unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug ?: 'bundle-' . uniqid();
        $count = Bundle::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . uniqid();
        }
        $data['slug'] = $slug;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('bundle_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bundles', $filename, 'public');
            $data['image'] = $path;
        }

        $bundle = Bundle::create($data);

        // Sync products
        if ($request->filled('product_ids')) {
            Product::whereIn('id', $request->product_ids)->update(['bundle_id' => $bundle->id]);
        }

        return redirect()->route('admin.bundles.index')->with('success', __('تم إنشاء الباقة التعليمية بنجاح.'));
    }

    public function edit(Bundle $bundle)
    {
        $bundle->load('products');
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->orderBy('category_id')->orderBy('level')->get();
        $selectedProductIds = $bundle->products->pluck('id')->toArray();

        return view('admin.bundles.edit', compact('bundle', 'categories', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'level' => 'nullable|string|max:50',
            'price_sar' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $data = $request->except(['image', 'product_ids']);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        // Update slug only if name changed
        if ($bundle->name !== $request->name) {
            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug ?: 'bundle-' . uniqid();
            $count = Bundle::where('slug', 'like', "{$slug}%")->where('id', '!=', $bundle->id)->count();
            if ($count > 0) {
                $slug .= '-' . uniqid();
            }
            $data['slug'] = $slug;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($bundle->image && Storage::disk('public')->exists($bundle->image)) {
                Storage::disk('public')->delete($bundle->image);
            }
            $file = $request->file('image');
            $filename = uniqid('bundle_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bundles', $filename, 'public');
            $data['image'] = $path;
        }

        $bundle->update($data);

        // Sync products
        if ($request->has('product_ids') && is_array($request->product_ids)) {
            // Unlink removed products
            Product::where('bundle_id', $bundle->id)
                ->whereNotIn('id', $request->product_ids)
                ->update(['bundle_id' => null]);

            // Link selected products
            Product::whereIn('id', $request->product_ids)
                ->update(['bundle_id' => $bundle->id]);
        } else {
            // Unlink all
            Product::where('bundle_id', $bundle->id)->update(['bundle_id' => null]);
        }

        return redirect()->route('admin.bundles.index')->with('success', __('تم تحديث الباقة التعليمية بنجاح.'));
    }

    public function destroy(Bundle $bundle)
    {
        // Unlink associated products
        Product::where('bundle_id', $bundle->id)->update(['bundle_id' => null]);

        // Delete image
        if ($bundle->image && Storage::disk('public')->exists($bundle->image)) {
            Storage::disk('public')->delete($bundle->image);
        }

        $bundle->delete();

        return redirect()->route('admin.bundles.index')->with('success', __('تم حذف الباقة بنجاح وإلغاء ارتباط كتبها.'));
    }
}
