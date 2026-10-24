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

    public function isStudentBundle(): bool
    {
        return ($this->bundle_type ?? 'student') === 'student';
    }

    public function isTeacherBundle(): bool
    {
        return ($this->bundle_type ?? '') === 'teacher';
    }

    public function getBundleTypeLabelAttribute(): string
    {
        return $this->isTeacherBundle() ? __('باقة المعلم والوسائط') : __('باقة الطالب والتمارين');
    }

    /**
     * Get companion bundle for the same curriculum series and level (e.g. Teacher pack for Student pack).
     */
    public function getCompanionBundleAttribute(): ?self
    {
        return static::active()
            ->where('category_id', $this->category_id)
            ->where('level', $this->level)
            ->where('id', '!=', $this->id)
            ->first();
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
     * Effective SAR price (explicit discounted price or 10% bundle discount off sum of components).
     */
    public function getEffectivePriceSarAttribute(): float
    {
        if ($this->price_sar !== null && (float)$this->price_sar > 0) {
            return (float) $this->price_sar;
        }
        return round($this->calculated_sar * 0.90, 2);
    }

    /**
     * Effective USD price (explicit discounted price or 10% bundle discount off sum of components).
     */
    public function getEffectivePriceUsdAttribute(): float
    {
        if ($this->price_usd !== null && (float)$this->price_usd > 0) {
            return (float) $this->price_usd;
        }
        return round($this->calculated_usd * 0.90, 2);
    }

    /**
     * Calculate effective price for current region (discounted bundle price or sum of products).
     */
    public function getCurrentPriceAttribute(): float
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $region = $currencyService->getUserRegion();

        if ($region === \App\Services\CurrencyService::REGION_YE) {
            return $this->effective_price_sar;
        }

        return $this->effective_price_usd;
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

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $trans = __($value);
            if ($trans !== $value) {
                return $trans;
            }
            $name = str_replace('باقة الطالب والتمارين - ', 'Student & Workbook Bundle - ', $value);
            $name = str_replace('باقة الطالب - ', 'Student Bundle - ', $name);
            $name = str_replace('باقة المعلم والوسائط - ', 'Teacher & Media Bundle - ', $name);
            return $name;
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $trans = __($value);
            if ($trans !== $value) {
                return $trans;
            }
            $t = $value;
            $t = str_replace('باقة الطالب المتكاملة لمنهج', 'Comprehensive Student Bundle for', $t);
            $t = str_replace('باقة الطالب والتمارين لمنهج', 'Student & Workbook Bundle for', $t);
            $t = str_replace('حقيبة المعلم والوسائط التعليمية لمنهج', 'Teacher & Media Resource Pack for', $t);
            $t = str_replace('باقة المعلم والوسائط لمنهج', 'Teacher & Media Bundle for', $t);
            $t = str_replace('المستوى', 'Level', $t);
            $t = str_replace('تشمل كتاب الحصة (الطالب) وكتاب التمارين والواجبات المنزلية بخصم خاص.', 'includes Student Book and Activity/Workbook with special bundle savings.', $t);
            $t = str_replace('تشمل كتاب الحصة (الطالب) وكتاب التمارين والواجبات المنزلية بخصم خاص', 'includes Student Book and Activity/Workbook with special bundle savings.', $t);
            $t = str_replace('تشمل كتاب الحصة وكتاب التمارين والواجبات المنزلية بخصم خاص', 'includes Student Book and Activity/Workbook with special bundle savings.', $t);
            $t = str_replace('تشمل كتاب الطالب وكتاب التمارين والواجبات بخصم خاص', 'includes Student Book and Activity/Workbook with special bundle savings.', $t);
            $t = str_replace('تشمل دليل المعلم الشامل، بطاقات الفلاش كارد، والتسجيلات الصوتية الرقمية.', "includes Comprehensive Teacher's Guide, Flashcards, and Digital MP3 Audio Tracks.", $t);
            $t = str_replace('تشمل دليل المعلم، الفلاش كارد، والتسجيلات الصوتية الرقمية', "includes Teacher's Guide, Flashcards, and Digital MP3 Audio Tracks.", $t);
            $t = str_replace('تشمل دليل المعلم، البطاقات التعليمية، والملفات الصوتية الرقمية', "includes Teacher's Guide, Flashcards, and Digital MP3 Audio Tracks.", $t);
            $t = str_replace('تشمل كتاب الحصة والأنشطة والتمارين المنزلية (حصة وتمارين معاً) بخصم خاص.', 'includes Student Book and Workbook & Homework Activities with special bundle savings.', $t);
            return $t;
        }
        return $value;
    }

    public function getAgeSuitedAttribute($value)
    {
        if (empty($value)) {
            $catSlug = strtolower($this->category?->slug ?? '');
            if (str_contains($catSlug, 'english')) return app()->getLocale() === 'en' ? '6-12 Years' : '6-12 سنة';
            if (str_contains($catSlug, 'ashbal') || str_contains($catSlug, 'riyad')) return app()->getLocale() === 'en' ? '5-6 Years' : '5-6 سنوات';
            $lvl = (int) ($this->level ?? 1);
            if ($lvl <= 4) return app()->getLocale() === 'en' ? '6-10 Years' : '6-10 سنوات';
            return app()->getLocale() === 'en' ? '11-16 Years' : '11-16 سنة';
        }
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $t = str_replace('سنوات', 'Years', $value);
            $t = str_replace('سنة', 'Years', $t);
            return $t;
        }
        return $value;
    }

    public function getDimensionsAttribute($value)
    {
        if (empty($value)) {
            if ($this->isTeacherBundle()) return 'A4 / 8 × 12 cm';
            return '22 × 28 cm';
        }
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            return str_replace('سم', 'cm', $value);
        }
        return $value;
    }
}

