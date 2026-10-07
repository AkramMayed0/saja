<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('مؤسسة سجى المتخصصة') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    

    <!-- Dark Mode Init Script -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 font-sans antialiased flex flex-col min-h-screen selection:bg-primary-100 selection:text-primary-900 dark:selection:bg-primary-900 dark:selection:text-primary-100 transition-colors duration-300 overflow-x-hidden">

    <!-- Navbar -->
    <header x-data="{ mobileMenuOpen: false }" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 gap-2 sm:gap-4">
                <div class="flex items-center gap-4 lg:gap-8 shrink-0">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-12 sm:h-16 w-auto object-contain drop-shadow-sm transition-transform hover:scale-105 duration-300">
                    </a>
                    
                    <!-- Links -->
                    <nav class="hidden md:flex items-center gap-2">
                        <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 font-bold text-sm px-4 py-2 rounded-full transition-all duration-300">{{ __('الرئيسية') }}</a>
                        <a href="{{ route('shop.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 font-bold text-sm px-4 py-2 rounded-full transition-all duration-300">{{ __('المتجر') }}</a>
                        <a href="{{ route('courses.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 font-bold text-sm px-4 py-2 rounded-full transition-all duration-300">{{ __('تدريب المعلمين') }}</a>
                    </nav>
                </div>

                <!-- Global Search -->
                <div class="hidden xl:flex flex-1 max-w-sm justify-center mx-2">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative w-full">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ابحث عن كتاب، مؤلف، أو موضوع...') }}" class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm rounded-full py-2.5 px-5 ltr:pr-12 rtl:pl-12 focus:ring-primary-500 focus:border-primary-500 dark:text-gray-200 dark:placeholder-gray-400 transition-colors duration-300">
                        <button type="submit" class="absolute ltr:right-4 rtl:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>
                
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <!-- Dark Mode Toggle Button -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none rounded-full text-sm p-2 transition-colors">
                        <!-- Dark icon (moon) -->
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <!-- Light icon (sun) -->
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>
                    
                    <!-- Language Toggle Button -->
                    <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" class="text-gray-500 dark:text-gray-400 font-bold hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none rounded-full text-sm px-2 py-2 transition-colors">
                        {{ app()->getLocale() == 'ar' ? 'EN' : 'AR' }}
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 font-bold text-sm px-2 sm:px-4 py-2 rounded-full transition-all duration-300 flex items-center gap-1.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="hidden sm:inline">{{ __('السلة') }}</span>
                    </a>
                    
                    <div class="h-5 w-px bg-gray-200 dark:bg-gray-700 mx-1 hidden md:block"></div>
                    
                    <!-- Desktop Auth -->
                    <div class="hidden md:flex items-center gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 font-bold text-sm px-4 py-2 rounded-full transition-all duration-300">{{ __('دخول') }}</a>
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-full hover:bg-primary-700 hover:shadow-md transition-all duration-300 font-bold text-sm">{{ __('حساب جديد') }}</a>
                        @endguest
                        
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="bg-primary-50 dark:bg-gray-800 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-gray-700 px-4 py-2 rounded-full hover:bg-primary-100 dark:hover:bg-gray-700 transition-all duration-300 font-bold text-sm">{{ __('لوحة التحكم') }}</a>
                            @endif
                            @if(auth()->user()->is_driver)
                                <a href="{{ route('driver.orders') }}" class="bg-blue-50 dark:bg-gray-800 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-gray-700 px-4 py-2 rounded-full hover:bg-blue-100 dark:hover:bg-gray-700 transition-all duration-300 font-bold text-sm">{{ __('لوحة المندوب') }}</a>
                            @endif
                            
                            <a href="{{ route('profile.index') }}" class="relative group cursor-pointer block">
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-800 px-4 py-2 rounded-full transition-all duration-300">
                                    <div class="w-6 h-6 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center font-bold text-xs border border-gray-200 dark:border-gray-600">
                                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-sm">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                </div>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-gray-800 font-bold px-4 py-2 rounded-full transition-all duration-300 text-sm flex items-center gap-1">{{ __('خروج') }}</button>
                            </form>
                        @endauth
                    </div>

                    <!-- Mobile Hamburger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none p-2 ml-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Alpine.js) -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 absolute w-full shadow-lg" style="display: none;">
            <div class="px-4 pt-2 pb-4 space-y-1 shadow-inner">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">{{ __('الرئيسية') }}</a>
                <a href="{{ route('shop.index') }}" class="block px-3 py-3 rounded-xl font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">{{ __('المتجر') }}</a>
                <a href="{{ route('courses.index') }}" class="block px-3 py-3 rounded-xl font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">{{ __('تدريب المعلمين') }}</a>
                
                <div class="py-2 border-t border-gray-100 dark:border-gray-800 mt-2"></div>
                
                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-3 rounded-xl font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">{{ __('دخول') }}</a>
                    <a href="{{ route('register') }}" class="block px-3 py-3 rounded-xl font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 mt-1 transition-colors">{{ __('حساب جديد') }}</a>
                @endguest
                
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-xl font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-gray-800 mt-1 transition-colors">{{ __('لوحة التحكم') }}</a>
                    @endif
                    @if(auth()->user()->is_driver)
                        <a href="{{ route('driver.orders') }}" class="block px-3 py-3 rounded-xl font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-800 mt-1 transition-colors">{{ __('لوحة المندوب') }}</a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="block px-3 py-3 rounded-xl font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 mt-1 transition-colors">{{ __('حسابي') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-right block px-3 py-3 rounded-xl font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-800 transition-colors">{{ __('خروج') }}</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow transition-colors duration-300">
        @if(session()->has('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-[1rem] shadow-sm relative flex items-center justify-between" role="alert">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                    <button type="button" class="text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-green-100 dark:bg-green-800 hover:bg-green-200 dark:hover:bg-green-700 rounded-full p-1 transition-colors" onclick="this.parentElement.style.display='none'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 text-gray-500 dark:text-gray-400 pt-16 pb-8 mt-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                <!-- الشعار والوصف -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-16 w-auto object-contain drop-shadow-sm">
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium">{{ __('مؤسسة سجى المتخصصة. قطاع النشر والخدمات التعليمية. نقدم أفضل الكتب وأرقى الخدمات لعملائنا.') }}</p>
                </div>

                <!-- استكشاف -->
                <div>
                    <h3 class="text-gray-900 dark:text-white font-bold mb-4 text-lg">{{ __('إستكشاف') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('pages.privacy') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('سياسة الخصوصية') }}</a></li>
                        <li><a href="{{ route('pages.shipping') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('الشحن والإرجاع') }}</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('الشروط والأحكام') }}</a></li>
                    </ul>
                </div>

                <!-- حول المؤسسة -->
                <div>
                    <h3 class="text-gray-900 dark:text-white font-bold mb-4 text-lg">{{ __('حول المؤسسة') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('pages.about') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('من نحن') }}</a></li>
                        <li><a href="{{ route('pages.vision') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('رؤيتنا وأهدافنا') }}</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm hover:underline decoration-blue-200 dark:decoration-blue-800 underline-offset-4">{{ __('الأسئلة الشائعة') }}</a></li>
                    </ul>
                </div>

                <!-- تواصل -->
                <div>
                    <h3 class="text-gray-900 dark:text-white font-bold mb-4 text-lg">{{ __('تواصل معنا') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary-100 dark:bg-primary-900"></span> {{ __('تيليجرام') }}</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary-100 dark:bg-primary-900"></span> {{ __('إيميل') }}</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary-100 dark:bg-primary-900"></span> {{ __('فيسبوك') }}</a></li>
                        <li><a href="#" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors font-medium text-sm flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary-100 dark:bg-primary-900"></span> {{ __('واتساب') }}</a></li>
                    </ul>
                </div>
                
            </div>
            
            <div class="pt-8 border-t border-gray-100 dark:border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-medium text-sm text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} {{ __('مؤسسة سجى المتخصصة. جميع الحقوق محفوظة.') }}</p>
                <div class="flex gap-4">
                    <span class="text-gray-300 dark:text-gray-600 hover:text-primary-500 dark:hover:text-primary-400 cursor-pointer transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></span>
                    <span class="text-gray-300 dark:text-gray-600 hover:text-primary-700 dark:hover:text-primary-500 cursor-pointer transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Theme Toggle Script -->
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
