@extends('layouts.store')

@section('content')
<div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-10 pb-16 sm:pt-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 rounded-[2rem] border border-gray-200 dark:border-gray-700">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-4 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight transition-colors duration-300">{{ __('إنشاء حساب جديد') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium transition-colors duration-300">{{ __('انضم إلينا الآن للتمتع بتجربة تسوق مميزة.') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-5">
                <label for="name" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('الاسم بالكامل') }}</label>
                <input id="name" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="{{ __('محمد أحمد') }}" />
                @error('name')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('البريد الإلكتروني') }}</label>
                <input id="email" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="example@email.com" />
                @error('email')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('كلمة المرور') }}</label>
                <input id="password" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                @error('password')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-8">
                <label for="password_confirmation" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('تأكيد كلمة المرور') }}</label>
                <input id="password_confirmation" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                @error('password_confirmation')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('إنشاء حساب') }}
                </button>
            </div>
            
            <!-- Google Login Button -->
            <div class="mt-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 font-medium">{{ __('أو') }}</span>
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
                {{ __('لديك حساب بالفعل؟') }} 
                <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-bold transition-colors duration-300">{{ __('تسجيل الدخول') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
