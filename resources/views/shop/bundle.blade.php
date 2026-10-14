@extends('layouts.store')

@section('title', $bundle->name)

@section('content')
<div class="py-8 sm:py-12 transition-colors duration-300">
    <div class="container-x">
        
        <!-- ============================== BREADCRUMBS ============================== -->
        <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400 font-medium" aria-label="{{ __('المسار') }}">
            <ol class="flex items-center space-x-2 space-x-reverse flex-wrap gap-y-1">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('الرئيسية') }}</a>
                </li>
                <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                <li>
                    <a href="{{ route('shop.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('المناهج') }}</a>
                </li>
                @if($bundle->category)
                    <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                    <li>
                        <a href="{{ route('shop.index', ['category' => $bundle->category->slug]) }}" 
                           title="{{ $bundle->category->name }}"
                           class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            {{ Str::words($bundle->category->name, 4, '...') }}
                        </a>
                    </li>
                @endif
                <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                <li class="font-bold text-gray-900 dark:text-white" 
                    title="{{ $bundle->name }}" 
                    aria-current="page">
                    {{ Str::words($bundle->name, 5, '...') }}
                </li>
            </ol>
        </nav>

        <!-- ============================== MAIN BUNDLE SHOWCASE ============================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            
            <!-- Left Column: Sticky Bundle Cover (5 cols) -->
            <div class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-4 sm:p-6 shadow-soft transition-colors">
                    
                    <!-- Cover Image -->
                    <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 dark:bg-gray-800/60 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-center group">
                        @php
                            $cover = $bundle->cover_image ? asset('storage/' . $bundle->cover_image) : 'https://placehold.co/600x800/f1f5f9/94a3b8?text=Bundle+Cover';
                        @endphp
                        <img src="{{ $cover }}" 
                             alt="{{ $bundle->name }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                        <span class="absolute top-3 start-3 px-3 py-1 rounded-full text-xs font-bold bg-white/90 dark:bg-gray-900/90 text-primary-700 dark:text-primary-300 shadow-sm backdrop-blur border border-white/40 dark:border-gray-700">
                            {{ __('باقة شاملة متكاملة') }}
                        </span>
                    </div>

                    <!-- Highlights & Summary below cover -->
                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-800/80 space-y-2.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>{{ __('تضم') }} <strong class="text-gray-800 dark:text-gray-200 font-bold">{{ $bundle->products->count() }}</strong> {{ __('ملحقات تعليمية متكاملة لهذا المستوى') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-leaf-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('شحن سريع وتوصيل متاح لكافة المدارس والمحافظات') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Information, What's Inside Checklist & Purchase Box (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                
                <!-- Header Block: Badges & Title -->
                <div>
                    <!-- Badges Row -->
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if($bundle->category)
                            <a href="{{ route('shop.index', ['category' => $bundle->category->slug]) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary-50 dark:bg-primary-950/60 text-primary-700 dark:text-primary-300 border border-primary-200/60 dark:border-primary-800/60 hover:bg-primary-100 transition-colors">
                                {{ $bundle->category->name }}
                            </a>
                        @endif

                        @if($bundle->level)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                {{ __('المستوى:') }} {{ $bundle->level }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white leading-tight tracking-tight mb-3">
                        {{ $bundle->name }}
                    </h1>

                    @if($bundle->description)
                        <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base leading-relaxed text-justify">
                            {{ $bundle->description }}
                        </p>
                    @endif
                </div>

                <!-- ==================== DIRECT VISUAL CHECKLIST: WHAT'S INSIDE ==================== -->
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 sm:p-7 shadow-soft">
                    <div class="pb-3.5 mb-4 border-b border-gray-100 dark:border-gray-800">
                        <h2 class="text-base font-extrabold text-gray-900 dark:text-white">
                            {{ __('ماذا تحتوي هذه الباقة؟') }}
                        </h2>
                    </div>

                    <!-- Checklist Items List -->
                    <div class="space-y-2.5">
                        @foreach($bundle->products as $item)
                            <div onclick="if (!event.target.closest('.no-row-click')) window.location='{{ route('shop.show', $item->slug) }}'"
                                 class="flex items-center justify-between gap-3 p-3 sm:px-4 sm:py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800/80 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm hover:border-gray-200 dark:hover:border-gray-700 transition-all group cursor-pointer">
                                <div class="flex items-center gap-3 min-w-0">
                                    <!-- Component Badge & Title -->
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($item->book_type_label)
                                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-white dark:bg-gray-900 text-primary-700 dark:text-primary-300 border border-gray-200 dark:border-gray-700 shrink-0">
                                                    {{ $item->book_type_label }}
                                                </span>
                                            @endif
                                            <a href="{{ route('shop.show', $item->slug) }}" 
                                               class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors truncate">
                                                {{ $item->title }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Individual Price & Sample Badge -->
                                <div class="flex items-center gap-2.5 shrink-0 text-start sm:text-end">
                                    @if($item->sample_pdf)
                                        <a href="{{ route('shop.sample', $item->slug) }}" 
                                           onclick="event.stopPropagation()"
                                           title="{{ __('تحميل عينة PDF') }}"
                                           class="no-row-click relative z-10 inline-flex items-center gap-1.5 text-[11px] font-bold text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-950/60 px-2.5 py-1 rounded-lg border border-primary-200/60 dark:border-primary-800/60 hover:bg-primary-100 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span>{{ __('عينة PDF') }}</span>
                                        </a>
                                    @endif
                                    <span class="text-xs sm:text-sm font-extrabold text-gray-700 dark:text-gray-300">
                                        {{ $item->formatted_price }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Clean Purchase & Price Box -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 shadow-soft">
                    <div class="flex items-baseline justify-between gap-4 mb-5 pb-5 border-b border-gray-100 dark:border-gray-800">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('السعر الإجمالي للباقة كاملة') }}
                            </span>
                            <div class="flex items-baseline gap-3">
                                <span class="font-display text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                                    {{ $bundle->formatted_price }}
                                </span>
                                @if($bundle->has_discount)
                                    <span class="text-base text-gray-400 line-through font-bold">
                                        {{ $bundle->formatted_original_price }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($bundle->has_discount)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/80">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>{{ __('خصم الباقة:') }} {{ __('وفّر') }} {{ $bundle->savings_percentage }}%</span>
                            </span>
                        @endif
                    </div>

                    <!-- Add Full Bundle to Cart Form -->
                    <form action="{{ route('cart.add-bundle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">

                        <div class="flex flex-col sm:flex-row items-stretch gap-3">
                            <!-- Quantity -->
                            <div class="w-full sm:w-28 shrink-0">
                                <label for="bundle_quantity" class="sr-only">{{ __('الكمية') }}</label>
                                <div class="relative">
                                    <input type="number" id="bundle_quantity" name="quantity" value="1" min="1" max="100" 
                                           class="w-full h-12 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl text-center font-bold text-base focus:ring-primary-500 focus:border-primary-500">
                                    <span class="absolute start-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 pointer-events-none">{{ __('العدد') }}</span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" data-ripple
                                    class="flex-1 h-12 btn btn-primary rounded-xl text-base font-bold flex items-center justify-center gap-2 shadow-soft hover:shadow-lift transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>{{ __('شراء الباقة كاملة') }}</span>
                                <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full font-bold">({{ $bundle->products->count() }} {{ __('مكونات') }})</span>
                            </button>
                        </div>
                    </form>
                </div>

                @php
                    $sampleProduct = $bundle->products->firstWhere('book_type', '!=', 'audio') ?? $bundle->products->first();
                    $publisher = $sampleProduct?->publisher ?: __('مؤسسة سجى المتخصصة');
                    $language = $sampleProduct?->language ?: ($bundle->category?->name && str_contains(strtolower($bundle->category->name), 'english') ? 'English' : __('العربية'));
                @endphp

                <!-- Clean Shared Specifications Grid -->
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 sm:p-7 shadow-soft">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white mb-4">
                        {{ __('بيانات ومواصفات السلسلة') }}
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('دار النشر') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold block" title="{{ $publisher }}">
                                {{ $publisher }}
                            </strong>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('اللغة') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold">
                                {{ $language }}
                            </strong>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('نوع الباقة') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold">
                                {{ __('شامل (ورقي + صوتي)') }}
                            </strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================== SECTION 2: COMPONENT CARDS BREAKDOWN ============================== -->
        <div class="mt-16 pt-12 border-t border-gray-200/80 dark:border-gray-800">
            <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">
                        {{ __('تفاصيل محتويات الباقة وملحقاتها') }}
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        {{ __('يمكنك تصفح كل ملحق، تحميل عينته، أو شراؤه بشكل منفصل:') }}
                    </p>
                </div>
            </div>

            <!-- Grid of Components Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($bundle->products as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </div>

        <!-- ============================== SECTION 3: SIBLING LEVELS / OTHER BUNDLES ============================== -->
        @if($otherBundles->count() > 0)
            <div class="mt-16 pt-12 border-t border-gray-200/80 dark:border-gray-800">
                <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
                    <div class="min-w-0 max-w-2xl">
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white leading-snug">
                            {{ __('باقات ومستويات أخرى من') }}
                            <span class="text-primary-600 dark:text-primary-400">{{ $bundle->category->name }}</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            {{ __('تصفح باقي المستويات والسنوات التعليمية لهذه السلسلة:') }}
                        </p>
                    </div>
                    <a href="{{ route('shop.index', ['category' => $bundle->category->slug]) }}" class="arrow-link font-bold text-xs sm:text-sm shrink-0 self-start sm:self-auto inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 hover:text-primary-700">
                        <span>{{ __('عرض كافة المستويات') }}</span>
                        <svg class="w-4 h-4 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($otherBundles->take(4) as $otherBundle)
                        <x-bundle-card :bundle="$otherBundle" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
