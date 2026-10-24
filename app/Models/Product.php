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
     * Get pre-discount original price for active or specified region.
     */
    public function getOriginalPriceForRegion(?string $region = null): ?float
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $region = $region ?: $currencyService->getUserRegion();

        if ($region === \App\Services\CurrencyService::REGION_YE) {
            return $this->original_price_sar !== null ? (float)$this->original_price_sar : null;
        }

        return $this->original_price_usd !== null ? (float)$this->original_price_usd : null;
    }

    /**
     * Pre-discount original price based on current user region.
     */
    public function getOriginalPriceAttribute(): ?float
    {
        return $this->getOriginalPriceForRegion();
    }

    /**
     * Whether this product has a pre-discount price.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price !== null && $this->original_price > $this->current_price;
    }

    /**
     * Savings percentage for product.
     */
    public function getSavingsPercentageAttribute(): int
    {
        if (!$this->has_discount || !$this->original_price || $this->original_price <= 0) {
            return 0;
        }

        return (int) round((($this->original_price - $this->current_price) / $this->original_price) * 100);
    }

    /**
     * Formatted original price string.
     */
    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if ($this->original_price === null) {
            return null;
        }
        return app(\App\Services\CurrencyService::class)->formatPrice($this->original_price);
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

    public function getTitleAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $trans = __($value);
            if ($trans !== $value) {
                return $trans;
            }
            $t = preg_replace('/^سلسلة الحوسبة والذكاء الاصطناعي الأمن السيبراني\s+(\d+)/u', 'Computing, AI & Cybersecurity Series $1', $value);
            $t = preg_replace('/^سلسلة الحوسبة والذكاء الاصطناعي والأمن السيبراني \(انجليزي\)\s+(\d+)/u', 'Computing, AI & Cybersecurity Series (English) $1', $t);
            $t = preg_replace('/^سلسلة اشبال التعليمية \(تمهيدي 2\)\s+(\d+)/u', 'Ashbal Educational Series (KG 2) $1', $t);
            $t = preg_replace('/^سلسلة اشبال التعليمية \(تمهيدي 1\)\s+(\d+)/u', 'Ashbal Educational Series (KG 1) $1', $t);
            return $t;
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
        }
        return $value;
    }

    public function getPublisherAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            if (in_array($value, ['مؤسسة سجى المتخصصة', 'موسسة سجى المتخصصة']) || empty($value)) {
                return 'Saja Specialized Foundation';
            }
            if ($value === 'دار سجى للنشر' || $value === 'دار سجى') {
                return 'Saja Publishing House';
            }
            $trans = __($value);
            if ($trans !== $value) {
                return $trans;
            }
        }
        return $value;
    }

    public function getAuthorAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            if ($value === 'دار سجى للنشر' || $value === 'دار سجى') {
                return 'Saja Publishing';
            }
            if ($value === 'فريق دار سجى العلمي' || $value === 'الفريق العلمي') {
                return 'Saja Academic Editorial Team';
            }
            $trans = __($value ?? '');
            if ($trans !== ($value ?? '')) {
                return $trans;
            }
        }
        return $value;
    }

    public function getLanguageAttribute($value)
    {
        $normalized = strtolower(trim($value ?? ''));
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            return match ($normalized) {
                'العربية', 'عربي', 'arabic' => 'Arabic',
                'الإنجليزية', 'إنجليزي', 'english' => 'English',
                default => $value ? __($value) : $value,
            };
        }
        return match ($normalized) {
            'arabic', 'العربية', 'عربي' => 'العربية',
            'english', 'الإنجليزية', 'إنجليزي' => 'الإنجليزية',
            default => $value ? __($value) : $value,
        };
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
            if ($this->isTeacher()) return 'A4 (21 × 29.7 cm)';
            if ($this->isFlashcard()) return '8 × 12 cm';
            if ($this->isAudio()) return app()->getLocale() === 'en' ? 'MP3 Digital Audio' : 'ملفات صوتية MP3';
            return '22 × 28 cm';
        }
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $t = str_replace('سم', 'cm', $value);
            $t = str_replace('ملفات صوتية MP3', 'MP3 Digital Audio', $t);
            return $t;
        }
        return $value;
    }
}

