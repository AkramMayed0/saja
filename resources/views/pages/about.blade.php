@extends('layouts.store')

@section('title', __('من نحن'))

@section('content')
<x-page-header :title="__('من نحن')" :subtitle="__('تعرف على مؤسسة سجى المتخصصة وأهدافنا في نشر المعرفة.')" />

<div class="container-x py-14">
    <article data-reveal class="card p-7 sm:p-10 mx-auto max-w-4xl">
        <div class="prose-saja">
            <div class="flex items-center gap-4 mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-20 w-auto object-contain shrink-0 drop-shadow-sm dark:brightness-125">
                <p class="text-xl font-bold text-gray-800 dark:text-gray-100 transition-colors duration-300">{{ __('مؤسسة سجى المتخصصة') }}</p>
            </div>
            
            <p>{{ __('نحن مؤسسة رائدة في قطاع النشر والخدمات التعليمية. تأسست مؤسسة سجى المتخصصة بهدف نشر المعرفة وإثراء المحتوى الثقافي والعلمي العربي.') }}</p>
            
            <p>{{ __('نقدم لعملائنا مجموعة منتقاة بعناية من أفضل الكتب والمراجع في مختلف المجالات، بالإضافة إلى توفير خدمات تعليمية وتقنية متقدمة تلبي تطلعات الأجيال القادمة.') }}</p>
            
            <p>{{ __('فريقنا يتكون من نخبة من المتخصصين والشغوفين بنشر الثقافة والعلم، نعمل بجد لضمان تجربة تسوق سلسة وممتعة لجميع عملائنا.') }}</p>
        </div>
    </article>
</div>
@endsection
