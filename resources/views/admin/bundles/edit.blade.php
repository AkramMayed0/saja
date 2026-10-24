@extends('layouts.admin')

@section('title', __('تعديل الباقة: :name', ['name' => $bundle->name]))

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-3.5 py-2 rounded-xl transition-colors shadow-2xs">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            <span>{{ __('العودة لقائمة الباقات') }}</span>
        </a>
        <div class="text-[11px] text-slate-400 font-mono">
            /bundles/{{ $bundle->slug }}
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('تعديل بيانات الباقة التعليمية') }}</h2>
                <p class="text-xs text-slate-400 mt-1">{{ __('تعديل تفاصيل باقة المنهج وتحديث الكتب والمواد المرتبطة بها وأسعارها') }}</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full font-bold {{ $bundle->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                {{ $bundle->is_active ? __('مفعلة') : __('معطلة') }}
            </span>
        </div>

        <form action="{{ route('admin.bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Group 1: Basic Info -->
            <div>
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('البيانات الأساسية للباقة') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('اسم الباقة التعليمية *') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $bundle->name) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" 
                               placeholder="{{ __('مثال: باقة منهج English Friends - المستوى الأول الشامل') }}" required>
                        @error('name')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('القسم الدراسي *') }}</label>
                        <select id="category_id" name="category_id" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer" required>
                            <option value="" disabled>{{ __('-- اختر القسم الدراسي --') }}</option>
                            @php
                                $parentCats = $categories->whereNull('parent_id');
                            @endphp
                            @if($parentCats->isNotEmpty() && $categories->whereNotNull('parent_id')->isNotEmpty())
                                @foreach($parentCats as $pCat)
                                    @if($pCat->children && $pCat->children->isNotEmpty())
                                        <optgroup label="📁 {{ $pCat->name }}">
                                            @foreach($pCat->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id', $bundle->category_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $pCat->id }}" {{ old('category_id', $bundle->category_id) == $pCat->id ? 'selected' : '' }}>{{ $pCat->name }}</option>
                                    @endif
                                @endforeach
                                @foreach($categories->whereNotNull('parent_id')->whereNotIn('parent_id', $parentCats->pluck('id')) as $standalone)
                                    <option value="{{ $standalone->id }}" {{ old('category_id', $bundle->category_id) == $standalone->id ? 'selected' : '' }}>{{ $standalone->name }}</option>
                                @endforeach
                            @else
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $bundle->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('المستوى التعليمي / الصف *') }}</label>
                        <input type="text" id="level" name="level" value="{{ old('level', $bundle->level) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" 
                               placeholder="{{ __('مثال: 1, 2, KG1...') }}">
                        @error('level')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Group 2: Pricing -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider">
                        {{ __('تسعير الباقة المخفّض') }}
                    </h3>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">
                        {{ __('مجموع أسعار الكتب المكونة للباقة:') }} 
                        <strong class="text-slate-800 dark:text-slate-200">{{ number_format($bundle->calculated_sar, 2) }} ر.س</strong>
                        <span class="text-slate-400">/</span>
                        <strong class="text-slate-800 dark:text-slate-200">${{ number_format($bundle->calculated_usd, 2) }}</strong>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Price SAR -->
                    <div>
                        <label for="price_sar" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('سعر الباقة الإجمالي (اليمن - ر.س) *') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" id="price_sar" name="price_sar" value="{{ old('price_sar', $bundle->price_sar > 0 ? $bundle->price_sar : $bundle->effective_price_sar) }}" 
                                   class="w-full text-xs p-3 pe-12 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" 
                                   placeholder="0.00" required>
                            <span class="absolute end-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">ر.س</span>
                        </div>
                        @error('price_sar')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Price USD -->
                    <div>
                        <label for="price_usd" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('سعر الباقة الإجمالي (دولي - $) *') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" id="price_usd" name="price_usd" value="{{ old('price_usd', $bundle->price_usd > 0 ? $bundle->price_usd : $bundle->effective_price_usd) }}" 
                                   class="w-full text-xs p-3 pe-10 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" 
                                   placeholder="0.00" required>
                            <span class="absolute end-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">$</span>
                        </div>
                        @error('price_usd')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Group 3: Associated Products Selector -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <div>
                        <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider">
                            {{ __('الكتب والمواد التعليمية المكونة للباقة') }}
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            {{ __('حدد الكتب والملفات التابعة لهذه الباقة:') }} 
                            <span class="font-bold text-slate-700 dark:text-slate-300">
                                {{ __('تم تحديد') }} <span id="selected-count-badge" class="text-primary-600 dark:text-primary-400">0</span> {{ __('من أصل') }} {{ count($products) }} {{ __('عنصر') }}
                            </span>
                        </p>
                    </div>

                    <!-- Smart Auto-Select Button -->
                    <button type="button" id="auto-select-matching-btn" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-950/60 hover:bg-primary-100 dark:hover:bg-primary-900/60 border border-primary-200/80 dark:border-primary-800 rounded-xl transition-all cursor-pointer shadow-2xs active:scale-95 shrink-0"
                            title="{{ __('تحديد كل الكتب والمواد التي تطابق القسم الدراسي والمستوى المختار أعلاه تلقائياً بضغطة واحدة') }}">
                        <svg class="w-3.5 h-3.5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>{{ __('تحديد كتب هذا المنهج تلقائياً') }}</span>
                    </button>
                </div>

                <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden bg-white dark:bg-slate-900 shadow-2xs">
                    <!-- Search & Filter Controls -->
                    <!-- Search & Filter Controls -->
                    <div class="p-3 bg-slate-50/80 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 space-y-2.5">
                        <!-- Search Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" id="product-search" 
                                   placeholder="{{ __('ابحث بسرعة باسم الكتاب، المستوى، الرمز، أو القسم...') }}" 
                                   class="w-full text-xs ps-9 pe-28 py-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                            <div class="absolute inset-y-0 end-0 flex items-center pe-2.5 gap-1.5">
                                <button type="button" id="search-clear-btn" class="hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 cursor-pointer rounded" title="{{ __('مسح البحث') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <span class="text-[11px] font-semibold text-slate-400 pointer-events-none">
                                    <span id="visible-count-badge">{{ count($products) }}</span>/{{ count($products) }}
                                </span>
                            </div>
                        </div>

                        <!-- Filter Pills & Bulk Actions -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-0.5">
                            <!-- Type Filters -->
                            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                <button type="button" data-filter="all" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-primary-600 text-white shadow-xs transition-colors cursor-pointer">
                                    {{ __('الكل') }}
                                </button>
                                <button type="button" data-filter="selected" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">
                                    {{ __('المحددة فقط') }}
                                </button>
                                <button type="button" data-filter="student" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">
                                    {{ __('كتب الطالب والتمارين') }}
                                </button>
                                <button type="button" data-filter="audio" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">
                                    {{ __('صوتيات MP3') }}
                                </button>
                                <button type="button" data-filter="teacher" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">
                                    {{ __('أدلة المعلم') }}
                                </button>
                                <button type="button" data-filter="flashcard" class="filter-pill-btn px-2.5 py-1 rounded-lg font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors cursor-pointer">
                                    {{ __('بطاقات') }}
                                </button>
                            </div>

                            <!-- Bulk Select Helpers -->
                            <div class="flex items-center gap-2 text-[11px]">
                                <button type="button" id="select-all-visible-btn" class="text-primary-600 dark:text-primary-400 hover:underline font-semibold cursor-pointer">
                                    {{ __('تحديد كل الظاهر') }}
                                </button>
                                <span class="text-slate-300 dark:text-slate-700">|</span>
                                <button type="button" id="deselect-all-btn" class="text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 font-semibold cursor-pointer">
                                    {{ __('إلغاء تحديد الكل') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scrollable List of Items -->
                    <div id="product-list-container" class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 p-2 space-y-1">
                        @forelse($products as $prod)
                            <label class="product-item flex items-center gap-3 p-2 rounded-lg border border-transparent hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer transition-colors"
                                   data-title="{{ mb_strtolower($prod->title) }}"
                                   data-category-id="{{ $prod->category_id }}"
                                   data-category-name="{{ mb_strtolower($prod->category->name ?? '') }}"
                                   data-level="{{ mb_strtolower($prod->level ?? '') }}"
                                   data-type="{{ $prod->book_type }}"
                                   data-sku="{{ mb_strtolower($prod->sku ?? '') }}">
                                
                                <input type="checkbox" name="product_ids[]" value="{{ $prod->id }}" 
                                       class="product-checkbox rounded border-slate-300 text-primary-600 focus:ring-primary-500 w-4 h-4 cursor-pointer shrink-0"
                                       {{ in_array($prod->id, old('product_ids', $selectedProductIds)) ? 'checked' : '' }}>

                                <!-- Small Thumbnail -->
                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" class="w-8 h-10 object-cover rounded shadow-2xs shrink-0" alt="{{ $prod->title }}">
                                @else
                                    <div class="w-8 h-10 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center text-slate-400 shrink-0 text-[10px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $prod->title }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">
                                            {{ $prod->category->name ?? '' }} 
                                            @if($prod->level) | {{ __('المستوى:') }} {{ $prod->level }} @endif
                                            @if($prod->sku) | {{ $prod->sku }} @endif
                                        </p>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300">
                                            {{ number_format($prod->price_sar ?: $prod->price, 2) }} ر.س
                                        </span>
                                        @if($prod->isAudio())
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">MP3</span>
                                        @elseif($prod->book_type === 'teacher')
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">{{ __('دليل معلم') }}</span>
                                        @elseif($prod->book_type === 'flashcard')
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">{{ __('بطاقات') }}</span>
                                        @else
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">{{ __('كتاب') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">{{ __('لا توجد منتجات مسجلة في المتجر بعد.') }}</div>
                        @endforelse

                        <!-- Empty state when search or filter matches 0 items -->
                        <div id="product-empty-search" class="hidden py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <p class="font-medium text-slate-500 dark:text-slate-400">{{ __('لا توجد نتائج مطابقة لبحثك أو التصنيف المحدد.') }}</p>
                            <p class="text-[11px] text-slate-400 mt-1">{{ __('جرّب البحث باسم آخر أو مسح حقل البحث.') }}</p>
                        </div>
                    </div>
                </div>
                @error('product_ids')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
            </div>

            <!-- Group 4: Media & Details -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('صورة الغلاف والوصف') }}
                </h3>

                <!-- Cover Image -->
                <div>
                    <label for="image" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('تغيير صورة غلاف الباقة (اختياري)') }}</label>
                    
                    @if($bundle->image || $bundle->cover_image)
                        <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200/70 dark:border-slate-700/70">
                            <img src="{{ asset('storage/' . ($bundle->image ?: $bundle->cover_image)) }}" class="w-14 h-18 object-cover rounded-lg shadow-2xs" alt="{{ $bundle->name }}">
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                <p class="font-bold text-slate-700 dark:text-slate-200">{{ __('الغلاف الحالي') }}</p>
                                <p class="text-[11px]">{{ __('اختر ملفاً جديداً أدناه إذا كنت ترغب في استبداله.') }}</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" id="image" name="image" 
                           class="w-full text-xs p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 file:me-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-950/60 dark:file:text-primary-300 hover:file:bg-primary-100 cursor-pointer" 
                           accept="image/*">
                    <p class="text-[11px] text-slate-400 mt-1">{{ __('اتركه فارغاً للاحتفاظ بالغلاف الحالي.') }}</p>
                    @error('image')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('الوصف والمميزات التعليمية') }}</label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium leading-relaxed" 
                              placeholder="{{ __('نبذة عن مميزات هذه الباقة الشاملة ومكوناتها ومخرجات التعلم...') }}">{{ old('description', $bundle->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Active Status Toggle -->
                <div class="flex items-center gap-2 pt-2">
                    <label class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bundle->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span>{{ __('تفعيل ونشر الباقة في واجهة المتجر') }}</span>
                    </label>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.bundles.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ __('حفظ التعديلات') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('product-search');
        const searchClearBtn = document.getElementById('search-clear-btn');
        const filterPills = document.querySelectorAll('.filter-pill-btn');
        const items = document.querySelectorAll('.product-item');
        const visibleCountBadge = document.getElementById('visible-count-badge');
        const selectedCountBadge = document.getElementById('selected-count-badge');
        const selectAllVisibleBtn = document.getElementById('select-all-visible-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const autoSelectBtn = document.getElementById('auto-select-matching-btn');
        const categorySelect = document.getElementById('category_id');
        const levelInput = document.getElementById('level');
        const emptyState = document.getElementById('product-empty-search');

        let currentFilter = 'all';
        let currentSearch = '';

        function updateItemVisuals() {
            let visibleCount = 0;
            let selectedCount = 0;

            items.forEach(item => {
                const checkbox = item.querySelector('.product-checkbox');
                const isChecked = checkbox && checkbox.checked;
                if (isChecked) {
                    selectedCount++;
                    item.classList.add('bg-primary-50/50', 'dark:bg-primary-950/30', 'border-primary-200', 'dark:border-primary-800/70');
                    item.classList.remove('border-transparent');
                } else {
                    item.classList.remove('bg-primary-50/50', 'dark:bg-primary-950/30', 'border-primary-200', 'dark:border-primary-800/70');
                    item.classList.add('border-transparent');
                }

                // Check filter pill condition
                const itemType = (item.getAttribute('data-type') || '').toLowerCase();
                const itemTitle = (item.getAttribute('data-title') || '').toLowerCase();
                let passesFilter = true;

                if (currentFilter === 'selected') {
                    passesFilter = isChecked;
                } else if (currentFilter === 'audio') {
                    passesFilter = itemType === 'mp3' || itemType === 'audio' || itemTitle.includes('audio') || itemTitle.includes('صوت');
                } else if (currentFilter === 'teacher') {
                    passesFilter = itemType === 'teacher' || itemTitle.includes('teacher') || itemTitle.includes('معلم');
                } else if (currentFilter === 'flashcard') {
                    passesFilter = itemType === 'flashcard' || itemTitle.includes('card') || itemTitle.includes('بطاق');
                } else if (currentFilter === 'student') {
                    passesFilter = !['mp3', 'audio', 'teacher', 'flashcard'].includes(itemType) 
                        && !itemTitle.includes('teacher') && !itemTitle.includes('معلم')
                        && !itemTitle.includes('card') && !itemTitle.includes('بطاق')
                        && !itemTitle.includes('audio');
                }

                // Check search query
                let passesSearch = true;
                if (currentSearch.trim() !== '') {
                    const searchNeedle = currentSearch.trim().toLowerCase();
                    const title = (item.getAttribute('data-title') || '').toLowerCase();
                    const catName = (item.getAttribute('data-category-name') || '').toLowerCase();
                    const level = (item.getAttribute('data-level') || '').toLowerCase();
                    const sku = (item.getAttribute('data-sku') || '').toLowerCase();

                    passesSearch = title.includes(searchNeedle) || 
                                   catName.includes(searchNeedle) || 
                                   level.includes(searchNeedle) || 
                                   sku.includes(searchNeedle);
                }

                if (passesFilter && passesSearch) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (visibleCountBadge) visibleCountBadge.textContent = visibleCount;
            if (selectedCountBadge) selectedCountBadge.textContent = selectedCount;

            // Selected pill counter text
            const selectedPill = document.querySelector('[data-filter="selected"]');
            if (selectedPill) {
                selectedPill.textContent = `{{ __('المحددة فقط') }} (${selectedCount})`;
            }

            // Empty state display
            if (emptyState) {
                if (visibleCount === 0 && items.length > 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        // Live Search Input
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentSearch = this.value;
                if (searchClearBtn) {
                    if (currentSearch.length > 0) {
                        searchClearBtn.classList.remove('hidden');
                    } else {
                        searchClearBtn.classList.add('hidden');
                    }
                }
                updateItemVisuals();
            });
        }

        // Clear Search
        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', function () {
                if (searchInput) {
                    searchInput.value = '';
                    currentSearch = '';
                    searchClearBtn.classList.add('hidden');
                    searchInput.focus();
                    updateItemVisuals();
                }
            });
        }

        // Filter Pills
        filterPills.forEach(pill => {
            pill.addEventListener('click', function () {
                filterPills.forEach(p => {
                    p.classList.remove('bg-primary-600', 'text-white', 'shadow-xs');
                    p.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
                });
                this.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
                this.classList.add('bg-primary-600', 'text-white', 'shadow-xs');

                currentFilter = this.getAttribute('data-filter');
                updateItemVisuals();
            });
        });

        // Checkbox change listener
        items.forEach(item => {
            const checkbox = item.querySelector('.product-checkbox');
            if (checkbox) {
                checkbox.addEventListener('change', updateItemVisuals);
            }
        });

        // Select All Visible
        if (selectAllVisibleBtn) {
            selectAllVisibleBtn.addEventListener('click', function () {
                items.forEach(item => {
                    if (!item.classList.contains('hidden')) {
                        const cb = item.querySelector('.product-checkbox');
                        if (cb) cb.checked = true;
                    }
                });
                updateItemVisuals();
            });
        }

        // Deselect All
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function () {
                items.forEach(item => {
                    const cb = item.querySelector('.product-checkbox');
                    if (cb) cb.checked = false;
                });
                updateItemVisuals();
            });
        }

        // Smart 1-Click Auto Select matching Category & Level
        if (autoSelectBtn) {
            autoSelectBtn.addEventListener('click', function () {
                const selCatId = categorySelect ? categorySelect.value : '';
                const selLevel = levelInput ? levelInput.value.trim().toLowerCase() : '';
                const selCatText = categorySelect && categorySelect.selectedIndex > 0 ? categorySelect.options[categorySelect.selectedIndex].text.toLowerCase() : '';

                if (!selCatId && !selLevel) {
                    alert('يرجى اختيار القسم الدراسي أو تحديد المستوى أعلاه أولاً ليتم تحديد الكتب تلقائياً.');
                    return;
                }

                let matchedCount = 0;
                items.forEach(item => {
                    const itemCatId = item.getAttribute('data-category-id') || '';
                    const itemCatName = (item.getAttribute('data-category-name') || '').toLowerCase();
                    const itemLevel = (item.getAttribute('data-level') || '').toLowerCase();
                    const itemTitle = (item.getAttribute('data-title') || '').toLowerCase();

                    let catMatches = (!selCatId) || (itemCatId === selCatId) || (selCatText && itemCatName.includes(selCatText));
                    let levelMatches = true;

                    if (selLevel) {
                        levelMatches = (itemLevel === selLevel) || itemTitle.includes(selLevel);
                    }

                    if (catMatches && levelMatches) {
                        const cb = item.querySelector('.product-checkbox');
                        if (cb) {
                            cb.checked = true;
                            matchedCount++;
                        }
                    }
                });

                updateItemVisuals();

                // Feedback animation
                const originalHtml = autoSelectBtn.innerHTML;
                autoSelectBtn.innerHTML = `
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-emerald-700 dark:text-emerald-300 font-bold">تم تحديد ${matchedCount} كتاب بنجاح</span>
                `;
                setTimeout(() => {
                    autoSelectBtn.innerHTML = originalHtml;
                }, 2500);
            });
        }

        // Initialize state
        updateItemVisuals();
    });
</script>
@endpush
