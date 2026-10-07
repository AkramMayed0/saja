@extends('layouts.store')

@section('content')
<div>
    <!-- Hero Section -->
    <section class="relative min-h-[calc(100vh-80px)] flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Saja Library" class="w-full h-full object-cover object-center" />
            <!-- Overlay with primary turquoise tint -->
            <div class="absolute inset-0 bg-gray-900/50"></div>
            <div class="absolute inset-0 bg-gradient-to-b sm:bg-gradient-to-r from-gray-900/95 via-gray-900/90 to-primary-900/80"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-start w-full flex flex-col items-center sm:items-start pt-8 pb-16 md:pt-10 md:pb-20">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold px-4 py-2 md:px-5 md:py-2.5 rounded-full mb-6 shadow-sm text-sm md:text-base">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                </span>
                {{ __('أفضل منصة للكتب العربية والمناهج') }}
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold mb-4 md:mb-6 text-white tracking-tight leading-normal max-w-4xl text-center sm:text-start drop-shadow-xl">
                {{ __('سجى للنشر والخدمات التعليمية') }}
                <span class="block pb-4 pt-1 text-transparent bg-clip-text bg-gradient-to-l rtl:from-primary-400 rtl:to-primary-200 ltr:from-primary-200 ltr:to-primary-400 mt-2 md:mt-3">{{ __('بوابتك الأولى لأفضل الكتب') }}</span>
            </h1>
            
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-8 md:mb-12 font-medium text-white opacity-90 max-w-3xl text-center sm:text-start leading-relaxed drop-shadow-lg">
                {{ __('نوفر أحدث الإصدارات، الروايات، والمناهج الدراسية للأفراد والمؤسسات والمدارس. تمتع بتجربة تسوق فريدة مع خدمة الدفع عند الاستلام!') }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 md:gap-4 w-full sm:w-auto">
                <a href="{{ route('shop.index') }}" class="w-full sm:w-auto text-center bg-primary-600 text-white font-bold py-3 px-6 md:py-4 md:px-8 rounded-full shadow-lg hover:bg-primary-500 hover:shadow-primary-600/30 transition-all duration-300 transform hover:-translate-y-1 text-base md:text-lg border border-primary-500">
                    {{ __('تصفح المتجر الآن') }}
                </a>
                <a href="#" class="w-full sm:w-auto text-center bg-white/10 backdrop-blur-md text-white font-bold py-3 px-6 md:py-4 md:px-8 rounded-full shadow-lg hover:bg-white/20 border border-white/30 transition-all duration-300 transform hover:-translate-y-1 text-base md:text-lg">
                    {{ __('طلبات الجملة للمدارس') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('تصفح الأقسام') }}</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group flex flex-col items-center justify-center gap-4 bg-white dark:bg-gray-800 rounded-[1.5rem] py-6 px-8 shadow-sm hover:shadow-md hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 dark:hover:border-primary-500 transition-all duration-300 border border-gray-200 dark:border-gray-700 text-center min-h-[120px]">
                    @if($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" class="h-16 w-16 object-contain drop-shadow-sm group-hover:scale-110 transition-transform duration-300">
                    @endif
                    <h3 class="text-base md:text-xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300 leading-relaxed">{{ $category->name }}</h3>
                </a>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 col-span-full">{{ __('لا توجد أقسام متاحة حالياً.') }}</p>
            @endforelse
        </div>
    </section>

    <!-- Trending Products Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                </span>
                {{ __('المنتجات الرائجة') }}
            </h2>
            <a href="{{ route('shop.index') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-800 dark:hover:text-primary-300 text-sm flex items-center gap-1">{{ __('عرض الكل') }} <span aria-hidden="true">&larr;</span></a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($trendingProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-20 bg-gray-50 dark:bg-gray-800/50 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 relative overflow-hidden transition-colors duration-300">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="bg-green-50 dark:bg-green-900/30 text-green-500 dark:text-green-400 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </span>
                    {{ __('وصل حديثاً') }}
                </h2>
                <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-800 dark:hover:text-primary-300 text-sm flex items-center gap-1">{{ __('عرض الكل') }} <span aria-hidden="true">&larr;</span></a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
