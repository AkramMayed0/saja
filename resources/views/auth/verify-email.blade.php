@extends('layouts.store')

@section('content')
<div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-10 pb-16 sm:pt-10 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-300 rounded-[2rem] border border-gray-200 dark:border-gray-700">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-4 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight transition-colors duration-300">{{ __('التحقق من البريد الإلكتروني') }}</h2>
        </div>

        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed text-center transition-colors duration-300">
            {{ __('شكراً لانضمامك إلينا! قبل البدء، هل يمكنك التحقق من عنوان بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه لك للتو؟ إذا لم تتلقَ البريد الإلكتروني، سنرسل لك واحداً آخر بكل سرور.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-bold text-sm text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-4 rounded-xl border border-green-200 dark:border-green-800/50 text-center transition-colors duration-300">
                {{ __('تم إرسال رابط تحقق جديد إلى عنوان البريد الإلكتروني الذي قدمته أثناء التسجيل.') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3.5 px-6 border border-transparent rounded-full shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('إعادة إرسال البريد') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto text-center sm:text-right">
                @csrf
                <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 font-bold transition-colors duration-300">
                    {{ __('تسجيل الخروج') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
