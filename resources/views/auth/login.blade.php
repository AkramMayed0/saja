@extends('layouts.store')

@section('content')
<div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-10 pb-16 sm:pt-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 rounded-[2rem] border border-gray-200 dark:border-gray-700">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-4 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight transition-colors duration-300">{{ __('تسجيل الدخول') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium transition-colors duration-300">{{ __('مرحباً بعودتك! الرجاء إدخال بياناتك للمتابعة.') }}</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 font-bold text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-4 rounded-xl border border-green-200 dark:border-green-800/50 text-center transition-colors duration-300">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('البريد الإلكتروني') }}</label>
                <input id="email" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="example@email.com" />
                @error('email')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('كلمة المرور') }}</label>
                <input id="password" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                @error('password')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-8">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-primary-600 shadow-sm focus:ring-primary-500 w-4 h-4 cursor-pointer transition-colors duration-300" name="remember">
                    <span class="mr-2 ms-2 text-sm text-gray-600 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('تذكرني') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors duration-300" href="{{ route('password.request') }}">
                        {{ __('نسيت كلمة المرور؟') }}
                    </a>
                @endif
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('تسجيل الدخول') }}
                </button>
            </div>
            
            <!-- Google Login Button -->
            <div class="mt-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 font-medium">أو</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('google.login') }}" class="w-full flex justify-center items-center gap-3 py-3.5 px-4 border border-gray-300 dark:border-gray-600 rounded-full shadow-sm text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        {{ __('المتابعة باستخدام جوجل') }}
                    </a>
                </div>
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400 font-medium border-t border-gray-100 dark:border-gray-700 pt-6 transition-colors duration-300">
                {{ __('ليس لديك حساب؟') }} 
                <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-bold transition-colors duration-300">{{ __('إنشاء حساب جديد') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
