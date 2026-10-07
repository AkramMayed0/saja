@extends('layouts.admin')

@section('title', __('إدارة الطلبات'))

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-4 font-bold">{{ __('رقم الطلب') }}</th>
                    <th class="pb-4 font-bold">{{ __('العميل') }}</th>
                    <th class="pb-4 font-bold">{{ __('عنوان الشحن') }}</th>
                    <th class="pb-4 font-bold">{{ __('الإجمالي') }}</th>
                    <th class="pb-4 font-bold">{{ __('تحديث الحالة') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group">
                        <td class="py-5 font-bold text-gray-900 dark:text-white">
                            <span class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">#{{ $order->id }}</span>
                            <div class="text-xs text-gray-400 font-normal mt-1">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                        </td>
                        <td class="py-5">
                            <div class="text-gray-800 dark:text-gray-200 font-bold">{{ $order->user->name }}</div>
                        </td>
                        <td class="py-5 text-gray-500 dark:text-gray-400 text-sm max-w-xs truncate" title="{{ $order->shipping_address }}">
                            {{ $order->shipping_address }}
                        </td>
                        <td class="py-5 font-bold text-primary-600 dark:text-primary-400">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-5" onclick="event.stopPropagation()">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-orange-50 text-orange-600 border-orange-200 focus:border-orange-500 focus:ring-orange-500 dark:bg-orange-900/30 dark:border-orange-800 dark:text-orange-400',
                                    'processing' => 'bg-primary-50 text-primary-600 border-primary-200 focus:border-primary-500 focus:ring-primary-500 dark:bg-primary-900/30 dark:border-primary-800 dark:text-primary-400',
                                    'completed' => 'bg-green-50 text-green-600 border-green-200 focus:border-green-500 focus:ring-green-500 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-200 focus:border-red-500 focus:ring-red-500 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400',
                                ];
                                $currentColor = $statusColors[$order->status] ?? 'bg-gray-50 border-gray-200';
                            @endphp
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-sm rounded-xl py-1.5 px-3 font-bold transition-colors shadow-sm cursor-pointer hover:shadow-md {{ $currentColor }}">
                                    <option value="pending" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                    <option value="processing" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('قيد التجهيز') }}</option>
                                    <option value="completed" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'completed' ? 'selected' : '' }}>{{ __('مكتمل') }}</option>
                                    <option value="cancelled" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('ملغي') }}</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400 text-lg">{{ __('لا توجد طلبات بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>
@endsection
