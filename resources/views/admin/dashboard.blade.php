@extends('layouts.admin')

@section('title', __('لوحة التحكم - المؤشرات الرئيسية'))

@section('content')
<!-- Row 1: Primary Financial & Action KPIs (4 Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6">
    <!-- 1. Completed Revenue in SAR -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي المبيعات (ر.س)') }}</span>
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <span class="text-sm font-bold">ر.س</span>
            </div>
        </div>
        <div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ number_format($completedSarRevenue, 2) }} <span class="text-xs font-bold text-slate-400">ر.س</span>
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                <span class="text-slate-400">{{ __('هذا الشهر:') }}</span>
                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($thisMonthSarRevenue, 2) }} ر.س</span>
            </p>
        </div>
    </div>
    
    <!-- 2. Completed Revenue in USD -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي المبيعات ($)') }}</span>
            <div class="w-9 h-9 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                <span class="text-sm font-bold">$</span>
            </div>
        </div>
        <div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                ${{ number_format($completedUsdRevenue, 2) }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                <span class="text-slate-400">{{ __('هذا الشهر:') }}</span>
                <span class="font-bold text-sky-600 dark:text-sky-400">${{ number_format($thisMonthUsdRevenue, 2) }}</span>
            </p>
        </div>
    </div>

    <!-- 3. Pending Orders (Action Required) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-xs border {{ $pendingOrdersCount > 0 ? 'border-amber-300 dark:border-amber-800/80 bg-amber-50/20' : 'border-slate-200/80 dark:border-slate-800' }} flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('طلبات بانتظار المعالجة') }}</span>
            <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $pendingOrdersCount }}
                </h3>
                @if($pendingOrdersCount > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300">
                        {{ __('إجراء مطلوب') }}
                    </span>
                @endif
            </div>
            <div class="mt-1.5">
                <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-1">
                    <span>{{ __('معاينة الطلبات العالقة') }}</span>
                    <span class="rtl:rotate-180">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Today's Orders -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('طلبات اليوم') }}</span>
            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
        <div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ $todayOrdersCount }}
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                <span class="text-slate-400">{{ __('مقارنة بأمس:') }}</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $yesterdayOrdersCount }} {{ __('طلب') }}</span>
            </p>
        </div>
    </div>
</div>

