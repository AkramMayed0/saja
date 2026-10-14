<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    /**
     * Display a listing of product reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        // Filter by rating
        if ($request->filled('rating') && in_array((int)$request->rating, [1, 2, 3, 4, 5])) {
            $query->where('rating', (int)$request->rating);
        }

        // Search by review comment, product title, or customer name/email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Sort: unapproved first, then latest created
        $reviews = $query->orderBy('is_approved', 'asc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        $pendingCount = Review::where('is_approved', false)->count();
        $approvedCount = Review::where('is_approved', true)->count();
        $totalCount = Review::count();

        return view('admin.reviews.index', compact('reviews', 'pendingCount', 'approvedCount', 'totalCount'));
    }

    /**
     * Toggle the review's approval status.
     */
    public function toggleApproval(Review $review)
    {
        $review->is_approved = !$review->is_approved;
        $review->save();

        if ($review->product) {
            $this->recalculateProductRating($review->product);
        }

        $message = $review->is_approved
            ? __('تم اعتماد التقييم بنجاح وظهوره في صفحة المنتج.')
            : __('تم إخفاء التقييم وتعطيل ظهوره للعملاء.');

        return back()->with('success', $message);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $product = $review->product;
        $review->delete();

        if ($product) {
            $this->recalculateProductRating($product);
        }

        return back()->with('success', __('تم حذف التقييم بنجاح.'));
    }

    /**
     * Recalculate average rating and review count for a product using approved reviews.
     */
    protected function recalculateProductRating(Product $product): void
    {
        $approvedReviews = $product->reviews()->where('is_approved', true);
        $count = $approvedReviews->count();
        $avg = $count > 0 ? round($approvedReviews->avg('rating'), 1) : 0.0;

        $product->update([
            'rating' => $avg,
            'reviews_count' => $count,
        ]);
    }
}
