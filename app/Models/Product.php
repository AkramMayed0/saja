<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = 'B-' . date('Ym') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function isAudio(): bool
    {
        return $this->book_type === 'audio';
    }

    public function isStudent(): bool
    {
        return in_array($this->book_type, ['student', 'combined']);
    }

    public function isWorkbook(): bool
    {
        return $this->book_type === 'workbook';
    }

    public function isTeacher(): bool
    {
        return $this->book_type === 'teacher';
    }

    public function isFlashcard(): bool
    {
        return $this->book_type === 'flashcard';
    }

    /**
     * Human-friendly label for book_type.
     */
    public function getBookTypeLabelAttribute(): string
    {
        return match ($this->book_type) {
            'student' => __('كتاب الطالب'),
            'workbook' => __('كتاب الأنشطة والتمارين'),
            'combined' => __('كتاب شامل (حصة وتمارين)'),
            'teacher' => __('دليل المعلم'),
            'flashcard' => __('البطاقات التعليمية'),
            'audio' => __('الملفات الصوتية والتسجيلات'),
            default => __('ملحق تعليمي'),
        };
    }

    /**
     * Get effective price for active or specified region.
     */
    public function getPriceForRegion(?string $region = null): float
    {
        return app(\App\Services\CurrencyService::class)->getProductPrice($this, $region);
    }

    /**
     * Accessor for price based on current user region.
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->getPriceForRegion();
    }

    /**
     * Formatted price string with active currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->formatPrice($this->current_price);
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

