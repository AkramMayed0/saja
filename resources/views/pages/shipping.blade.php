@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('الشحن والإرجاع') }}</h1>
        
        <div class="prose prose-blue max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-6 font-medium transition-colors duration-300">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('سياسة الشحن') }}</h2>
            <p>{{ __('نحن نوفر خدمة شحن سريعة وموثوقة لجميع مناطق المملكة. يتم معالجة وشحن الطلبات خلال 24-48 ساعة عمل من تأكيد الطلب.') }}</p>
            <ul class="list-disc list-inside space-y-2 mt-4 text-gray-500 dark:text-gray-400 transition-colors duration-300">
                <li>{{ __('الشحن المجاني للطلبات التي تتجاوز قيمتها 300 ريال.') }}</li>
                <li>{{ __('مدة التوصيل تتراوح بين 2 إلى 5 أيام عمل حسب منطقتك.') }}</li>
            </ul>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('سياسة الإرجاع') }}</h2>
            <p>{{ __('هدفنا هو رضاكم التام. إذا لم تكن راضياً عن طلبك، يمكنك إرجاعه خلال 14 يوماً من تاريخ الاستلام.') }}</p>
            <ul class="list-disc list-inside space-y-2 mt-4 text-gray-500 dark:text-gray-400 transition-colors duration-300">
                <li>{{ __('يجب أن تكون المنتجات في حالتها الأصلية غير مستخدمة.') }}</li>
                <li>{{ __('الكتب الرقمية والمحتوى القابل للتنزيل غير قابل للإرجاع.') }}</li>
                <li>{{ __('يتحمل العميل رسوم شحن الإرجاع إلا في حالة وصول منتج تالف أو خاطئ.') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
