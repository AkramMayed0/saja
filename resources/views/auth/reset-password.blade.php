@extends('layouts.store')

@section('content')
<div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-10 pb-16 sm:pt-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 rounded-[2rem] border border-gray-200 dark:border-gray-700">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-4 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight transition-colors duration-300">{{ __('إعادة تعيين كلمة المرور') }}</h2>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('البريد الإلكتروني') }}</label>
                <input id="email" class="block w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                @error('email')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('كلمة المرور الجديدة') }}</label>
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
                    {{ __('إعادة تعيين كلمة المرور') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
