<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'youtube_url', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
