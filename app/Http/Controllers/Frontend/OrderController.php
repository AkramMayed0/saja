<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Order details (owner only).
     */
    public function show(Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Let a customer cancel their own order while it hasn't been handled yet.
     */
    public function cancel(Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        // Lock the row and re-check inside the transaction so a simultaneous
        // admin/driver update can't slip past the cancel check.
        $cancelled = DB::transaction(function () use ($order) {
            $fresh = Order::whereKey($order->id)->lockForUpdate()->first();

            if (! $fresh || ! $fresh->canBeCancelledByCustomer()) {
                return false;
            }

            $fresh->update([
                'status' => 'cancelled',
                'delivery_status' => 'cancelled',
                'driver_id' => null,
                'driver_lat' => null,
                'driver_lng' => null,
                'driver_last_updated_at' => null,
            ]);

            return true;
        });

        if (! $cancelled) {
            return back()->with('error', __('لا يمكن إلغاء هذا الطلب لأنه قيد التجهيز أو التوصيل. يرجى التواصل معنا.'));
        }

        return back()->with('success', __('تم إلغاء طلبك بنجاح.'));
    }
}
