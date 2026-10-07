@extends('layouts.admin')

@section('title', __('الرئيسية - الإحصائيات'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Products -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('إجمالي المنتجات') }}</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['total_products'] }}</h3>
        </div>
        <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('إجمالي الطلبات') }}</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</h3>
        </div>
        <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('عدد العملاء') }}</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['total_customers'] }}</h3>
        </div>
        <div class="w-14 h-14 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('الإيرادات (مكتملة)') }}</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-white">${{ number_format($stats['total_revenue'], 2) }}</h3>
        </div>
        <div class="w-14 h-14 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('أحدث الطلبات') }}</h3>
        <a href="{{ route('admin.orders') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline text-sm">{{ __('عرض الكل') }}</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-3 font-bold">{{ __('رقم الطلب') }}</th>
                    <th class="pb-3 font-bold">{{ __('العميل') }}</th>
                    <th class="pb-3 font-bold">{{ __('التاريخ') }}</th>
                    <th class="pb-3 font-bold">{{ __('الإجمالي') }}</th>
                    <th class="pb-3 font-bold">{{ __('الحالة') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recent_orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 font-bold text-gray-900 dark:text-white">#{{ $order->id }}</td>
                        <td class="py-4 text-gray-600 dark:text-gray-300">{{ $order->user->name }}</td>
                        <td class="py-4 text-gray-500 dark:text-gray-400 text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-4 font-bold text-primary-600 dark:text-primary-400">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-4">
                            @php
                                $statusLabels = [
                                    'pending' => __('قيد الانتظار'),
                                    'processing' => __('قيد التجهيز'),
                                    'completed' => __('مكتمل'),
                                    'cancelled' => __('ملغي'),
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border
                                {{ $order->status === 'pending' ? 'bg-orange-50 text-orange-600 border-orange-100 dark:bg-orange-900/30 dark:border-orange-800/50 dark:text-orange-400' : '' }}
                                {{ $order->status === 'processing' ? 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-900/30 dark:border-primary-800/50 dark:text-primary-400' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-50 text-green-600 border-green-100 dark:bg-green-900/30 dark:border-green-800/50 dark:text-green-400' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:border-red-800/50 dark:text-red-400' : '' }}
                            ">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">{{ __('لا توجد طلبات بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
