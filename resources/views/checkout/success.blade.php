@extends('layouts.store')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-12 transition-colors duration-300">
        <div class="w-24 h-24 bg-green-50 dark:bg-green-900/30 text-green-500 dark:text-green-400 rounded-full flex items-center justify-center mx-auto mb-8 transition-colors duration-300 shadow-sm border border-green-100 dark:border-green-800">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        
        <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-4 transition-colors duration-300 tracking-tight">{{ __('شكراً لك!') }}</h2>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-10 font-medium transition-colors duration-300">{{ __('تم استلام طلبك بنجاح وجاري العمل على تجهيزه.') }}</p>
        
        @if(session('order_id'))
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl py-5 px-8 inline-block mb-12 border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
                <span class="text-gray-500 dark:text-gray-400 block mb-2 font-bold text-sm uppercase tracking-wider">{{ __('رقم الطلب الخاص بك هو') }}</span>
                <span class="text-3xl font-black text-primary-600 dark:text-primary-400">#{{ session('order_id') }}</span>
            </div>
        @endif
        
        <div>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300 text-lg">
                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>{{ __('متابعة التسوق') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection
