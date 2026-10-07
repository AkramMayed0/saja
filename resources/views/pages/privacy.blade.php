@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('سياسة الخصوصية') }}</h1>
        
        <div class="prose prose-blue max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-6 font-medium transition-colors duration-300">
            <p>{{ __('نحن في مؤسسة سجى المتخصصة نولي أهمية قصوى لخصوصية عملائنا. تشرح هذه السياسة كيفية جمع واستخدام وحماية معلوماتك الشخصية.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('1. المعلومات التي نجمعها') }}</h2>
            <p>{{ __('نقوم بجمع المعلومات التي تقدمها لنا طواعية عند التسجيل في الموقع، أو عند إجراء عملية شراء، مثل الاسم، البريد الإلكتروني، العنوان، ورقم الهاتف.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('2. استخدام المعلومات') }}</h2>
            <p>{{ __('تُستخدم معلوماتك لمعالجة طلباتك، تحسين تجربة تسوقك، والتواصل معك بشأن العروض والتحديثات الجديدة إذا كنت قد اشتركت في النشرة البريدية.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('3. حماية البيانات') }}</h2>
            <p>{{ __('نحن نستخدم أحدث تقنيات التشفير لحماية بياناتك الشخصية والمالية. لا يتم مشاركة بياناتك مع أي طرف ثالث لأغراض تجارية.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('4. التعديلات على السياسة') }}</h2>
            <p>{{ __('نحتفظ بالحق في تحديث سياسة الخصوصية هذه في أي وقت. سيتم إشعارك بأي تغييرات جوهرية عبر البريد الإلكتروني.') }}</p>
        </div>
    </div>
</div>
@endsection
