@extends('layouts.store')

@section('title', __('الأسئلة الشائعة'))

@section('content')
<x-page-header :title="__('الأسئلة الشائعة')" :subtitle="__('إجابات سريعة لأكثر الأسئلة التي يطرحها عملاؤنا.')" />

<div class="container-x py-14">
    <article data-reveal class="mx-auto max-w-3xl space-y-4">
        <!-- Question 1 -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('كيف يمكنني تتبع طلبي؟') }}</h3>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ __('يمكنك تتبع حالة طلبك (قيد الانتظار، قيد التجهيز، أو مكتمل) بكل سهولة من خلال قسم "الطلبات" في حسابك الشخصي بعد تسجيل الدخول.') }}</p>
        </div>
        
        <!-- Question 2 -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('ما هي طرق الدفع المتاحة؟') }}</h3>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ __('نوفر لعملائنا في اليمن طريقة الدفع عند الاستلام فقط لضمان راحتكم وثقتكم بنا. لا حاجة لاستخدام أي بطاقات بنكية للدفع عبر الموقع.') }}</p>
        </div>
        
        <!-- Question 3 -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('هل يمكنني إلغاء الطلب بعد تأكيده؟') }}</h3>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ __('نعم، بكل تأكيد! يمكنك إلغاء الطلب مباشرة من حسابك الشخصي طالما أن الطلب في حالة "قيد الانتظار" ولم يبدأ فريقنا بتجهيزه للطباعة أو توصيله.') }}</p>
        </div>
        
        <!-- Question 4 -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('أين تتوفر خدمة التوصيل؟') }}</h3>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ __('حالياً تقتصر خدمة التوصيل على عملائنا داخل الجمهورية اليمنية فقط لضمان سرعة التوصيل وأعلى جودة للخدمة.') }}</p>
        </div>

        <!-- Question 5 -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('هل الكتب المعروضة متوفرة دائماً؟') }}</h3>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ __('نعم، جميع منتجاتنا وكتبنا متوفرة باستمرار! حيث نعتمد على نظام الطباعة عند الطلب، مما يضمن حصولك على نسختك دائماً بجودة عالية دون خوف من نفاد المخزون.') }}</p>
        </div>
    </article>
</div>
@endsection
