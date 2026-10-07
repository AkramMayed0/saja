<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['product.category'])->latest()->get();
        
        $groupedCourses = $courses->groupBy(function($course) {
            if ($course->product && $course->product->category) {
                return $course->product->category->name;
            }
            return __('دورات عامة');
        });

        return view('courses.index', compact('groupedCourses'));
    }

    public function show(Course $course)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $course->youtube_url, $match);
        $youtube_id = $match[1] ?? null;
        
        return view('courses.show', compact('course', 'youtube_id'));
    }
}
