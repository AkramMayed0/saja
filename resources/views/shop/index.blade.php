@extends('layouts.store')

@section('content')
    <x-page-header :title="__('باقات ومستويات المناهج')" :subtitle="__('تصفح باقات المناهج التعليمية المتكاملة مع كافة الملحقات والكتب والصوتيات.')" />
    
    <div class="container-x py-10">
        
        <!-- Search and Filters Container (Alpine.js) -->
        <div x-data="{ openFilters: false }" class="relative mb-10 z-30">
            <form action="{{ route('shop.index') }}" method="GET" class="w-full">
                
                <!-- حاوية الشريط الموحدة (Pill Container) -->
                <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full shadow-sm hover:shadow-md transition-all duration-300 h-14 px-2 relative z-40">
                    
                    <!-- أيقونة البحث (اليمين) -->
                    <div class="pr-4 pl-2 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- حقل الإدخال -->
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث عن باقة، منهج تعليمي، أو مستوى...') }}" class="flex-grow w-full bg-transparent border-0 border-transparent focus:border-transparent focus:ring-0 outline-none text-gray-700 dark:text-gray-200 text-sm sm:text-base placeholder-gray-400 dark:placeholder-gray-500 px-2 transition-colors duration-300" autocomplete="off">

                    <!-- فاصل عمودي ناعم -->
                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-2 hidden sm:block transition-colors duration-300"></div>

                    <!-- زر التصفية (اليسار) -->
                    <button type="button" @click="openFilters = !openFilters" class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors shrink-0" :class="openFilters ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="hidden sm:block">{{ __('تصفية') }}</span>
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
                     class="absolute top-full right-0 sm:right-auto sm:left-0 mt-3 w-full sm:w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden flex flex-col transition-colors duration-300">
                    
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-100 text-lg transition-colors duration-300">{{ __('تصفية الباقات') }}</div>

                    <div class="p-6 flex flex-col gap-6">
                        <!-- Categories Select -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2 transition-colors duration-300">{{ __('السلسلة التعليمية') }}</label>
                            <select name="category" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 outline-none transition-colors duration-300">
                                <option value="">{{ __('جميع السلاسل التعليمية') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->bundles_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2 transition-colors duration-300">{{ __('نطاق السعر') }}</label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" placeholder="{{ __('الأدنى') }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 outline-none transition-colors duration-300">
                                <span class="text-gray-400 dark:text-gray-500 font-medium">-</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="{{ __('الأقصى') }}" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 outline-none transition-colors duration-300">
                            </div>
                        </div>

                        <!-- Level -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2 transition-colors duration-300">{{ __('المستوى الدراسي') }}</label>
                            <select name="level" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 outline-none transition-colors duration-300">
                                <option value="">{{ __('جميع المستويات') }}</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ __('المستوى') }} {{ $level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 transition-colors duration-300">
                        <button type="button" @click="openFilters = false" class="py-4 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">{{ __('إلغاء') }}</button>
                        <button type="submit" class="py-4 text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:bg-primary-50 dark:hover:bg-gray-700 transition-colors duration-300 border-r border-gray-200 dark:border-gray-700">{{ __('تطبيق') }}</button>
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

        @if($bundles->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
                @foreach($bundles as $bundle)
                    <x-bundle-card :bundle="$bundle" />
                @endforeach
            </div>
            <div class="mt-12 mb-10 flex justify-center">
                {{ $bundles->links() }}
            </div>
        @else
            <div class="py-20 text-center bg-white dark:bg-gray-800/50 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700 my-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('لم يتم العثور على باقات تطابق البحث') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto mb-6">{{ __('يرجى تجربة كلمات بحث أخرى أو تغيير معايير التصفية.') }}</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm inline-flex items-center gap-2">
                    <span>{{ __('عرض جميع الباقات') }}</span>
                </a>
            </div>
        @endif
    </div>
@endsection
