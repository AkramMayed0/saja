<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Product;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('product')->latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.courses.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'product_id' => 'nullable|exists:products,id'
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', __('تم إضافة الدورة بنجاح.'));
    }

    public function edit(Course $course)
    {
        $products = Product::all();
        return view('admin.courses.edit', compact('course', 'products'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')->with('success', __('تم تعديل الدورة بنجاح.'));
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', __('تم حذف الدورة بنجاح.'));
    }
}
