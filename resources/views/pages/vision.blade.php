@extends('layouts.store')

@section('title', __('رؤيتنا ورسالتنا وأهدافنا') . ' - ' . __('مؤسسة سجى المتخصصة'))

@section('content')
<x-page-header 
    :eyebrow="__('المنظومة التعليمية المتكاملة')"
    :title="__('رؤيتنا ورسالتنا وأهدافنا')" 
    :subtitle="__('نحو تعليم أكثر جودة، ومناهج أقرب إلى واقع المتعلم واحتياجاته.')" 
/>

<div class="container-x py-12 sm:py-16 space-y-12">

    <!-- Vision & Mission Side by Side -->
    <div class="grid gap-6 md:grid-cols-2 max-w-5xl mx-auto">
        <!-- Vision -->
        <article data-reveal class="card p-7 sm:p-10 border-t-4 border-t-primary-500 shadow-soft">
            <div class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-3">
                {{ __('رؤيتنا') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm sm:text-base text-justify">
                {{ __('أن نكون مؤسسة يمنية رائدة ومتميزة في مجال النشر التعليمي وتطوير المناهج والخدمات التعليمية، وأن نُسهم في بناء منظومة تعليمية أكثر جودة وابتكاراً، من خلال تقديم منتجات وحلول تعليمية موثوقة تجمع بين الأصالة والمعاصرة، وتواكب تطورات المعرفة والتقنية، وتلبي احتياجات المتعلمين والمؤسسات التعليمية.') }}
            </p>
        </article>

        <!-- Mission -->
        <article data-reveal class="card p-7 sm:p-10 border-t-4 border-t-leaf-500 shadow-soft">
            <div class="w-12 h-12 rounded-2xl bg-leaf-50 dark:bg-leaf-950/60 text-leaf-600 dark:text-leaf-400 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-3">
                {{ __('رسالتنا') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm sm:text-base text-justify">
                {{ __('تطوير وإنتاج ونشر مناهج ومواد تعليمية متكاملة، وتقديم خدمات وحلول تربوية وتدريبية مساندة، وفق معايير الجودة والممارسات التعليمية الحديثة، بما يعزز فاعلية التعليم والتعلم، وينمي معارف المتعلمين ومهاراتهم، ويرسخ هويتهم اليمنية والعربية والإسلامية، ويدعم المعلمين والمؤسسات التعليمية في تحقيق أهدافهم التربوية والتعليمية.') }}
            </p>
        </article>
    </div>

    <!-- 10 Strategic Goals -->
    <div data-reveal class="card p-7 sm:p-10 max-w-5xl mx-auto border border-gray-200/80 dark:border-gray-800 shadow-soft">
        <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between flex-wrap gap-3">
            <div>
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider block mb-1">
                    {{ __('الأهداف التربوية للمؤسسة') }}
                </span>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">
                    {{ __('أهدافنا الاستراتيجية العشرة') }}
                </h3>
            </div>
            <a href="{{ route('pages.about') }}" class="btn btn-sm btn-outline text-xs font-bold">
                {{ __('تصفح صفحة من نحن') }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $goals = [
                    ['num' => '1', 'title' => __('تطوير المناهج والمواد التعليمية'), 'desc' => __('إعداد وإنتاج مناهج ومقررات تعليمية متكاملة تستند إلى أسس تربوية وعلمية، وتراعي احتياجات المتعلمين ومراحلهم ومستوياتهم التعليمية.')],
                    ['num' => '2', 'title' => __('الارتقاء بجودة التعليم'), 'desc' => __('الإسهام في تحسين عمليات التعليم والتعلم من خلال تقديم محتوى تعليمي واضح وهادف، وأنشطة تطبيقية، ووسائل مساندة تعزز الفهم والممارسة والتعلم النشط.')],
                    ['num' => '3', 'title' => __('تعزيز الهوية والقيم'), 'desc' => __('إنتاج محتوى تعليمي يعكس الهوية اليمنية والعربية والإسلامية، ويرسخ القيم الأخلاقية والاجتماعية الإيجابية.')],
                    ['num' => '4', 'title' => __('مواكبة التطورات المعرفية والتقنية'), 'desc' => __('تطوير منتجات تعليمية في مجالات اللغات والحوسبة والذكاء الاصطناعي والأمن السيبراني وغيرها من المجالات التي تلبي احتياجات الحاضر والمستقبل.')],
                    ['num' => '5', 'title' => __('دعم المعلمين والمؤسسات التعليمية'), 'desc' => __('توفير أدلة المعلمين والمواد المساندة والبرامج التدريبية والخدمات الاستشارية التي تساعد على الاستخدام الفاعل للمناهج وتحسين الممارسات الصفية.')],
                    ['num' => '6', 'title' => __('توسيع نطاق الخدمات التعليمية'), 'desc' => __('بناء علاقات تعاون وشراكات مستدامة مع المدارس والمعاهد والمؤسسات التعليمية، وتطوير خدمات تلبي احتياجاتها بصورة عملية ومرنة.')],
                    ['num' => '7', 'title' => __('تطوير الموارد التعليمية الرقمية'), 'desc' => __('توظيف التقنيات الحديثة في إنتاج مصادر ووسائل تعليمية متنوعة تسهم في إثراء المحتوى وتوسيع فرص الاستفادة منه.')],
                    ['num' => '8', 'title' => __('تحقيق رضا المستفيدين'), 'desc' => __('الاستماع إلى آراء المدارس والمعلمين والمتعلمين، والاستفادة من ملاحظاتهم في تقويم المنتجات والخدمات وتطويرها.')],
                    ['num' => '9', 'title' => __('تعزيز البحث والتقويم التربوي'), 'desc' => __('الاستفادة من التغذية الراجعة والبيانات الميدانية والخبرات المتخصصة في مراجعة المناهج وقياس فاعليتها وتحديد فرص التحسين.')],
                    ['num' => '10', 'title' => __('ترسيخ التميز والاستدامة المؤسسية'), 'desc' => __('تطوير الكفاءات المهنية والإدارية، وتحسين جودة العمليات، وتعزيز كفاءة الأداء، بما يدعم استمرارية المؤسسة ونموها.')],
                ];
            @endphp

            @foreach($goals as $g)
                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800">
                    <span class="w-7 h-7 rounded-xl bg-primary-100 dark:bg-primary-950 text-primary-700 dark:text-primary-300 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        {{ $g['num'] }}
                    </span>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $g['title'] }}</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ $g['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
