@extends('layouts.store')

@section('content')
<div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-10 pb-16 sm:pt-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 rounded-[2rem] border border-gray-200 dark:border-gray-700">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-4 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight transition-colors duration-300">{{ __('نسيت كلمة المرور؟') }}</h2>
        </div>

        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed text-center transition-colors duration-300">
            {{ __('لا مشكلة. فقط أخبرنا ببريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور لتتمكن من اختيار كلمة مرور جديدة.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 font-bold text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-4 rounded-xl border border-green-200 dark:border-green-800/50 text-center transition-colors duration-300">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('البريد الإلكتروني') }}</label>
                <input id="email" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="example@email.com" />
                @error('email')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('إرسال رابط إعادة التعيين') }}
                </button>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 font-bold transition-colors duration-300">{{ __('العودة لتسجيل الدخول') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
