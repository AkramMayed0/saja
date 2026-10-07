@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('من نحن') }}</h1>
        
        <div class="prose prose-blue max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-6 font-medium transition-colors duration-300">
            <div class="flex items-center gap-4 mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-20 w-auto object-contain shrink-0 drop-shadow-sm">
                <p class="text-xl font-bold text-gray-800 dark:text-gray-100 transition-colors duration-300">{{ __('مؤسسة سجى المتخصصة') }}</p>
            </div>
            
            <p>{{ __('نحن مؤسسة رائدة في قطاع النشر والخدمات التعليمية. تأسست مؤسسة سجى المتخصصة بهدف نشر المعرفة وإثراء المحتوى الثقافي والعلمي العربي.') }}</p>
            
            <p>{{ __('نقدم لعملائنا مجموعة منتقاة بعناية من أفضل الكتب والمراجع في مختلف المجالات، بالإضافة إلى توفير خدمات تعليمية وتقنية متقدمة تلبي تطلعات الأجيال القادمة.') }}</p>
            
            <p>{{ __('فريقنا يتكون من نخبة من المتخصصين والشغوفين بنشر الثقافة والعلم، نعمل بجد لضمان تجربة تسوق سلسة وممتعة لجميع عملائنا.') }}</p>
        </div>
    </div>
</div>
@endsection
