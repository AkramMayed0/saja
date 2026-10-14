@extends('layouts.admin')

@section('title', __('إدارة الطلبات'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <!-- Header & Filter Bar -->
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('قائمة الطلبات') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('متابعة وتحديث حالات طلبات العملاء وتعيين السائقين') }}</p>
            </div>
            
            <!-- Quick Status Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $currentStatus === 'all' || !$currentStatus ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ __('الكل') }}
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 hover:bg-amber-100' }}">
                    {{ __('قيد الانتظار') }}
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => 'processing'])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $currentStatus === 'processing' ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 hover:bg-blue-100' }}">
                    {{ __('قيد التجهيز') }}
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $currentStatus === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 hover:bg-emerald-100' }}">
                    {{ __('مكتمل') }}
                </a>
                <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => 'cancelled'])) }}" 
                   class="px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $currentStatus === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 hover:bg-rose-100' }}">
                    {{ __('ملغي') }}
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.orders') }}" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
            @if(request('status') && request('status') !== 'all')
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('ابحث برقم الطلب # أو اسم العميل أو البريد...') }}" 
                       class="w-full text-xs ps-9 pe-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs">
                    {{ __('بحث') }}
                </button>
                @if(request('search') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('admin.orders') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-xl transition-colors shrink-0">
                        {{ __('إلغاء التصفية') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[750px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('رقم الطلب') }}</th>
                    <th class="pb-3 font-semibold">{{ __('العميل') }}</th>
                    <th class="pb-3 font-semibold">{{ __('عنوان الشحن') }}</th>
                    <th class="pb-3 font-semibold">{{ __('الإجمالي') }}</th>
                    <th class="pb-3 font-semibold">{{ __('تحديث الحالة') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('الإجراء') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors group">
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                #{{ $order->id }}
                            </a>
                            <div class="text-[11px] text-slate-400 mt-0.5" dir="ltr">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                        </td>
                        <td class="py-4">
                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $order->user->name ?? __('عميل زائر') }}</div>
                            @if(isset($order->user->email))
                                <div class="text-xs text-slate-400">{{ $order->user->email }}</div>
                            @endif
                        </td>
                        <td class="py-4 text-slate-600 dark:text-slate-400 text-xs max-w-xs truncate" title="{{ $order->shipping_address }}">
                            {{ $order->shipping_address }}
                        </td>
                        <td class="py-4 font-black text-slate-900 dark:text-white">
                            {{ $order->formatted_total }}
                        </td>
                        <td class="py-4">
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-50 text-amber-800 border-amber-200/80 dark:bg-amber-950/40 dark:border-amber-800/60 dark:text-amber-300',
                                    'processing' => 'bg-blue-50 text-blue-800 border-blue-200/80 dark:bg-blue-950/40 dark:border-blue-800/60 dark:text-blue-300',
                                    'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80 dark:bg-emerald-950/40 dark:border-emerald-800/60 dark:text-emerald-300',
                                    'cancelled' => 'bg-rose-50 text-rose-800 border-rose-200/80 dark:bg-rose-950/40 dark:border-rose-800/60 dark:text-rose-300',
                                ];
                                $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-50 border-slate-200 text-slate-700';
                            @endphp
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg py-1 px-2.5 font-bold border transition-colors cursor-pointer shadow-2xs {{ $currentStyle }}">
                                    <option value="pending" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                    <option value="processing" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('قيد التجهيز') }}</option>
                                    <option value="completed" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'completed' ? 'selected' : '' }}>{{ __('مكتمل') }}</option>
                                    <option value="cancelled" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-white" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('ملغي') }}</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-4 text-center">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                <span>{{ __('تفاصيل') }}</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                            {{ __('لا توجد طلبات تطابق معايير البحث.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
