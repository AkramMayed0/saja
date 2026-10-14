<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false,
            ]
        );

        // Update product's rating and reviews_count using approved reviews only
        $approvedReviews = $product->reviews()->where('is_approved', true);
        $avgRating = $approvedReviews->avg('rating') ?? 0;
        $reviewsCount = $approvedReviews->count();

        $product->update([
            'rating' => round($avgRating, 1),
            'reviews_count' => $reviewsCount,
        ]);

        return back()->with('success', __('شكراً لك! تم إرسال تقييمك بنجاح وسيظهر بعد مراجعته واعتماده.'));
    }
}
