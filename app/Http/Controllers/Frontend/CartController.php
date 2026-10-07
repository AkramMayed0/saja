<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $userId = auth()->id();
        $sessionId = Session::getId();

        $cart = Cart::with('cartItems.product')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId)->whereNull('user_id');
                }
            })->first();

        $total = 0;
        if ($cart) {
            foreach ($cart->cartItems as $item) {
                $total += $item->price * $item->quantity;
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $this->cartService->addToCart($request->product_id, 1);

        return back()->with('success', __('تمت إضافة المنتج للسلة بنجاح'));
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $this->cartService->updateQuantity($itemId, $request->quantity);

        return back()->with('success', __('تم تحديث الكمية بنجاح'));
    }

    public function remove($itemId)
    {
        $this->cartService->removeItem($itemId);

        return back()->with('success', __('تم حذف المنتج من السلة'));
    }
}
