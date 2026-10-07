<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'driver_id', 'status', 'delivery_status', 
        'total_amount', 'shipping_address', 'shipping_lat', 'shipping_lng',
        'driver_lat', 'driver_lng', 'driver_last_updated_at'
    ];

    protected function casts(): array
    {
        return [
            'driver_last_updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