<!-- Row 2: Store Health & Insights (2 Split Cards) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <!-- 1. Order Status Breakdown -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>{{ __('ملخص حالات الطلبات') }}</span>
                </h3>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('الإجمالي:') }} {{ $totalOrders }} {{ __('طلب') }}</span>
            </div>

            <!-- Segmented Progress Bar -->
            <div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden flex mb-5">
                @if($totalOrders > 0)
                    @if($statusPercentages['completed'] > 0)
                        <div style="width: {{ $statusPercentages['completed'] }}%" class="bg-emerald-500 transition-all" title="{{ __('مكتمل:') }} {{ $statusPercentages['completed'] }}%"></div>
                    @endif
                    @if($statusPercentages['processing'] > 0)
                        <div style="width: {{ $statusPercentages['processing'] }}%" class="bg-blue-500 transition-all" title="{{ __('قيد التجهيز:') }} {{ $statusPercentages['processing'] }}%"></div>
                    @endif
                    @if($statusPercentages['pending'] > 0)
                        <div style="width: {{ $statusPercentages['pending'] }}%" class="bg-amber-500 transition-all" title="{{ __('قيد الانتظار:') }} {{ $statusPercentages['pending'] }}%"></div>
                    @endif
                    @if($statusPercentages['cancelled'] > 0)
                        <div style="width: {{ $statusPercentages['cancelled'] }}%" class="bg-rose-500 transition-all" title="{{ __('ملغي:') }} {{ $statusPercentages['cancelled'] }}%"></div>
                    @endif
                @else
                    <div class="w-full bg-slate-200 dark:bg-slate-700"></div>
                @endif
            </div>

            <!-- Status Pills Breakdown -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <!-- Completed -->
                <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="p-2.5 rounded-lg border border-slate-200/70 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('مكتمل') }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-base font-black text-slate-900 dark:text-white">{{ $statusCounts['completed'] }}</span>
                        <span class="text-[11px] text-slate-400">{{ $statusPercentages['completed'] }}%</span>
                    </div>
                </a>

                <!-- Processing -->
                <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="p-2.5 rounded-lg border border-slate-200/70 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('قيد التجهيز') }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-base font-black text-slate-900 dark:text-white">{{ $statusCounts['processing'] }}</span>
                        <span class="text-[11px] text-slate-400">{{ $statusPercentages['processing'] }}%</span>
                    </div>
                </a>

                <!-- Pending -->
                <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="p-2.5 rounded-lg border border-slate-200/70 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('قيد الانتظار') }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-base font-black text-slate-900 dark:text-white">{{ $statusCounts['pending'] }}</span>
                        <span class="text-[11px] text-slate-400">{{ $statusPercentages['pending'] }}%</span>
                    </div>
                </a>

                <!-- Cancelled -->
                <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}" class="p-2.5 rounded-lg border border-slate-200/70 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ __('ملغي') }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="text-base font-black text-slate-900 dark:text-white">{{ $statusCounts['cancelled'] }}</span>
                        <span class="text-[11px] text-slate-400">{{ $statusPercentages['cancelled'] }}%</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Top 5 Best-Selling Products -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 sm:p-6 shadow-xs border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                <span>{{ __('الأكثر مبيعاً') }}</span>
            </h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">{{ __('كل المنتجات') }}</a>
        </div>

        <div class="space-y-3">
            @forelse($topProducts as $item)
                @if($item->product)
                    <div class="flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->title }}" class="w-10 h-12 object-cover rounded-md shadow-2xs shrink-0">
                            @else
                                <div class="w-10 h-12 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-slate-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $item->product->title }}</h4>
                                <span class="text-[11px] text-slate-400 block truncate">{{ $item->product->category->name ?? __('بدون قسم') }}</span>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-full bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 border border-primary-100/60 dark:border-primary-900/60">
                            {{ $item->total_sold }} {{ __('مباعة') }}
                        </span>
                    </div>
                @endif
            @empty
                <div class="py-8 text-center text-xs text-slate-400">
                    {{ __('لا توجد مبيعات مسجلة حتى الآن.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Bottom: Recent Orders Table -->
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('أحدث الطلبات') }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('آخر 5 طلبات تم تسجيلها في المتجر') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
            <span>{{ __('عرض كل الطلبات') }}</span>
            <span class="rtl:rotate-180">←</span>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[700px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('رقم الطلب') }}</th>
                    <th class="pb-3 font-semibold">{{ __('العميل') }}</th>
                    <th class="pb-3 font-semibold">{{ __('التاريخ والوقت') }}</th>
                    <th class="pb-3 font-semibold">{{ __('الإجمالي') }}</th>
                    <th class="pb-3 font-semibold">{{ __('الحالة') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('الإجراء') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recent_orders as $order)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 font-bold text-slate-900 dark:text-white">
                            #{{ $order->id }}
                        </td>
                        <td class="py-3.5 text-slate-700 dark:text-slate-300 font-medium">
                            {{ $order->user->name ?? __('عميل زائر') }}
                        </td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400 text-xs" dir="ltr">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="py-3.5 font-bold text-slate-900 dark:text-white">
                            {{ $order->formatted_total }}
                        </td>
                        <td class="py-3.5">
                            @php
                                $statusBadges = [
                                    'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/60',
                                    'processing' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800/60',
                                    'completed' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60',
                                    'cancelled' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800/60',
                                ];
                                $statusLabels = [
                                    'pending' => __('قيد الانتظار'),
                                    'processing' => __('قيد التجهيز'),
                                    'completed' => __('مكتمل'),
                                    'cancelled' => __('ملغي'),
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusBadges[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="py-3.5 text-center">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                <span>{{ __('معاينة') }}</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400 text-xs">{{ __('لا توجد طلبات مسجلة بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
