<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Handle adding a product to cart for guests (session_id) or registered users (user_id).
     */
    public function addToCart($productId, $quantity = 1)
    {
        $userId = auth()->id();
        $sessionId = Session::getId();

        $product = Product::find($productId);
        if (!$product) {
            return false; // Product not found
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
        ]);

        // Remember the guest cart id: the session ID is regenerated on login,
        // but session data survives, so we can merge the cart afterwards.
        if (!$userId) {
            Session::put('guest_cart_id', $cart->id);
        }

        $currencyService = app(\App\Services\CurrencyService::class);
        $userCurrency = $currencyService->getUserCurrency();
        $userRegion = $currencyService->getUserRegion();
        $effectivePrice = $currencyService->getProductPrice($product, $userRegion);

        if (!$cart->currency || $cart->currency !== $userCurrency) {
            $cart->currency = $userCurrency;
            $cart->save();
        }

        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->price = $effectivePrice;
            $cartItem->currency = $userCurrency;
            $cartItem->save();
        } else {
            $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $effectivePrice,
                'currency' => $userCurrency,
            ]);
        }

        return $cart;
    }

    /**
     * Handle adding all products in a bundle to the cart in a single action.
     */
    public function addBundleToCart($bundleId, $quantity = 1)
    {
        $bundle = \App\Models\Bundle::with('products')->findOrFail($bundleId);
        $cart = null;

        foreach ($bundle->products as $product) {
            $cart = $this->addToCart($product->id, $quantity);
        }

        return $bundle;
    }

    /**
     * Merge guest cart into user cart upon login.
     */
    public function transferSessionCartToUserCart($user)
    {
        $guestCartId = Session::pull('guest_cart_id');
        $sessionCart = $guestCartId
            ? Cart::with('cartItems')->whereNull('user_id')->find($guestCartId)
            : Cart::with('cartItems')->where('session_id', Session::getId())->whereNull('user_id')->first();

        if ($sessionCart) {
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($sessionCart->cartItems as $item) {
                $existingItem = $userCart->cartItems()->where('product_id', $item->product_id)->first();
                if ($existingItem) {
                    $existingItem->quantity += $item->quantity;
                    $existingItem->save();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }

            // Cleanup old session cart
            $sessionCart->delete();
        }
    }

    /**
     * Update the quantity of a cart item.
     */
    public function updateQuantity($cartItemId, $quantity)
    {
        $quantity = min(100, max(1, (int) $quantity)); // Keep quantity between 1 and 100
        $cartItem = $this->findOwnedItem($cartItemId);
        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
            return true;
        }
        return false;
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem($cartItemId)
    {
        $cartItem = $this->findOwnedItem($cartItemId);
        if ($cartItem) {
            $cartItem->delete();
            return true;
        }
        return false;
    }

    /**
     * Find a cart item only if it belongs to the current user's / guest's cart.
     */
    protected function findOwnedItem($cartItemId)
    {
        $userId = auth()->id();

        return CartItem::where('id', $cartItemId)
            ->whereHas('cart', function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereNull('user_id')->where('session_id', Session::getId());
                }
            })
            ->first();
    }
}
