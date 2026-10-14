@extends('layouts.admin')

@section('title', __('إدارة المنتجات'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('قائمة المنتجات والكتب') }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('إدارة الكتب والمناهج الدراسية والأسعار بالريال والدولار') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>{{ __('إضافة منتج جديد') }}</span>
        </a>
    </div>

    <!-- Filter Bar (GET) -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 p-3.5 bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row gap-3 items-stretch md:items-center">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ __('ابحث باسم الكتاب أو رمز SKU...') }}" 
                   class="w-full text-xs ps-9 pe-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500">
        </div>

        <!-- Category Dropdown -->
        <div class="w-full md:w-48">
            <select name="category_id" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                <option value="all">{{ __('كل الأقسام') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Format Dropdown -->
        <div class="w-full md:w-44">
            <select name="format" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                <option value="all">{{ __('كل الصيغ والأنواع') }}</option>
                <option value="ورقي" {{ in_array(request('format'), ['ورقي', 'paper', 'physical']) ? 'selected' : '' }}>{{ __('كتاب ورقي') }}</option>
                <option value="mp3" {{ in_array(request('format'), ['mp3', 'audio', 'صوتي']) ? 'selected' : '' }}>{{ __('صوتيات MP3 (Audio)') }}</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 rounded-xl transition-colors shadow-2xs">
                {{ __('تصفية') }}
            </button>
            @if(request('search') || (request('category_id') && request('category_id') !== 'all') || (request('format') && request('format') !== 'all'))
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('إلغاء') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('المنتج') }}</th>
                    <th class="pb-3 font-semibold">{{ __('القسم') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('المستوى') }}</th>
                    <th class="pb-3 font-semibold">{{ __('الصيغة') }}</th>
                    <th class="pb-3 font-semibold">{{ __('السعر (SAR / USD)') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-14 object-cover rounded-md shadow-2xs shrink-0" alt="{{ $product->title }}">
                                @else
                                    <div class="w-10 h-14 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-slate-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate max-w-xs">{{ $product->title }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        <span class="font-mono text-slate-500 dark:text-slate-400">{{ $product->sku ?? ('#' . $product->id) }}</span>
                                        @if($product->author) <span class="text-slate-300 dark:text-slate-600">|</span> {{ $product->author }} @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5">
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $product->category->name ?? __('بدون قسم') }}
                            </span>
                        </td>
                        <!-- Level Column -->
                        <td class="py-3.5 text-center">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                {{ $product->level ?: '-' }}
                            </span>
                        </td>
                        <!-- Format Column -->
                        <td class="py-3.5">
                            @if($product->isAudio() || str_contains(strtolower($product->format ?? ''), 'mp3'))
                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    <span>{{ __('صوتيات MP3') }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                    <span>{{ __('ورقي') }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 text-xs font-bold space-y-1">
                            <div class="text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-400 font-normal">SAR:</span>
                                <span>{{ number_format($product->price_sar ?? $product->price, 2) }} ر.س</span>
                            </div>
                            <div class="text-sky-600 dark:text-sky-400 flex items-center gap-1.5 font-medium">
                                <span class="text-[10px] text-slate-400 font-normal">USD:</span>
                                <span>${{ number_format($product->price_usd ?? round($product->price / 3.75, 2), 2) }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-primary-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المنتج نهائياً؟') }}')">
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
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                            {{ __('لا توجد منتجات تطابق معايير البحث.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
