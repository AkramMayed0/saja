@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('الأسئلة الشائعة') }}</h1>
        
        <div class="space-y-6">
            <!-- Question 1 -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors duration-300">{{ __('كيف يمكنني تتبع طلبي؟') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('بمجرد شحن طلبك، سنقوم بإرسال رقم التتبع عبر البريد الإلكتروني. يمكنك استخدامه في صفحة تتبع الطلبات لمعرفة حالة الشحنة.') }}</p>
            </div>
            
            <!-- Question 2 -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors duration-300">{{ __('ما هي طرق الدفع المتاحة؟') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('نحن نقبل الدفع عبر البطاقات الائتمانية (فيزا، ماستركارد)، مدى، Apple Pay، والتحويل البنكي. كما يتوفر خيار الدفع عند الاستلام في مناطق محددة.') }}</p>
            </div>
            
            <!-- Question 3 -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors duration-300">{{ __('هل يمكنني إلغاء الطلب بعد تأكيده؟') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('يمكنك إلغاء الطلب خلال 12 ساعة من تأكيده بشرط ألا يكون قد تم شحنه بعد. يرجى التواصل مع خدمة العملاء فوراً.') }}</p>
            </div>
            
            <!-- Question 4 -->
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors duration-300">{{ __('هل يتوفر الشحن الدولي؟') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('حالياً نوفر الشحن داخل المملكة العربية السعودية ودول الخليج. نسعى للتوسع لتغطية مناطق أخرى قريباً.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
