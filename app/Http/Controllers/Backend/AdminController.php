<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Completed Revenue in SAR (SAR or default currency)
        $completedSarRevenue = (float) Order::where('status', 'completed')
            ->where(function ($q) {
                $q->where('currency', 'SAR')->orWhereNull('currency')->orWhere('currency', '');
            })->sum('total_amount');

        $thisMonthSarRevenue = (float) Order::where('status', 'completed')
            ->where(function ($q) {
                $q->where('currency', 'SAR')->orWhereNull('currency')->orWhere('currency', '');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // 2. Completed Revenue in USD
        $completedUsdRevenue = (float) Order::where('status', 'completed')
            ->where('currency', 'USD')
            ->sum('total_amount');

        $thisMonthUsdRevenue = (float) Order::where('status', 'completed')
            ->where('currency', 'USD')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // 3. Today's Orders vs Yesterday's
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
        $yesterdayOrdersCount = Order::whereDate('created_at', today()->subDay())->count();

        // 4. Order Status Breakdown & Counts (Single Grouped Query)
        $rawCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusCounts = [
            'pending'    => (int) $rawCounts->get('pending', 0),
            'processing' => (int) $rawCounts->get('processing', 0),
            'completed'  => (int) $rawCounts->get('completed', 0),
            'cancelled'  => (int) $rawCounts->get('cancelled', 0),
        ];
        $totalOrders = array_sum($statusCounts);
        $pendingOrdersCount = $statusCounts['pending'];

        $statusPercentages = [];
        foreach ($statusCounts as $key => $count) {
            $statusPercentages[$key] = $totalOrders > 0 ? round(($count / $totalOrders) * 100, 1) : 0;
        }

        // 6. Top 5 Best-Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with(['product.category'])
            ->take(5)
            ->get();

        // 7. Recent 5 Orders
        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'completedSarRevenue',
            'thisMonthSarRevenue',
            'completedUsdRevenue',
            'thisMonthUsdRevenue',
            'pendingOrdersCount',
            'todayOrdersCount',
            'yesterdayOrdersCount',
            'totalOrders',
            'statusCounts',
            'statusPercentages',
            'topProducts',
            'recent_orders'
        ));
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $orderId = ltrim($search, '#');
            $query->where(function ($q) use ($search, $orderId) {
                if (is_numeric($orderId)) {
                    $q->orWhere('id', (int) $orderId);
                }
                $q->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

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

        if ($request->status === 'cancelled') {
            $data['delivery_status'] = 'cancelled';
            $data['driver_id'] = null;
        }

        $order->update($data);

        return back()->with('success', __('تم تحديث حالة الطلب بنجاح.'));
    }

    public function customers(Request $request)
    {
        $query = User::where('is_admin', false)->withCount('orders')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                if (Schema::hasColumn('users', 'phone')) {
                    $q->orWhere('phone', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('role')) {
            if ($request->role === 'driver') {
                $query->where('is_driver', true);
            } elseif ($request->role === 'customer') {
                $query->where('is_driver', false);
            }
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function toggleDriver(User $user)
    {
        $user->is_driver = !$user->is_driver;
        $user->save();

        $message = $user->is_driver
            ? __('تم تعيين المستخدم كسائق توصيل بنجاح.')
            : __('تم إلغاء صلاحية سائق التوصيل عن المستخدم بنجاح.');

        return back()->with('success', $message);
    }
}

