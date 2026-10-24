@extends('layouts.admin')

@section('title', __('إدارة الباقات التعليمية'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('الباقات والمناهج التعليمية الشاملة') }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('إدارة السلاسل والباقات المتكاملة والكتب المرتبطة بكل مرحلة') }}</p>
        </div>
        <a href="{{ route('admin.bundles.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>{{ __('إضافة باقة جديدة') }}</span>
        </a>
    </div>

    <!-- Filter Bar (GET) -->
    <form method="GET" action="{{ route('admin.bundles.index') }}" class="mb-6 p-3.5 bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row gap-3 items-stretch md:items-center">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ __('ابحث باسم الباقة أو المستوى...') }}" 
                   class="w-full text-xs ps-9 pe-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500">
        </div>

        <!-- Category Dropdown -->
        <div class="w-full md:w-56">
            <select name="category_id" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                <option value="all">{{ __('كل الأقسام الدراسية') }}</option>
                @php
                    $parentCats = $categories->whereNull('parent_id');
                @endphp
                @if($parentCats->isNotEmpty() && $categories->whereNotNull('parent_id')->isNotEmpty())
                    @foreach($parentCats as $pCat)
                        <optgroup label="📁 {{ $pCat->name }}">
                            <option value="{{ $pCat->id }}" {{ request('category_id') == $pCat->id ? 'selected' : '' }}>
                                {{ __('★ كل') }} {{ $pCat->name }}
                            </option>
                            @foreach($pCat->children as $child)
                                <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;↳ {{ $child->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 rounded-xl transition-colors shadow-2xs">
                {{ __('تصفية') }}
            </button>
            @if(request('search') || (request('category_id') && request('category_id') !== 'all'))
                <a href="{{ route('admin.bundles.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors shrink-0">
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
                    <th class="pb-3 font-semibold">{{ __('الباقة التعليمية') }}</th>
                    <th class="pb-3 font-semibold">{{ __('القسم والمرحلة') }}</th>
                    <th class="pb-3 font-semibold">{{ __('السعر (SAR / USD)') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('الحالة') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($bundles as $bundle)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5">
                            <div class="flex items-center gap-3">
                                @if($bundle->image)
                                    <img src="{{ asset('storage/' . $bundle->image) }}" class="w-10 h-13 object-cover rounded-md shadow-2xs shrink-0" alt="{{ $bundle->name }}">
                                @elseif($bundle->cover_image)
                                    <img src="{{ asset('storage/' . $bundle->cover_image) }}" class="w-10 h-13 object-cover rounded-md shadow-2xs shrink-0" alt="{{ $bundle->name }}">
                                @else
                                    <div class="w-10 h-13 bg-primary-50 dark:bg-primary-950/60 rounded-md flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200/60 dark:border-primary-800/60">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate max-w-xs">{{ $bundle->name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                        /bundles/{{ $bundle->slug }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 block">
                                {{ $bundle->category->name ?? __('بدون قسم') }}
                            </span>
                            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                @if($bundle->level)
                                    <span>{{ __('المستوى:') }} {{ $bundle->level }}</span>
                                    <span>•</span>
                                @endif
                                <span>{{ $bundle->products_count }} {{ __('كتب/مواد') }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-xs font-bold space-y-1">
                            <div class="text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-400 font-normal">SAR:</span>
                                <span>{{ number_format($bundle->effective_price_sar, 2) }} ر.س</span>
                            </div>
                            <div class="text-sky-600 dark:text-sky-400 flex items-center gap-1.5 font-medium">
                                <span class="text-[10px] text-slate-400 font-normal">USD:</span>
                                <span>${{ number_format($bundle->effective_price_usd, 2) }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-center">
                            @if($bundle->is_active)
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60">
                                    {{ __('مفعلة') }}
                                </span>
                            @else
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-500 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    {{ __('معطلة') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.bundles.edit', $bundle) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-primary-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الباقة؟ سيتم إلغاء ارتباط الكتب الملحقة بها.') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="{{ __('حذف') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                            {{ __('لا توجد باقات تعليمية تطابق معايير البحث.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
        {{ $bundles->withQueryString()->links() }}
    </div>
</div>
@endsection
