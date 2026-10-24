@extends('layouts.store')

@section('content')
    <x-page-header 
        :title="$selectedCategory ? $selectedCategory->name : __('المناهج والمقررات الدراسية')" 
        :subtitle="__('تصفح باقات المناهج التعليمية المتكاملة والكتب والمقررات الدراسية لكافة المستويات.')" />
    
    <div class="container-x py-10">
        
        <!-- Search and Filters Container (Alpine.js) -->
        <div x-data="{ openFilters: false }" class="relative mb-8 z-30">
            <form action="{{ route('shop.index') }}" method="GET" class="w-full">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                
                <!-- حاوية الشريط الموحدة (Pill Container) -->
                <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-sm hover:shadow-md transition-all duration-300 h-14 px-2 relative z-40">
                    
                    <!-- أيقونة البحث (اليمين) -->
                    <div class="pr-4 pl-2 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- حقل الإدخال -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث عن منهج، باقة، كتاب، أو مستوى دراسي...') }}" class="flex-grow w-full bg-transparent border-0 border-transparent focus:border-transparent focus:ring-0 outline-none text-gray-700 dark:text-gray-200 text-sm sm:text-base placeholder-gray-400 dark:placeholder-gray-500 px-2 transition-colors duration-300" autocomplete="off">

                    <!-- فاصل عمودي ناعم -->
                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-2 hidden sm:block transition-colors duration-300"></div>

                    <!-- زر التصفية (اليسار) -->
                    <button type="button" @click="openFilters = !openFilters" class="relative flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all shrink-0" :class="openFilters ? 'bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:block">{{ __('تصفية') }}</span>
                        @if(request('category') || request('min_price') || request('max_price') || request('level'))
                            <span class="w-2 h-2 rounded-full bg-primary-600 shrink-0"></span>
                        @endif
                    </button>
                </div>

                <!-- Dropdown Filters Card -->
                <div x-show="openFilters" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     @click.away="openFilters = false"
                     x-cloak
                     class="absolute top-full right-0 sm:right-auto sm:left-0 mt-3 w-full sm:w-96 bg-white dark:bg-gray-900 rounded-3xl shadow-2xl border border-gray-200/80 dark:border-gray-800 z-50 overflow-hidden flex flex-col transition-all duration-300">
                    
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <span class="font-extrabold text-gray-900 dark:text-white text-base">{{ __('تصفية النتائج') }}</span>
                        @if(request('category') || request('min_price') || request('max_price') || request('level'))
                            <a href="{{ route('shop.index', request('search') ? ['search' => request('search')] : []) }}" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                {{ __('إعادة تعيين') }}
                            </a>
                        @endif
                    </div>

                    <div class="p-6 flex flex-col gap-5">
                        <!-- Categories Select -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                {{ __('القسم أو السلسلة التعليمية') }}
                            </label>
                            <div class="relative">
                                <select name="category" class="w-full bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 outline-none transition-colors duration-200 font-medium cursor-pointer">
                                    <option value="">{{ __('جميع الأقسام والسلاسل التعليمية') }}</option>
                                    @php
                                        $parents = $categories->whereNull('parent_id');
                                    @endphp
                                    @if($parents->isNotEmpty())
                                        @foreach($parents as $parentCat)
                                            @php
                                                $activeChildren = $parentCat->children->filter(fn($c) => ($c->bundles_count + $c->products_count) > 0);
                                                $hasContent = ($parentCat->bundles_count + $parentCat->products_count + $activeChildren->count()) > 0;
                                            @endphp
                                            @if($hasContent)
                                                <optgroup label="{{ $parentCat->name }}">
                                                    <option value="{{ $parentCat->slug }}" {{ request('category') == $parentCat->slug ? 'selected' : '' }}>
                                                        {{ __('جميع') }} {{ $parentCat->name }}
                                                    </option>
                                                    @foreach($activeChildren as $subCat)
                                                        <option value="{{ $subCat->slug }}" {{ request('category') == $subCat->slug ? 'selected' : '' }}>
                                                            &nbsp;&nbsp;{{ $subCat->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                        @foreach($categories->whereNotNull('parent_id')->whereNotIn('parent_id', $parents->pluck('id')) as $standalone)
                                            @if(($standalone->bundles_count + $standalone->products_count) > 0)
                                                <option value="{{ $standalone->slug }}" {{ request('category') == $standalone->slug ? 'selected' : '' }}>
                                                    {{ $standalone->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($categories as $category)
                                            @if(($category->bundles_count + $category->products_count) > 0)
                                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                {{ __('نطاق السعر') }}
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" placeholder="{{ __('الحد الأدنى') }}" class="w-full bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 outline-none transition-colors duration-200 font-medium">
                                </div>
                                <div class="relative">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="{{ __('الحد الأقصى') }}" class="w-full bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 outline-none transition-colors duration-200 font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Level -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                                {{ __('المستوى الدراسي') }}
                            </label>
                            <select name="level" class="w-full bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 outline-none transition-colors duration-200 font-medium cursor-pointer">
                                <option value="">{{ __('جميع المستويات') }}</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ __('المستوى') }} {{ $level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <button type="button" @click="openFilters = false" class="py-3.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            {{ __('إلغاء') }}
                        </button>
                        <button type="submit" class="py-3.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                            {{ __('تطبيق التصفية') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(request('search') || request('category') || request('min_price') || request('max_price') || request('level'))
            <div class="mb-6 flex items-center justify-between bg-gray-50 dark:bg-gray-800 px-4 py-3 rounded-xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <span class="text-gray-600 dark:text-gray-400 text-sm">
                    {{ __('نتائج البحث') }} 
                    @if(request('search')) {{ __('عن:') }} <strong class="text-gray-900 dark:text-white">"{{ request('search') }}"</strong> @endif
                    @if(request('category')) {{ __('في قسم:') }} <strong class="text-gray-900 dark:text-white">{{ $categories->where('slug', request('category'))->first()->name ?? request('category') }}</strong> @endif
                    @if(request('level')) {{ __('| المستوى:') }} <strong class="text-gray-900 dark:text-white">{{ request('level') }}</strong> @endif
                </span>
                <a href="{{ route('shop.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm font-medium flex items-center gap-1 transition-colors duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ __('إزالة الفلاتر') }}
                </a>
            </div>
        @endif

        <!-- ==================== CONTENT GRID ==================== -->
        @if($items->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 mt-4">
                @foreach($items as $item)
                    @if($item instanceof \App\Models\Bundle)
                        <x-bundle-card :bundle="$item" />
                    @else
                        <x-product-card :product="$item" />
                    @endif
                @endforeach
            </div>
            <div class="mt-12 mb-10 flex justify-center">
                {{ $items->links() }}
            </div>
        @else
            <div class="py-20 text-center bg-white dark:bg-gray-800/50 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700 my-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('لم يتم العثور على مناهج أو كتب تطابق البحث') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto mb-6">{{ __('يرجى تجربة كلمات بحث أخرى أو تغيير معايير التصفية.') }}</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm inline-flex items-center gap-2">
                    <span>{{ __('عرض جميع المناهج والكتب') }}</span>
                </a>
            </div>
        @endif
    </div>
@endsection
