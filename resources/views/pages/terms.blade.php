@extends('layouts.store')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-12 transition-colors duration-300">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-700 pb-4 transition-colors duration-300">{{ __('الشروط والأحكام') }}</h1>
        
        <div class="prose prose-blue max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-6 font-medium transition-colors duration-300">
            <p>{{ __('مرحباً بك في متجر مؤسسة سجى المتخصصة. باستخدامك لهذا الموقع، فإنك توافق على الالتزام بالشروط والأحكام التالية:') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('1. استخدام الموقع') }}</h2>
            <p>{{ __('يجب أن يكون عمرك 18 عاماً على الأقل أو تتسوق تحت إشراف والديك. يمنع استخدام منتجاتنا لأي غرض غير قانوني.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('2. حقوق الملكية') }}</h2>
            <p>{{ __('جميع المحتويات المتوفرة في هذا المتجر من نصوص وصور وشعارات هي ملك لمؤسسة سجى المتخصصة ومحمية بموجب قوانين حقوق الطبع والنشر.') }}</p>
            
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 transition-colors duration-300">{{ __('3. التسعير والدفع') }}</h2>
            <p>{{ __('نحتفظ بالحق في تغيير أسعار المنتجات في أي وقت دون إشعار مسبق. جميع المدفوعات آمنة وموثوقة.') }}</p>
        </div>
    </div>
</div>
@endsection
