<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title') @yield('title') — @endif {{ __('مؤسسة سجى المتخصصة') }}</title>
    <meta name="description" content="{{ __('مؤسسة سجى المتخصصة. قطاع النشر والخدمات التعليمية. نقدم أفضل الكتب وأرقى الخدمات لعملائنا.') }}">
    <meta name="theme-color" content="#00909b">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Theme + JS flag (runs before paint to avoid flashes) -->
    <script>
        document.documentElement.classList.add('js');
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('head')
</head>
<body class="font-sans antialiased flex flex-col min-h-screen overflow-x-hidden transition-colors duration-300">

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:start-3 focus:z-[100] btn btn-primary">{{ __('تخطي إلى المحتوى') }}</a>

    @php
        $navLinks = [
            ['route' => 'home', 'match' => 'home', 'label' => __('الرئيسية')],
            ['route' => 'shop.index', 'match' => 'shop.*', 'label' => __('المناهج')],
            ['route' => 'courses.index', 'match' => 'courses.*', 'label' => __('تدريب المعلمين')],
            ['route' => 'pages.about', 'match' => 'pages.about', 'label' => __('من نحن')],
        ];
    @endphp

    <!-- Navbar -->
    <header x-data="{ mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false"
            data-site-header
            class="site-header fixed w-full top-0 z-50 border-b border-transparent bg-white/60 dark:bg-gray-950/60 backdrop-blur-xl">
        <!-- Scroll progress -->
        <div class="absolute inset-x-0 bottom-0 h-0.5 overflow-hidden" aria-hidden="true">
            <div data-scroll-progress class="h-full w-full origin-left rtl:origin-right scale-x-0 bg-gradient-to-l from-primary-500 to-leaf-500"></div>
        </div>

        <div class="container-x">
            <div class="header-row flex h-[76px] items-center justify-between gap-3">
                <div class="flex items-center gap-6 lg:gap-10 shrink-0">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center shrink-0 group" aria-label="{{ __('سجى') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-11 sm:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105 dark:brightness-125">
                    </a>

                    <!-- Links -->
                    <nav class="hidden md:flex items-center gap-1" aria-label="{{ __('القائمة الرئيسية') }}">
                        @foreach($navLinks as $link)
                            <a href="{{ route($link['route']) }}" class="nav-link {{ request()->routeIs($link['match']) ? 'is-active' : '' }}" @if(request()->routeIs($link['match'])) aria-current="page" @endif>{{ $link['label'] }}</a>
                        @endforeach
                    </nav>
                </div>

                <!-- Global Search -->
                <div class="hidden xl:flex flex-1 max-w-sm">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative w-full group" role="search">
                        <svg class="pointer-events-none absolute top-1/2 -translate-y-1/2 start-4 w-4 h-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="search" name="search" value="{{ request('search') }}" aria-label="{{ __('بحث') }}" placeholder="{{ __('ابحث عن كتاب، مؤلف، أو موضوع...') }}" class="w-full rounded-full border border-gray-200 bg-gray-100/70 py-2.5 ps-11 pe-4 text-sm text-gray-800 placeholder-gray-400 transition-all duration-300 focus:border-primary-400 focus:bg-white focus:ring-4 focus:ring-primary-500/15 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500">
                    </form>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <!-- Dark Mode Toggle -->
                    <button type="button" data-theme-toggle class="btn-icon" aria-label="{{ __('تبديل الوضع الليلي') }}">
                        <svg class="w-5 h-5 dark:hidden transition-transform duration-500 hover:-rotate-12" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        <svg class="w-5 h-5 hidden dark:block transition-transform duration-500 hover:rotate-90" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </button>

                    <!-- Language Toggle -->
                    <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="btn-icon text-xs font-bold" aria-label="{{ __('تغيير اللغة') }}">
                        {{ app()->getLocale() == 'ar' ? 'EN' : 'ع' }}
                    </a>


                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="btn-icon relative {{ request()->routeIs('cart.*') ? 'text-primary-600 dark:text-primary-400' : '' }}" aria-label="{{ __('السلة') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                    </a>

                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-800 mx-2 hidden md:block"></div>

                    <!-- Desktop Auth -->
                    <div class="hidden md:flex items-center gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('دخول') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('حساب جديد') }}</a>
                        @endguest

                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm border border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-100 dark:border-primary-900 dark:bg-primary-950/60 dark:text-primary-300">{{ __('لوحة التحكم') }}</a>
                            @endif
                            @if(auth()->user()->is_driver)
                                <a href="{{ route('driver.orders') }}" class="btn btn-sm border border-leaf-200 bg-leaf-50 text-leaf-700 hover:bg-leaf-100 dark:border-leaf-900 dark:bg-leaf-950/60 dark:text-leaf-300">{{ __('لوحة المندوب') }}</a>
                            @endif

                            <!-- Account dropdown -->
                            <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                                <button type="button" @click="open = !open" :aria-expanded="open" class="flex items-center gap-2 rounded-full border border-gray-200 bg-white py-1 ps-1 pe-3 text-sm font-semibold text-gray-700 transition-all hover:border-gray-300 hover:shadow-soft dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-leaf-500 text-xs font-bold text-white">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                                    <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                                    <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute end-0 mt-2 w-60 origin-top card p-2 shadow-lift">
                                    <div class="px-3 py-2.5 mb-1 border-b border-gray-100 dark:border-gray-800">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                        {{ __('حسابي') }}
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40">
                                            <svg class="h-4 w-4 rtl:-scale-x-100" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                            {{ __('خروج') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile Hamburger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="btn-icon md:hidden" :aria-expanded="mobileMenuOpen" aria-label="{{ __('القائمة') }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h10.5"/>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden absolute inset-x-0 top-full border-b border-gray-200 bg-white/95 backdrop-blur-xl shadow-lift dark:border-gray-800 dark:bg-gray-950/95">
            <div class="container-x py-4 space-y-1">
                <form action="{{ route('shop.index') }}" method="GET" class="relative mb-3" role="search">
                    <svg class="pointer-events-none absolute top-1/2 -translate-y-1/2 start-4 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث عن كتاب، مؤلف، أو موضوع...') }}" class="form-input ps-11 rounded-full">
                </form>
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="flex items-center justify-between rounded-2xl px-4 py-3 font-semibold {{ request()->routeIs($link['match']) ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900' }}">
                        {{ $link['label'] }}
                        <svg class="h-4 w-4 opacity-40 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endforeach


                <div class="my-3 border-t border-gray-100 dark:border-gray-800"></div>

                @guest
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline">{{ __('دخول') }}</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('حساب جديد') }}</a>
                    </div>
                @endguest

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 font-semibold text-primary-700 bg-primary-50 dark:bg-primary-950/50 dark:text-primary-300">{{ __('لوحة التحكم') }}</a>
                    @endif
                    @if(auth()->user()->is_driver)
                        <a href="{{ route('driver.orders') }}" class="block rounded-2xl px-4 py-3 font-semibold text-leaf-700 bg-leaf-50 dark:bg-leaf-950/50 dark:text-leaf-300">{{ __('لوحة المندوب') }}</a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="block rounded-2xl px-4 py-3 font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900">{{ __('حسابي') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start rounded-2xl px-4 py-3 font-semibold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40">{{ __('خروج') }}</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Toast notification -->
    @if(session()->has('success'))
        <div x-data="{ show: false }" x-init="$nextTick(() => show = true); setTimeout(() => show = false, 5000)"
             x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-24 inset-x-0 z-[60] flex justify-center px-4 pointer-events-none" role="status" aria-live="polite">
            <div class="pointer-events-auto relative flex w-full max-w-md items-center gap-3 overflow-hidden card p-4 pe-3 shadow-lift">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-leaf-100 text-leaf-600 dark:bg-leaf-950 dark:text-leaf-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </span>
                <p class="flex-1 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ session('success') }}</p>
                <button type="button" @click="show = false" class="btn-icon h-8 w-8" aria-label="{{ __('إغلاق') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <span class="absolute bottom-0 start-0 h-0.5 w-full origin-left rtl:origin-right bg-leaf-500" style="animation: toast-timer 5s linear forwards"></span>
            </div>
        </div>
        <style>@keyframes toast-timer { from { transform: scaleX(1) } to { transform: scaleX(0) } }</style>
    @endif

    @if(session('error'))
        <div x-data="{ show: false }" x-init="$nextTick(() => show = true); setTimeout(() => show = false, 6000)"
             x-show="show" x-cloak
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-24 inset-x-0 z-[60] flex justify-center px-4 pointer-events-none" role="alert" aria-live="assertive">
            <div class="pointer-events-auto relative flex w-full max-w-md items-center gap-3 overflow-hidden card p-4 pe-3 shadow-lift">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950 dark:text-red-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <p class="flex-1 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ session('error') }}</p>
                <button type="button" @click="show = false" class="btn-icon h-8 w-8" aria-label="{{ __('إغلاق') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main id="main" class="flex-grow pt-[76px]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="contact" class="relative mt-20 overflow-hidden border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-950">
        <div class="pointer-events-none absolute -top-40 start-1/2 h-80 w-[60rem] -translate-x-1/2 rounded-full bg-primary-500/10 blur-3xl dark:bg-primary-500/10" aria-hidden="true"></div>

        <div class="container-x relative">
            <!-- Newsletter / CTA strip -->
            <div class="-mt-px grid gap-6 border-b border-gray-100 py-12 dark:border-gray-800 md:grid-cols-2 md:items-center">
                <div>
                    <h2 class="text-2xl font-extrabold">{{ __('هل لديك سؤال؟ نحن هنا لمساعدتك') }}</h2>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('تصفح الأسئلة الشائعة أو ابدأ التسوق مباشرة بكل سهولة وأمان.') }}</p>
                </div>
                <div class="flex flex-wrap gap-3 md:justify-self-end">
                    <a href="{{ route('pages.faq') }}" class="btn btn-outline">{{ __('الأسئلة الشائعة') }}</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">{{ __('تصفح المناهج الآن') }}</a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 py-14 md:grid-cols-12">
                <!-- Brand -->
                <div class="col-span-2 md:col-span-4">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-16 w-auto object-contain dark:brightness-125">
                    <p class="mt-5 max-w-xs text-sm leading-7 text-gray-500 dark:text-gray-400">{{ __('مؤسسة سجى المتخصصة. قطاع النشر والخدمات التعليمية. نقدم أفضل الكتب وأرقى الخدمات لعملائنا.') }}</p>
                    <div class="mt-6 flex gap-2">
                        @php
                            $socials = [
                                ['label' => __('فيسبوك'), 'url' => '#', 'path' => 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z'],
                                ['label' => __('واتساب'), 'url' => 'https://wa.me/967781818809', 'path' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
                                ['label' => __('انستغرام'), 'url' => '#', 'path' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                                ['label' => __('تيليجرام'), 'url' => '#', 'path' => 'M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z'],
                            ];
                        @endphp
                        @foreach($socials as $s)
                            <a href="{{ $s['url'] }}" @if($s['url'] !== '#') target="_blank" rel="noopener noreferrer" @endif aria-label="{{ $s['label'] }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 transition-all duration-300 hover:-translate-y-1 hover:border-primary-500 hover:bg-primary-600 hover:text-white dark:border-gray-800 dark:text-gray-400">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $s['path'] }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Explore -->
                <div class="md:col-span-2 md:col-start-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">{{ __('إستكشاف') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('shop.index') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('المناهج') }}</a></li>
                        <li><a href="{{ route('courses.index') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('تدريب المعلمين') }}</a></li>
                        <li><a href="{{ route('cart.index') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('السلة') }}</a></li>
                    </ul>
                </div>

                <!-- About -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">{{ __('حول المؤسسة') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('pages.about') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('من نحن') }}</a></li>
                        <li><a href="{{ route('pages.vision') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('رؤيتنا وأهدافنا') }}</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('الأسئلة الشائعة') }}</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">{{ __('السياسات') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('pages.privacy') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('سياسة الخصوصية') }}</a></li>
                        <li><a href="{{ route('pages.shipping') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('الشحن والإرجاع') }}</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="link-underline text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ __('الشروط والأحكام') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 border-t border-gray-100 py-8 text-sm text-gray-400 dark:border-gray-800 dark:text-gray-500 md:flex-row">
                <p>&copy; {{ date('Y') }} {{ __('مؤسسة سجى المتخصصة. جميع الحقوق محفوظة.') }}</p>
            </div>
        </div>
    </footer>

    <!-- Back to top (ring shows scroll progress) -->
    <button type="button" data-back-to-top aria-label="{{ __('العودة للأعلى') }}"
            class="group fixed bottom-6 end-6 z-40 flex h-12 w-12 translate-y-4 items-center justify-center rounded-full bg-white text-gray-700 opacity-0 shadow-lift pointer-events-none transition-all duration-300 hover:-translate-y-1 dark:bg-gray-900 dark:text-gray-200 [&.is-shown]:pointer-events-auto [&.is-shown]:translate-y-0 [&.is-shown]:opacity-100"
            style="background-image: conic-gradient(#14a3ab calc(var(--p, 0) * 1%), transparent 0);">
        <span class="absolute inset-[3px] rounded-full bg-white dark:bg-gray-900"></span>
        <svg class="relative h-5 w-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
    </button>

    @stack('scripts')
</body>
</html>
