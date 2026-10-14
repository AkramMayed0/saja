<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'driver_id', 'status', 'delivery_status', 
        'total_amount', 'currency', 'currency_symbol', 'shipping_address', 'shipping_lat', 'shipping_lng',
        'driver_lat', 'driver_lng', 'driver_last_updated_at'
    ];

    /**
     * Get formatted total amount with currency symbol.
     */
    public function getFormattedTotalAttribute(): string
    {
        $symbol = $this->currency_symbol ?: ($this->currency === 'USD' ? '$' : 'ر.س');
        $amount = number_format($this->total_amount, 2);
        return $this->currency === 'USD' ? '$' . $amount : $amount . ' ' . $symbol;
    }

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

    /**
     * A customer may cancel only while the order is still pending
     * (not being prepared) and has not gone out for delivery.
     */
    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === 'pending'
            && ! in_array($this->delivery_status, ['out_for_delivery', 'delivered', 'cancelled'], true);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
