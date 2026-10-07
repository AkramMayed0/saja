<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];
        
        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product']);
        $drivers = User::where('is_driver', true)->get();
        return view('admin.orders.show', compact('order', 'drivers'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'driver_id' => ['nullable', \Illuminate\Validation\Rule::exists('users', 'id')->where('is_driver', true)]
        ]);

        $data = ['status' => $request->status];

        if ($request->has('driver_id')) {
            $newDriverId = $request->driver_id ?: null;
            $data['driver_id'] = $newDriverId;
            
            // Only update delivery_status if the assigned driver actually changed
            if ($newDriverId != $order->driver_id) {
                if ($newDriverId) {
                    $data['delivery_status'] = 'assigned';
                } else {
                    $data['delivery_status'] = 'pending';
                }
            }
        }

        $order->update($data);

        return back()->with('success', __('تم تحديث حالة الطلب بنجاح.'));
    }

    public function customers()
    {
        $customers = User::where('is_admin', false)->withCount('orders')->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }
}
