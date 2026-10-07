@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('رؤيتنا وأهدافنا') }}</h1>
        
        <div class="prose prose-blue max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-6 font-medium transition-colors duration-300">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 flex items-center gap-2 transition-colors duration-300">
                <span class="w-3 h-3 rounded-full bg-primary-500 inline-block"></span> {{ __('الرؤية') }}
            </h2>
            <p>{{ __('أن نكون المنصة الأولى والخيار الأمثل في العالم العربي لكل ما يتعلق بالنشر والخدمات التعليمية المبتكرة.') }}</p>
            
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-10 mb-4 flex items-center gap-2 transition-colors duration-300">
                <span class="w-3 h-3 rounded-full bg-primary-500 inline-block"></span> {{ __('رسالتنا') }}
            </h2>
            <p>{{ __('تسهيل الوصول إلى المعرفة وتوفير بيئة تعليمية وثقافية محفزة من خلال تقديم محتوى قيم وخدمات عالية الجودة تواكب التطور التكنولوجي.') }}</p>
            
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-10 mb-4 flex items-center gap-2 transition-colors duration-300">
                <span class="w-3 h-3 rounded-full bg-primary-500 inline-block"></span> {{ __('أهدافنا') }}
            </h2>
            <ul class="list-disc list-inside space-y-2 mt-4 text-gray-500 dark:text-gray-400 transition-colors duration-300">
                <li>{{ __('نشر الوعي الثقافي والمعرفي في المجتمع.') }}</li>
                <li>{{ __('دعم الكتاب والمؤلفين وإبراز المواهب الجديدة.') }}</li>
                <li>{{ __('توفير مكتبة شاملة تلبي كافة الاحتياجات التعليمية والثقافية.') }}</li>
                <li>{{ __('التميز في تقديم خدمات العملاء وتجربة التسوق الرقمي.') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
