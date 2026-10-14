@extends('layouts.admin')

@section('title', __('تفاصيل الطلب') . ' #' . $order->id)

@section('content')
<!-- Top Bar: Back Link & Print Action -->
<div class="flex items-center justify-between gap-4 mb-6 print:hidden">
    <a href="{{ route('admin.orders') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-3.5 py-2 rounded-xl transition-colors shadow-2xs">
        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        <span>{{ __('العودة لقائمة الطلبات') }}</span>
    </a>

    <!-- Print Invoice Button -->
    <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/80 rounded-xl transition-colors shadow-2xs">
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        <span>{{ __('طباعة الفاتورة') }}</span>
    </button>
</div>

<!-- Print-Only Official Invoice Header -->
<div class="hidden print:block pb-6 mb-6 border-b border-slate-300">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">{{ __('دار سجى للنشر والتوزيع') }}</h1>
            <p class="text-xs text-slate-500">{{ __('فاتورة بيع رسمية') }}</p>
        </div>
        <div class="text-left text-xs">
            <p class="font-black text-base text-slate-900">#{{ $order->id }}</p>
            <p class="text-slate-600 mt-1" dir="ltr">{{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p class="font-bold text-slate-700 mt-1">{{ __('الحالة:') }} {{ $order->status }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6 p-4 bg-slate-50 rounded-lg text-xs">
        <div>
            <span class="font-bold text-slate-500 block mb-1">{{ __('بيانات المشتري:') }}</span>
            <p class="font-bold text-slate-900 text-sm">{{ $order->user->name ?? __('عميل زائر') }}</p>
            <p class="text-slate-600">{{ $order->user->email ?? '' }}</p>
        </div>
        <div>
            <span class="font-bold text-slate-500 block mb-1">{{ __('عنوان التسليم:') }}</span>
            <p class="text-slate-800 leading-relaxed">{{ $order->shipping_address }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 print:block">
    <!-- Left Column: Actions & Customer Info -->
    <div class="lg:col-span-1 space-y-5 print:hidden">
        
        <!-- Status Card -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center justify-between">
                <span>{{ __('تحديث حالة الطلب') }}</span>
                <span class="text-[11px] text-slate-400 font-normal">#{{ $order->id }}</span>
            </h3>
            
            @php
                $statusColors = [
                    'pending' => 'bg-amber-50 text-amber-800 border-amber-200/80 dark:bg-amber-950/40 dark:border-amber-800/60 dark:text-amber-300',
                    'processing' => 'bg-blue-50 text-blue-800 border-blue-200/80 dark:bg-blue-950/40 dark:border-blue-800/60 dark:text-blue-300',
                    'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80 dark:bg-emerald-950/40 dark:border-emerald-800/60 dark:text-emerald-300',
                    'cancelled' => 'bg-rose-50 text-rose-800 border-rose-200/80 dark:bg-rose-950/40 dark:border-rose-800/60 dark:text-rose-300',
                ];
                $currentColor = $statusColors[$order->status] ?? 'bg-slate-50 border-slate-200 text-slate-700';
            @endphp
            
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl py-2 px-3 font-bold transition-colors cursor-pointer border mb-3 shadow-2xs {{ $currentColor }}">
                    <option value="pending" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                    <option value="processing" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('قيد التجهيز') }}</option>
                    <option value="completed" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'completed' ? 'selected' : '' }}>{{ __('مكتمل') }}</option>
                    <option value="cancelled" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('ملغي') }}</option>
                </select>
                <div class="text-[11px] text-slate-400 space-y-1">
                    <p>{{ __('تاريخ الطلب:') }} <span class="font-semibold text-slate-600 dark:text-slate-300" dir="ltr">{{ $order->created_at->format('Y-m-d H:i') }}</span></p>
                    @if($order->updated_at != $order->created_at)
                        <p>{{ __('آخر تحديث:') }} <span class="font-semibold text-slate-600 dark:text-slate-300" dir="ltr">{{ $order->updated_at->format('Y-m-d H:i') }}</span></p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Driver Assignment Card -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">{{ __('إسناد وتكليف السائق') }}</h3>
            
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $order->status }}">
                
                <select name="driver_id" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 py-2 px-3 mb-3 text-slate-800 dark:text-slate-200 font-semibold focus:ring-1 focus:ring-primary-500">
                    <option value="">{{ __('-- اختر سائق لتوصيل الطلب --') }}</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ $order->driver_id == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 text-xs font-bold py-2 rounded-xl transition-colors shadow-2xs">
                    {{ __('حفظ إسناد السائق') }}
                </button>
            </form>
            
            @if($order->driver_id)
                <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200/60 dark:border-slate-700 text-xs">
                    <p class="text-slate-700 dark:text-slate-300 font-bold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ __('السائق المكلف:') }}</span>
                        <span>{{ $order->driver->name ?? '' }}</span>
                    </p>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        {{ __('حالة التوصيل:') }}
                        <span class="font-bold text-primary-600 dark:text-primary-400">
                            @if($order->delivery_status === 'pending') {{ __('قيد الانتظار') }}
                            @elseif($order->delivery_status === 'assigned') {{ __('بانتظار قبول السائق') }}
                            @elseif($order->delivery_status === 'out_for_delivery') {{ __('في الطريق للتسليم') }}
                            @elseif($order->delivery_status === 'delivered') {{ __('تم التسليم') }}
                            @elseif($order->delivery_status === 'cancelled') {{ __('ملغي') }}
                            @else {{ $order->delivery_status }}
                            @endif
                        </span>
                    </p>
                </div>
            @endif
        </div>

        <!-- Customer Details Card -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">{{ __('بيانات العميل والشحن') }}</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-xs shrink-0">
                        {{ mb_substr($order->user->name ?? '?', 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $order->user->name ?? __('عميل زائر') }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ $order->user->email ?? '' }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <span class="text-[11px] font-semibold text-slate-400 block mb-1">{{ __('عنوان الشحن التفصيلي:') }}</span>
                    <p class="text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/40 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800">
                        {{ $order->shipping_address }}
                    </p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Right Column: Products List & Invoice Items -->
    <div class="lg:col-span-2 print:col-span-3">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 print:border-none print:shadow-none print:p-0">
            <div class="flex items-center justify-between mb-5 print:hidden">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>{{ __('المنتجات المطلوبة') }}</span>
                </h3>
                <span class="text-xs text-slate-400">{{ count($order->items) }} {{ __('عناصر') }}</span>
            </div>
            
            <div class="divide-y divide-slate-100 dark:divide-slate-800 print:divide-slate-200">
                @foreach($order->items as $item)
                    <div class="py-3.5 flex gap-3.5 items-center">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->title }}" class="w-12 h-14 object-cover rounded-md shadow-2xs shrink-0 print:hidden">
                        @else
                            <div class="w-12 h-14 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-slate-400 shrink-0 print:hidden">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm truncate">
                                {{ $item->product ? $item->product->title : __('منتج محذوف') }}
                            </h4>
                            @if($item->product && $item->product->sku)
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $item->product->sku }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs mt-1 text-slate-500">
                                <span class="font-semibold">{{ __('الكمية:') }} {{ $item->quantity }}</span>
                                <span class="font-semibold">{{ number_format($item->price, 2) }} {{ $order->currency_symbol ?? ($order->currency === 'USD' ? '$' : 'ر.س') }}</span>
                            </div>
                        </div>
                        
                        <div class="text-left shrink-0">
                            <span class="font-black text-sm sm:text-base text-slate-900 dark:text-white">
                                {{ number_format($item->price * $item->quantity, 2) }} {{ $order->currency_symbol ?? ($order->currency === 'USD' ? '$' : 'ر.س') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-5 pt-4 border-t border-slate-200 dark:border-slate-800 print:border-slate-300 flex justify-between items-center">
                <div>
                    <span class="text-sm font-bold text-slate-600 dark:text-slate-400 block">{{ __('الإجمالي النهائي') }}</span>
                    <span class="text-[11px] text-slate-400">{{ __('شامل الضريبة والشحن إن وجد') }}</span>
                </div>
                <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
