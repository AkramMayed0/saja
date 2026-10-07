@extends('layouts.admin')

@section('title', __('تفاصيل الطلب') . ' #' . $order->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        {{ __('العودة لقائمة الطلبات') }}
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Customer & Order Info -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">{{ __('تحديث الحالة') }}</h3>
            
            @php
                $statusColors = [
                    'pending' => 'bg-orange-50 text-orange-600 border-orange-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-orange-900/30 dark:border-orange-800 dark:text-orange-400',
                    'processing' => 'bg-primary-50 text-primary-600 border-primary-200 focus:border-primary-500 focus:ring-primary-500 dark:bg-primary-900/30 dark:border-primary-800 dark:text-primary-400',
                    'completed' => 'bg-green-50 text-green-600 border-green-200 focus:border-green-500 focus:ring-green-500 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400',
                    'cancelled' => 'bg-red-50 text-red-600 border-red-200 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400',
                ];
                $currentColor = $statusColors[$order->status] ?? 'bg-gray-50 border-gray-200';
            @endphp
            
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="w-full text-sm rounded-xl py-2.5 px-4 font-bold transition-colors shadow-sm cursor-pointer hover:shadow-md mb-2 {{ $currentColor }}">
                    <option value="pending" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                    <option value="processing" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('قيد التجهيز') }}</option>
                    <option value="completed" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'completed' ? 'selected' : '' }}>{{ __('مكتمل') }}</option>
                    <option value="cancelled" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('ملغي') }}</option>
                </select>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('تاريخ الطلب:') }} <span class="font-bold" dir="ltr">{{ $order->created_at->format('Y-m-d h:i A') }}</span></p>
                @if($order->updated_at != $order->created_at)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('آخر تحديث:') }} <span class="font-bold" dir="ltr">{{ $order->updated_at->format('Y-m-d h:i A') }}</span></p>
                @endif
            </form>
        </div>

        <!-- Driver Assignment Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">{{ __('إسناد المندوب') }}</h3>
            
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $order->status }}">
                
                <select name="driver_id" class="w-full text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-2.5 px-4 mb-4 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500">
                    <option value="">{{ __('-- اختر مندوباً لتوصيل الطلب --') }}</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="w-full bg-primary-600 text-white font-bold py-2.5 rounded-xl hover:bg-primary-700 transition-colors shadow-sm">
                    {{ __('حفظ إسناد المندوب') }}
                </button>
            </form>
            
            @if($order->driver_id)
                <div class="mt-4 p-3 bg-primary-50 dark:bg-primary-900/30 rounded-xl border border-primary-100 dark:border-primary-800/50">
                    <p class="text-sm text-primary-700 dark:text-primary-400 font-bold flex items-center gap-2">
                        {{ __('مندوب التوصيل:') }} {{ $order->driver->name ?? '' }}
                    </p>
                    <p class="text-xs text-primary-600 dark:text-primary-300 mt-2 font-bold">
                        {{ __('حالة التوصيل:') }} 
                        @if($order->delivery_status === 'pending') {{ __('قيد الانتظار') }}
                        @elseif($order->delivery_status === 'assigned') {{ __('بانتظار موافقة المندوب') }}
                        @elseif($order->delivery_status === 'out_for_delivery') {{ __('في الطريق إلى العميل') }}
                        @elseif($order->delivery_status === 'delivered') {{ __('تم التسليم بنجاح') }}
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Customer Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">{{ __('بيانات العميل') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-lg">
                        {{ mb_substr($order->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->user->email }}</p>
                    </div>
                </div>
                <hr class="border-gray-100 dark:border-gray-700">
                <div>
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">{{ __('عنوان الشحن التفصيلي') }}</span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-relaxed">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Right Column: Products List -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                {{ __('المنتجات المطلوبة') }}
            </h3>
            
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($order->items as $item)
                    <div class="py-4 flex gap-4 items-center">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->title }}" class="w-16 h-20 object-cover rounded-lg shadow-sm">
                        @else
                            <div class="w-16 h-20 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1 text-sm md:text-base">{{ $item->product ? $item->product->title : __('منتج محذوف') }}</h4>
                            @if($item->product)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $item->product->author }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm">
                                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded font-black">{{ __('الكمية:') }} {{ $item->quantity }}</span>
                                <span class="text-gray-600 dark:text-gray-300 font-bold">${{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="text-left shrink-0">
                            <span class="font-black text-lg text-gray-900 dark:text-white">${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-6 border-t-2 border-dashed border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ __('الإجمالي النهائي') }}</span>
                <span class="text-3xl font-black text-primary-600 dark:text-primary-400">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
