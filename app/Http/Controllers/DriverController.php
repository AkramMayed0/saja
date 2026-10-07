<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DriverController extends Controller
{
    /**
     * Display a listing of orders assigned to the driver.
     */
    public function index()
    {
        $orders = Order::where('driver_id', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereIn('delivery_status', ['assigned', 'out_for_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.index', compact('orders'));
    }

    /**
     * Update the driver's current location via API.
     */
    public function updateLocation(Request $request, Order $order)
    {
        if ((int) $order->driver_id !== (int) auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $order->update([
            'driver_lat' => $request->lat,
            'driver_lng' => $request->lng,
            'driver_last_updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Update the delivery status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        if ((int) $order->driver_id !== (int) auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', __('هذا الطلب مغلق ولا يمكن تحديث حالته.'));
        }

        $request->validate([
            'delivery_status' => 'required|in:out_for_delivery,delivered',
        ]);

        $data = ['delivery_status' => $request->delivery_status];
        if ($request->delivery_status === 'delivered') {
            $data['status'] = 'completed';
        }

        $order->update($data);

        return back()->with('success', __('تم تحديث حالة التوصيل بنجاح.'));
    }
}
