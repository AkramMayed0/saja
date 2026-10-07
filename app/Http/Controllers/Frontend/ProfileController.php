<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->with('items.product')->latest()->get();

        return view('profile.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()];
        }

        $request->validate($rules);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', __('تم تحديث بيانات الحساب بنجاح.'));
    }

    public function tracking(\App\Models\Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'lat' => $order->driver_lat,
            'lng' => $order->driver_lng,
            'status' => $order->delivery_status,
            'last_updated' => $order->driver_last_updated_at ? $order->driver_last_updated_at->diffForHumans() : null,
        ]);
    }
}
