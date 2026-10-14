<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price_sar' => 'float',
        'price_usd' => 'float',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the sum of individual products' current prices.
     */
    public function getCalculatedPriceAttribute(): float
    {
        return (float) $this->products->sum(function ($product) {
            return $product->current_price;
        });
    }

    /**
     * Sum of products' SAR prices.
     */
    public function getCalculatedSarAttribute(): float
    {
        return (float) $this->products->sum(function ($product) {
            return (float) ($product->price_sar ?: $product->price ?: 0);
        });
    }

    /**
     * Sum of products' USD prices.
     */
    public function getCalculatedUsdAttribute(): float
    {
        return (float) $this->products->sum(function ($product) {
            return (float) ($product->price_usd ?: round(($product->price_sar ?: $product->price ?: 0) / 3.75, 2));
        });
    }

    /**
     * Effective SAR price (explicit discounted price or sum of components).
     */
    public function getEffectivePriceSarAttribute(): float
    {
        if ($this->price_sar !== null && (float)$this->price_sar > 0) {
            return (float) $this->price_sar;
        }
        return $this->calculated_sar;
    }

    /**
     * Effective USD price (explicit discounted price or sum of components).
     */
    public function getEffectivePriceUsdAttribute(): float
    {
        if ($this->price_usd !== null && (float)$this->price_usd > 0) {
            return (float) $this->price_usd;
        }
        return $this->calculated_usd;
    }

    /**
     * Calculate effective price for current region (discounted bundle price or sum of products).
     */
    public function getCurrentPriceAttribute(): float
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $region = $currencyService->getUserRegion();

        if ($region === \App\Services\CurrencyService::REGION_YE && $this->price_sar !== null && $this->price_sar > 0) {
            return (float) $this->price_sar;
        }

        if ($region !== \App\Services\CurrencyService::REGION_YE && $this->price_usd !== null && $this->price_usd > 0) {
            return (float) $this->price_usd;
        }

        return $this->calculated_price;
    }

    /**
     * Original price without bundle discount.
     */
    public function getOriginalPriceAttribute(): float
    {
        return $this->calculated_price;
    }

    /**
     * Whether this bundle offers a discount over buying parts separately.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->current_price < $this->original_price && $this->original_price > 0;
    }

    /**
     * Savings percentage rounded to integer.
     */
    public function getSavingsPercentageAttribute(): int
    {
        if (!$this->has_discount || $this->original_price <= 0) {
            return 0;
        }

        return (int) round((($this->original_price - $this->current_price) / $this->original_price) * 100);
    }

    /**
     * Formatted string with currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->formatPrice($this->current_price);
    }

    /**
     * Formatted original price string.
     */
    public function getFormattedOriginalPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->formatPrice($this->original_price);
    }

    /**
     * Get representative cover image.
     */
    public function getCoverImageAttribute(): ?string
    {
        if ($this->image) {
            return $this->image;
        }

        // Fallback to student book, combined, or first product with an image
        $coverProduct = $this->products->firstWhere('book_type', 'student')
            ?? $this->products->firstWhere('book_type', 'combined')
            ?? $this->products->first(fn($p) => !empty($p->image));

        return $coverProduct?->image;
    }

    /**
     * Helper to get student book.
     */
    public function getStudentBookAttribute(): ?Product
    {
        return $this->products->firstWhere('book_type', 'student');
    }

    /**
     * Helper to get workbook / activity book.
     */
    public function getActivityBookAttribute(): ?Product
    {
        return $this->products->firstWhere('book_type', 'workbook');
    }

    /**
     * Helper to get teacher's guide.
     */
    public function getTeacherBookAttribute(): ?Product
    {
        return $this->products->firstWhere('book_type', 'teacher');
    }

    /**
     * Helper to get flashcards.
     */
    public function getFlashcardsAttribute(): ?Product
    {
        return $this->products->firstWhere('book_type', 'flashcard');
    }

    /**
     * Helper to get audio tracks.
     */
    public function getAudioProductAttribute(): ?Product
    {
        return $this->products->firstWhere('book_type', 'audio');
    }

    /**
     * Total number of reviews across all items in this bundle.
     */
    public function getReviewsCountAttribute(): int
    {
        return (int) $this->products->sum('reviews_count');
    }

    /**
     * Weighted average rating across all items in this bundle.
     */
    public function getRatingAttribute(): float
    {
        $ratedProducts = $this->products->filter(fn($p) => ($p->reviews_count ?? 0) > 0);
        if ($ratedProducts->isEmpty()) {
            return 0.0;
        }

        $totalScore = $ratedProducts->sum(fn($p) => $p->rating * $p->reviews_count);
        $totalReviews = $ratedProducts->sum('reviews_count');

        return $totalReviews > 0 ? round($totalScore / $totalReviews, 1) : 0.0;
    }

    /**
     * Get route key name for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Retrieve the model for a bound value supporting both slug and numeric id.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->orWhere($field ?? 'slug', $value)->firstOrFail();
        }

        return $this->where($field ?? 'slug', $value)->firstOrFail();
    }
}

