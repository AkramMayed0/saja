<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', __('سلتك فارغة.'));
        }

        $total = $cart->cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'shipping_lat' => 'nullable|numeric',
            'shipping_lng' => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('shop.index');
        }

        $total = $cart->cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $shippingAddress = implode(', ', [
            $request->name,
            $request->address,
            $request->city,
            $request->phone
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_address' => $shippingAddress,
                'shipping_lat' => $request->shipping_lat,
                'shipping_lng' => $request->shipping_lng,
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // Clear the cart
            $cart->cartItems()->delete();

            DB::commit();

            return redirect()->route('checkout.success')->with('success', __('تم استلام طلبك بنجاح'))->with('order_id', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('حدث خطأ أثناء معالجة الطلب، يرجى المحاولة مرة أخرى.'));
        }
    }

    public function success()
    {
        return view('checkout.success');
    }
}
