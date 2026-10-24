<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function bundles()
    {
        return $this->hasMany(Bundle::class);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function getIsParentAttribute(): bool
    {
        return is_null($this->parent_id);
    }

    public function getTotalProductsCountAttribute(): int
    {
        if ($this->relationLoaded('children')) {
            $childrenCount = $this->children->sum(function ($child) {
                return $child->products_count ?? $child->products()->count();
            });
            return ($this->products_count ?? $this->products()->count()) + $childrenCount;
        }

        if ($this->is_parent) {
            return ($this->products_count ?? $this->products()->count())
                + Product::whereIn('category_id', $this->children()->pluck('id'))->count();
        }

        return $this->products_count ?? $this->products()->count();
    }

    public function getTotalBundlesCountAttribute(): int
    {
        if ($this->relationLoaded('children')) {
            $childrenCount = $this->children->sum(function ($child) {
                return $child->bundles_count ?? $child->bundles()->count();
            });
            return ($this->bundles_count ?? $this->bundles()->count()) + $childrenCount;
        }

        if ($this->is_parent) {
            return ($this->bundles_count ?? $this->bundles()->count())
                + Bundle::whereIn('category_id', $this->children()->pluck('id'))->count();
        }

        return $this->bundles_count ?? $this->bundles()->count();
    }

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'en' && !request()->is('admin*')) {
            $trans = __($value);
            if ($trans !== $value) {
                return $trans;
            }
        }
        return $value;
    }
}
