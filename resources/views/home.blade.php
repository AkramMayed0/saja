@extends('layouts.store')

@section('content')
@php
    $heroBooks = $newProducts->merge($trendingProducts)->filter(fn ($p) => $p->image)->unique('id')->take(3)->values();
@endphp

<!-- ============================== HERO ============================== -->
<section class="relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 -z-10" aria-hidden="true">
        <div class="absolute inset-0 bg-grid mask-fade-b"></div>
        <div class="absolute -top-24 start-[10%] h-[28rem] w-[28rem] rounded-full bg-primary-400/25 blur-3xl animate-blob dark:bg-primary-600/20"></div>
        <div class="absolute top-40 end-[5%] h-[24rem] w-[24rem] rounded-full bg-leaf-400/20 blur-3xl animate-blob animation-delay-4000 dark:bg-leaf-600/15"></div>
    </div>

    <div class="container-x grid items-center gap-14 pb-20 pt-12 lg:grid-cols-2 lg:gap-8 lg:pb-28 lg:pt-20">
        <!-- Copy -->
        <div class="text-center lg:text-start">
            <h1 class="font-sans text-4xl font-bold leading-[1.25] tracking-tight sm:text-5xl lg:text-5xl xl:text-6xl animate-fade-up text-primary-950 dark:text-white pb-2">
                {{ __('سجى المتخصصة') }}
            </h1>

            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-500 dark:text-gray-400 lg:mx-0 animate-fade-up" style="animation-delay:.16s">
                {{ __('مؤسسة سجى المتخصصة – قطاع النشر والخدمات التعليمية، نقدم سلاسل تعليمية مبتكرة تجمع بين الجودة، الحداثة، الهوية، واحتياجات المتعلم.') }}
            </p>

            <div class="mt-10 flex flex-col items-center gap-3 sm:flex-row sm:justify-center lg:justify-start animate-fade-up" style="animation-delay:.24s">
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg w-full sm:w-auto group">
                    {{ __('تصفح المناهج الآن') }}
                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1 ltr:-scale-x-100 ltr:group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </a>
                <a href="#wholesale" class="btn btn-outline btn-lg w-full sm:w-auto">
                    {{ __('طلبات الجملة') }}
                </a>
            </div>

            <!-- Real stats -->
            <dl class="mx-auto mt-12 grid max-w-md grid-cols-3 divide-x divide-gray-200 rtl:divide-x-reverse dark:divide-gray-800 lg:mx-0 animate-fade-up" style="animation-delay:.32s">
                <div class="flex flex-col px-2 text-center lg:text-start lg:ps-0">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('عنوان متوفر') }}</dt>
                    <dd class="order-first font-display text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl"><span data-count="{{ $productsCount }}">{{ $productsCount }}</span>+</dd>
                </div>
                <div class="flex flex-col px-2 text-center lg:text-start lg:ps-6">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('قسم متخصص') }}</dt>
                    <dd class="order-first font-display text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl"><span data-count="{{ $categories->count() }}">{{ $categories->count() }}</span></dd>
                </div>
                <div class="flex flex-col px-2 text-center lg:text-start lg:ps-6">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('جودة مضمونة') }}</dt>
                    <dd class="order-first font-display text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl"><span data-count="100">100</span>%</dd>
                </div>
            </dl>
        </div>

        <!-- Visual: floating book stack with mouse parallax -->
        <div data-parallax-scene class="relative mx-auto hidden h-[460px] w-full max-w-lg lg:block" aria-hidden="true">
            @if($heroBooks->count() >= 3)
                <!-- Back-left book -->
                <div data-depth="15" class="absolute start-[12%] top-[18%] w-[32%] will-change-transform">
                    <div class="-rotate-6 -translate-y-2"><div class="book-cover aspect-[3/4] animate-float-slow shadow-xl shadow-gray-200/50 dark:shadow-black/50 rounded-lg overflow-hidden ring-1 ring-black/5"><img src="{{ asset('storage/'.$heroBooks[1]->image) }}" alt="" class="h-full w-full object-cover"></div></div>
                </div>
                <!-- Back-right book -->
                <div data-depth="20" class="absolute end-[12%] top-[22%] w-[32%] will-change-transform">
                    <div class="rotate-6 translate-y-2"><div class="book-cover aspect-[3/4] animate-float-slow animation-delay-2000 shadow-xl shadow-gray-200/50 dark:shadow-black/50 rounded-lg overflow-hidden ring-1 ring-black/5"><img src="{{ asset('storage/'.$heroBooks[2]->image) }}" alt="" class="h-full w-full object-cover"></div></div>
                </div>
                <!-- Front book -->
                <div data-depth="30" class="absolute start-1/2 top-[10%] w-[40%] -translate-x-1/2 rtl:translate-x-1/2 will-change-transform z-10">
                    <div class="book-cover aspect-[3/4] animate-float shadow-2xl shadow-primary-900/20 dark:shadow-black/60 rounded-xl overflow-hidden ring-1 ring-black/10"><img src="{{ asset('storage/'.$heroBooks[0]->image) }}" alt="" class="h-full w-full object-cover"></div>
                </div>
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="w-1/2 animate-float">
                </div>
            @endif

            <!-- Floating badges -->
            <div data-depth="40" class="absolute bottom-[4%] start-[2%] will-change-transform z-20">
                <div class="glass flex items-center gap-2.5 rounded-2xl p-2 pr-4 shadow-lift animate-float animation-delay-2000 border border-white/40 dark:border-white/10">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-leaf-400 to-leaf-600 text-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                    </span>
                    <div class="text-start">
                        <p class="text-xs font-bold text-gray-900 dark:text-white">{{ __('شحن سريع وآمن') }}</p>
                    </div>
                </div>
            </div>
            <div data-depth="45" class="absolute end-[0%] top-[0%] will-change-transform z-20">
                <div class="glass flex items-center gap-2.5 rounded-2xl p-2 pl-4 shadow-lift animate-float-slow border border-white/40 dark:border-white/10">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                    </span>
                    <div class="text-start">
                        <p class="text-xs font-bold text-gray-900 dark:text-white">{{ __('مناهج معتمدة') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================== FEATURED BOOKS SLIDER ============================== -->
<section class="w-full overflow-hidden py-10">
    <div class="relative w-full overflow-hidden flex" dir="ltr">
        <div class="flex w-max animate-marquee gap-5 hover:[animation-play-state:paused]" style="animation-duration: 90s;">
            @php
                $books = ($sliderProducts ?? \App\Models\Product::where('title', 'not like', '%Cards%')
                    ->whereNotNull('image')
                    ->orderBy('category_id', 'asc')
                    ->orderByRaw("CASE book_type 
                        WHEN 'student' THEN 1 
                        WHEN 'combined' THEN 2 
                        WHEN 'workbook' THEN 3 
                        WHEN 'teacher' THEN 4 
                        ELSE 5 END ASC")
                    ->orderBy('level', 'asc')
                    ->get());
                $tickerProducts = $books->concat($books);
            @endphp
            @foreach($tickerProducts as $product)
                <a href="{{ route('shop.show', $product->slug) }}" class="shrink-0 w-32 sm:w-40 block group" aria-label="{{ $product->title }}">
                    <div class="book-cover aspect-[3/4] w-full shadow-soft ring-1 ring-black/5 dark:ring-white/10 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800 transition-transform duration-300 group-hover:scale-105">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" loading="lazy" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================== CATEGORIES ============================== -->
<section class="container-x py-24">
    <div data-reveal class="mb-12 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <span class="eyebrow">{{ __('الأقسام') }}</span>
            <h2 class="section-title mt-4">{{ __('تصفح الأقسام') }}</h2>
            <p class="section-sub">{{ __('اختر القسم المناسب وابدأ رحلتك مع المعرفة.') }}</p>
        </div>
        <a href="{{ route('shop.index') }}" class="arrow-link">
            {{ __('عرض الكل') }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        </a>
    </div>

    <div data-reveal-group class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
        @forelse($categories as $category)
            <a data-reveal href="{{ route('shop.index', ['category' => $category->slug]) }}" class="card card-hover group relative flex flex-row items-center gap-4 overflow-hidden p-4 sm:flex-col sm:items-stretch sm:gap-5 sm:p-6">
                <div class="absolute -end-10 -top-10 h-32 w-32 rounded-full bg-primary-500/0 blur-2xl transition-all duration-500 group-hover:bg-primary-500/20" aria-hidden="true"></div>
                @if($category->image)
                    <img src="{{ asset('storage/'.$category->image) }}" alt="" class="h-12 w-12 shrink-0 object-contain sm:h-14 sm:w-14 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6">
                @else
                    <span class="flex h-12 w-12 shrink-0 items-center sm:h-14 sm:w-14 justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-leaf-500 font-display text-xl font-black text-white shadow-glow transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6">{{ mb_substr($category->name, 0, 1) }}</span>
                @endif
                <div class="grid min-w-0 flex-1 grid-cols-[1fr_auto] items-center gap-x-2 sm:grid-rows-[1fr_auto] sm:gap-y-3">
                    <h3 class="col-start-1 font-display text-base font-bold leading-snug text-gray-900 dark:text-white sm:col-span-2 sm:self-center sm:text-lg">{{ $category->name }}</h3>
                    <p class="col-start-1 mt-1 text-xs font-medium text-gray-500 dark:text-gray-400 sm:mt-0 sm:self-center">{{ $category->products_count }} {{ __('منتج') }}</p>
                    <span class="col-start-2 row-span-2 row-start-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-400 transition-all duration-300 group-hover:border-primary-600 group-hover:bg-primary-600 group-hover:text-white dark:border-gray-700 sm:row-span-1 sm:row-start-2">
                        <svg class="h-4 w-4 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    </span>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-gray-500 dark:text-gray-400">{{ __('لا توجد أقسام متاحة حالياً.') }}</p>
        @endforelse

        @if($categories->count())
            <a data-reveal href="{{ route('shop.index') }}" class="group relative flex flex-row items-center gap-4 overflow-hidden rounded-3xl bg-gray-900 p-4 text-white sm:flex-col sm:items-stretch sm:justify-between sm:gap-5 sm:p-6 sm:min-h-[11rem] shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-lift dark:bg-primary-900">
                <div class="absolute inset-0 bg-grid opacity-20" aria-hidden="true"></div>
                <span class="relative flex h-12 w-12 shrink-0 items-center sm:h-14 sm:w-14 justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                </span>
                <div class="relative grid min-w-0 flex-1 grid-cols-[1fr_auto] items-center gap-x-2 sm:grid-rows-[1fr_auto] sm:gap-y-3">
                    <h3 class="col-start-1 font-display text-base font-bold text-white sm:col-span-2 sm:self-center sm:text-lg">{{ __('جميع المنتجات') }}</h3>
                    <p class="col-start-1 mt-1 text-xs text-white/60 sm:mt-0 sm:self-center">{{ $productsCount }} {{ __('منتج') }}</p>
                    <span class="col-start-2 row-span-2 row-start-1 flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-900 transition-transform duration-300 group-hover:-translate-x-1 ltr:group-hover:translate-x-1 sm:row-span-1 sm:row-start-2">
                        <svg class="h-4 w-4 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    </span>
                </div>
            </a>
        @endif
    </div>
</section>

<!-- ============================== TRENDING ============================== -->
<section class="container-x pb-24">
    <div data-reveal class="mb-12 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <span class="eyebrow">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                {{ __('الأكثر طلباً') }}
            </span>
            <h2 class="section-title mt-4">{{ __('المنتجات الرائجة') }}</h2>
        </div>
        <a href="{{ route('shop.index') }}" class="arrow-link">
            {{ __('عرض الكل') }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        </a>
    </div>

    <div data-reveal-group class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($trendingProducts as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>

<!-- ============================== WHOLESALE CTA ============================== -->
<section id="wholesale" class="container-x">
    <div data-reveal class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary-700 via-primary-800 to-primary-950 px-6 py-16 shadow-lift sm:px-12 lg:px-16">
        <div class="absolute inset-0 -z-10 bg-grid opacity-[0.15] [filter:invert(1)]" aria-hidden="true"></div>
        <div class="absolute -end-20 -top-20 -z-10 h-80 w-80 rounded-full bg-leaf-400/30 blur-3xl animate-blob" aria-hidden="true"></div>
        <div class="absolute -bottom-24 start-10 -z-10 h-72 w-72 rounded-full bg-primary-300/20 blur-3xl animate-blob animation-delay-2000" aria-hidden="true"></div>

        <div class="grid items-center gap-10 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-primary-100 ring-1 ring-white/20">{{ __('للمدارس والمؤسسات') }}</span>
                <h2 class="mt-5 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl">{{ __('طلبات الجملة') }}</h2>
                <p class="mt-4 max-w-xl text-lg leading-8 text-primary-100/80">{{ __('وفّر لمدرستك جميع المناهج بأسعار خاصة وتوصيل منظم.') }}</p>
                <ul class="mt-8 grid gap-3 text-sm font-medium text-white/90 sm:grid-cols-2">
                    @foreach([__('أسعار خاصة للكميات'), __('توصيل مجدول للمدارس'), __('فواتير رسمية'), __('مدير حساب مخصص')] as $perk)
                        <li class="flex items-center gap-2.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-leaf-400/90 text-primary-950">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                            {{ $perk }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-2 lg:flex-col lg:items-end">
                <a href="#contact" class="btn btn-lg w-full bg-white text-primary-800 shadow-lift hover:-translate-y-0.5 hover:bg-primary-50 sm:w-auto lg:w-64" data-ripple>{{ __('تواصل معنا') }}</a>
                <a href="{{ route('shop.index') }}" class="btn btn-lg w-full text-white ring-1 ring-white/30 hover:bg-white/10 sm:w-auto lg:w-64">{{ __('تصفح المناهج') }}</a>
            </div>
        </div>
    </div>
</section>

<!-- ============================== NEW ARRIVALS ============================== -->
<section class="container-x pt-24">
    <div data-reveal class="mb-12 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <span class="eyebrow !border-leaf-200 !bg-leaf-50 !text-leaf-700 dark:!border-leaf-900 dark:!bg-leaf-950/50 dark:!text-leaf-300">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1l2.39 6.26L19 8l-5 4.42L15.18 19 10 15.6 4.82 19 6 12.42 1 8l6.61-.74z"/></svg>
                {{ __('جديد') }}
            </span>
            <h2 class="section-title mt-4">{{ __('وصل حديثاً') }}</h2>
        </div>
        <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="arrow-link">
            {{ __('عرض الكل') }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        </a>
    </div>

    <div data-reveal-group class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($newProducts as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>
@endsection
