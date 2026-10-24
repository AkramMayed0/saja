<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageOptimizationService;

class CategoryController extends Controller
{
    protected ImageOptimizationService $imageOptimizationService;

    public function __construct(ImageOptimizationService $imageOptimizationService)
    {
        $this->imageOptimizationService = $imageOptimizationService;
    }

    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount(['products', 'bundles'])
            ->orderByRaw('COALESCE(parent_id, id) ASC')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
        ]);

        $slug = Str::slug($request->name);
        if (empty($slug)) {
            $slug = 'category-' . uniqid();
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->filled('parent_id') ? $request->parent_id : null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageOptimizationService->storeAsWebp(
                $request->file('image'),
                'categories',
                'cat_'
            );
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', __('تم إضافة القسم بنجاح.'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'parent_id' => 'nullable|exists:categories,id|not_in:'.$category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
        ]);

        $slug = Str::slug($request->name);
        if (empty($slug)) {
            $slug = $category->slug ?: 'category-' . $category->id;
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->filled('parent_id') ? $request->parent_id : null,
        ];

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $this->imageOptimizationService->storeAsWebp(
                $request->file('image'),
                'categories',
                'cat_'
            );
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', __('تم تحديث القسم بنجاح.'));
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->with('error', __('لا يمكن حذف هذا القسم لأنه يحتوي على أقسام فرعية مرتبطة به.'));
        }

        if ($category->products()->count() > 0 || $category->bundles()->count() > 0) {
            return back()->with('error', __('لا يمكن حذف قسم يحتوي على منتجات أو باقات.'));
        }
        
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __('تم حذف القسم بنجاح.'));
    }
}
