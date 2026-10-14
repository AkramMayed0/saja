@extends('layouts.store')

@section('content')
@php
    // 0 = received, 1 = preparing, 2 = on the way, 3 = delivered
    $step = match (true) {
        $order->status === 'cancelled' => -1,
        $order->status === 'completed' || $order->delivery_status === 'delivered' => 3,
        $order->delivery_status === 'out_for_delivery' => 2,
        $order->status === 'processing' || $order->delivery_status === 'assigned' => 1,
        default => 0,
    };
    $steps = [
        __('تم استلام الطلب'),
        __('قيد التجهيز'),
        __('في الطريق إليك'),
        __('تم التسليم'),
    ];
@endphp

<div class="container-x max-w-4xl py-10 sm:py-14">
    <a href="{{ route('profile.index') }}" class="arrow-link mb-6 inline-flex">
        <svg class="h-4 w-4 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        {{ __('العودة إلى حسابي') }}
    </a>

    <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-sm font-bold text-gray-400">{{ __('تفاصيل الطلب') }}</p>
            <h1 class="font-display text-3xl font-extrabold text-gray-900 dark:text-white">#{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div class="font-display text-3xl font-black text-primary-600 dark:text-primary-400">{{ $order->formatted_total }}</div>
    </div>

    {{-- Status --}}
    <section class="card mb-6 p-6 sm:p-8" aria-label="{{ __('حالة الطلب') }}">
        @if($step === -1)
            <div class="flex items-center gap-3 text-red-600 dark:text-red-400">
                <svg class="h-7 w-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                <p class="font-bold">{{ __('تم إلغاء هذا الطلب') }}</p>
            </div>
        @else
            <ol class="grid grid-cols-4 gap-2">
                @foreach($steps as $i => $label)
                    @php $done = $i <= $step; @endphp
                    <li class="relative flex flex-col items-center text-center">
                        @if($i > 0)
                            <span class="absolute top-4 end-1/2 h-0.5 w-full {{ $i <= $step ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-700' }}" aria-hidden="true"></span>
                        @endif
                        <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold {{ $done ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400 dark:bg-gray-700' }} {{ $i === $step ? 'ring-4 ring-primary-200 dark:ring-primary-900' : '' }}">
                            @if($done)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </span>
                        <span class="mt-2 text-xs font-semibold sm:text-sm {{ $done ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">{{ $label }}</span>
                    </li>
                @endforeach
            </ol>
        @endif
    </section>

    <div class="grid gap-6 md:grid-cols-2">
        <section class="card p-6">
            <h2 class="mb-3 font-bold text-gray-900 dark:text-white">{{ __('عنوان التوصيل') }}</h2>
            <p class="text-sm leading-7 text-gray-600 dark:text-gray-300">{{ $order->shipping_address }}</p>
        </section>
        <section class="card p-6">
            <h2 class="mb-3 font-bold text-gray-900 dark:text-white">{{ __('طريقة الدفع') }}</h2>
            <p class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
                <svg class="h-5 w-5 text-leaf-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                {{ __('الدفع عند الاستلام') }}
            </p>
        </section>
    </div>

    {{-- Items --}}
    <section class="card mt-6 p-6">
        <h2 class="mb-4 font-bold text-gray-900 dark:text-white">{{ __('المنتجات') }}</h2>
        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($order->items as $item)
                <li class="flex items-center justify-between gap-4 py-3 text-sm">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-50 font-bold text-primary-600 dark:bg-primary-900/30 dark:text-primary-400">{{ $item->quantity }}×</span>
                        <span class="truncate font-semibold text-gray-700 dark:text-gray-200">{{ $item->product->title ?? __('منتج غير متاح') }}</span>
                    </div>
                    <span class="shrink-0 font-bold text-gray-600 dark:text-gray-300">{{ number_format($item->price * $item->quantity, 2) }} {{ $order->currency_symbol ?? ($order->currency === 'USD' ? '$' : 'ر.س') }}</span>
                </li>
            @endforeach
        </ul>
        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-700">
            <span class="font-bold text-gray-900 dark:text-white">{{ __('الإجمالي') }}</span>
            <span class="font-display text-xl font-black text-primary-600 dark:text-primary-400">{{ $order->formatted_total }}</span>
        </div>
    </section>

    {{-- Cancel --}}
    @if($order->canBeCancelledByCustomer())
        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-6 flex justify-end" onsubmit="return confirm(@js(__('هل أنت متأكد من إلغاء هذا الطلب؟')))">
            @csrf
            @method('PATCH')
            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-2 text-sm font-bold text-red-600 transition-colors hover:bg-red-100 dark:border-red-800/50 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                {{ __('إلغاء الطلب') }}
            </button>
        </form>
    @elseif(in_array($order->status, ['pending', 'processing']))
        <p class="mt-6 text-xs font-medium text-gray-400">{{ __('لا يمكن إلغاء الطلب بعد بدء التجهيز أو التوصيل. للمساعدة يرجى التواصل معنا.') }}</p>
    @endif
</div>
@endsection
