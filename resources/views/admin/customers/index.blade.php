@extends('layouts.admin')

@section('title', __('العملاء وسائقي التوصيل'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('قائمة الحسابات والعملاء') }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('إدارة حسابات المسجلين ومنح أو إلغاء صلاحية سائق التوصيل بنقرة واحدة') }}</p>
        </div>
    </div>

    <!-- Filter Bar (GET) -->
    <form method="GET" action="{{ route('admin.customers') }}" class="mb-6 p-3.5 bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-200/60 dark:border-slate-700/60 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ __('ابحث بالاسم أو البريد الإلكتروني...') }}" 
                   class="w-full text-xs ps-9 pe-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500">
        </div>

        <!-- Role Dropdown -->
        <div class="w-full sm:w-48">
            <select name="role" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                <option value="">{{ __('كل الحسابات') }}</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>{{ __('العملاء فقط') }}</option>
                <option value="driver" {{ request('role') === 'driver' ? 'selected' : '' }}>{{ __('السائقين فقط') }}</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 rounded-xl transition-colors shadow-2xs">
                {{ __('تصفية') }}
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.customers') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('إلغاء') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[750px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('العميل') }}</th>
                    <th class="pb-3 font-semibold">{{ __('البريد الإلكتروني') }}</th>
                    <th class="pb-3 font-semibold">{{ __('صلاحية سائق توصيل') }}</th>
                    <th class="pb-3 font-semibold">{{ __('تاريخ التسجيل') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('عدد الطلبات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ mb_substr($customer->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td class="py-3.5 text-xs text-slate-600 dark:text-slate-400 font-mono">
                            {{ $customer->email }}
                        </td>
                        <td class="py-3.5">
                            <!-- 1-Click Driver Role Toggle Form -->
                            <form action="{{ route('admin.customers.toggle-driver', $customer) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all border shadow-2xs {{ $customer->is_driver ? 'bg-emerald-50 text-emerald-800 border-emerald-200/80 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60' : 'bg-slate-50 text-slate-600 border-slate-200/80 hover:bg-slate-100 dark:bg-slate-800/50 dark:text-slate-400 dark:border-slate-700/60' }}" 
                                        title="{{ $customer->is_driver ? __('انقر لإلغاء صلاحية السائق') : __('انقر لتعيين المستخدم كسائق توصيل') }}">
                                    @if($customer->is_driver)
                                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span>{{ __('سائق معتمد') }}</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></span>
                                        <span>{{ __('عميل عادي (تعيين سائق)') }}</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="py-3.5 text-xs text-slate-500 dark:text-slate-400" dir="ltr">
                            {{ $customer->created_at->format('Y-m-d') }}
                        </td>
                        <td class="py-3.5 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $customer->orders_count > 0 ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800' }}">
                                {{ $customer->orders_count }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                            {{ __('لا يوجد عملاء يطابقون معايير البحث.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
        {{ $customers->withQueryString()->links() }}
    </div>
</div>
@endsection
