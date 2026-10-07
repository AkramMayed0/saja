@extends('layouts.admin')

@section('title', __('العملاء'))

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ __('قائمة العملاء') }}</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-4 font-bold">{{ __('العميل') }}</th>
                    <th class="pb-4 font-bold">{{ __('البريد الإلكتروني') }}</th>
                    <th class="pb-4 font-bold">{{ __('تاريخ التسجيل') }}</th>
                    <th class="pb-4 font-bold">{{ __('عدد الطلبات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-lg shrink-0">
                                    {{ mb_substr($customer->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-gray-900 dark:text-white">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td class="py-5 text-gray-600 dark:text-gray-300">
                            {{ $customer->email }}
                        </td>
                        <td class="py-5 text-sm text-gray-500 dark:text-gray-400">
                            {{ $customer->created_at->format('Y-m-d') }}
                        </td>
                        <td class="py-5">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm font-bold">
                                {{ $customer->orders_count }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500 dark:text-gray-400 text-lg">{{ __('لا يوجد عملاء مسجلين بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $customers->links() }}
    </div>
</div>
@endsection
