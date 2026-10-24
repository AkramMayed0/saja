@extends('layouts.store')

@section('title', __('من نحن') . ' - ' . __('مؤسسة سجى المتخصصة'))

@section('content')
<x-page-header 
    :eyebrow="__('قطاع النشر والخدمات التعليمية')"
    :title="__('من نحن')" 
    :subtitle="__('نحو تعليم أكثر جودة، ومناهج أقرب إلى واقع المتعلم واحتياجاته.')" 
/>

<div class="container-x py-12 sm:py-16 space-y-12 sm:space-y-16">

    <!-- Hero Identity Card -->
    <article data-reveal class="card p-7 sm:p-12 relative overflow-hidden bg-gradient-to-br from-white via-white to-primary-50/30 dark:from-gray-900 dark:via-gray-900 dark:to-primary-950/20 border border-gray-200/80 dark:border-gray-800 shadow-soft">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 pb-6 border-b border-gray-100 dark:border-gray-800">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('سجى') }}" class="h-20 w-auto object-contain shrink-0 drop-shadow-sm dark:brightness-125">
                <div class="text-center sm:text-start">
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                        {{ __('مؤسسة سجى المتخصصة') }}
                    </h2>
                    <p class="text-sm sm:text-base font-semibold text-primary-600 dark:text-primary-400 mt-1">
                        {{ __('قطاع النشر والخدمات التعليمية — الجمهورية اليمنية') }}
                    </p>
                </div>
            </div>

            <div class="prose-saja text-base sm:text-lg leading-relaxed text-gray-700 dark:text-gray-200 space-y-4 text-justify font-normal">
                <p>
                    {{ __('مؤسسة سجى المتخصصة، قطاع النشر والخدمات التعليمية، مؤسسة تعليمية يمنية تُعنى بتطوير وإعداد ونشر المناهج والمواد التعليمية والتدريبية، وتقديم الحلول والخدمات الداعمة للعملية التعليمية، بما يسهم في الارتقاء بمستوى التعليم، وتلبية احتياجات المدارس والمعاهد والمؤسسات التعليمية.') }}
                </p>
                <p>
                    {{ __('نؤمن بأن التعليم الجيد يبدأ من منهج متقن، ومحتوى هادف، وبيئة تعليمية محفزة، وشراكة فاعلة بين المؤلف والمعلم والمتعلم والمؤسسة التعليمية. ومن هذا المنطلق، نعمل على تطوير منتجات تعليمية تجمع بين الأسس التربوية الحديثة، والممارسات التعليمية الفاعلة، والهوية اليمنية والعربية والإسلامية، مع مراعاة احتياجات المتعلمين ومراحلهم العمرية ومستوياتهم التعليمية.') }}
                </p>
                <p>
                    {{ __('تضم منتجات المؤسسة سلاسل ومقررات تعليمية متنوعة في مجالات اللغة الإنجليزية، ورياض الأطفال، والحوسبة والذكاء الاصطناعي والأمن السيبراني، وغيرها من المجالات التعليمية التي تدعم بناء المعارف والمهارات، وتنمي التفكير والإبداع، وتعزز الاستعداد لمتطلبات الحياة المعاصرة.') }}
                </p>
                <p>
                    {{ __('ولا يقتصر دورنا على إعداد الكتب والمناهج، بل يمتد إلى دعم المؤسسات التعليمية والمعلمين من خلال الأدلة الإرشادية، والمواد والوسائل التعليمية المساندة، والبرامج التدريبية، والمصادر الرقمية، والخدمات التي تساعد على توظيف المناهج بصورة أكثر فاعلية داخل الصفوف الدراسية.') }}
                </p>
                <p>
                    {{ __('وفي سجى، ننظر إلى تطوير التعليم بوصفه مسؤولية مشتركة تتطلب الجودة والابتكار والاستمرارية. لذلك نسعى إلى بناء شراكات مثمرة مع المدارس والمعاهد والمعلمين والمهتمين بالتعليم، والعمل معهم على تقديم حلول تعليمية عملية ومستدامة تسهم في تحسين تجربة التعلم ومخرجاته.') }}
                </p>
            </div>

            <!-- Motto Banner -->
            <div class="p-4 sm:p-5 rounded-2xl bg-primary-50/80 dark:bg-primary-950/40 border border-primary-200/60 dark:border-primary-800/60 flex items-center justify-center text-center">
                <span class="text-base sm:text-lg font-black text-primary-700 dark:text-primary-300">
                    {{ __('سجى... محتوى تعليمي هادف، وهوية راسخة، ومستقبل واعد.') }}
                </span>
            </div>
        </div>
    </article>

    <!-- Core Values Section -->
    <div class="max-w-5xl mx-auto space-y-8">
        <div class="text-center max-w-2xl mx-auto">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-100 text-primary-800 dark:bg-primary-950 dark:text-primary-300 mb-2">
                {{ __('المبادئ والركائز') }}
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">
                {{ __('قيمنا المؤسسية') }}
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                {{ __('القيم السبع التي توجه عملنا وتلهم كل ما ننتجه ونقدمه لشركائنا.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- 1. Quality -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">01</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('الجودة والإتقان') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نلتزم بتقديم منتجات وخدمات تعليمية تتسم بالدقة والوضوح والتكامل، ونعمل على مراجعتها وتطويرها باستمرار لضمان تحقيق أهدافها التعليمية.') }}
                </p>
            </div>

            <!-- 2. Identity -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">02</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('الهوية والانتماء') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نعتز بهويتنا اليمنية والعربية والإسلامية، ونعمل على تجسيد قيمها الأصيلة في المحتوى التعليمي، بما يعزز الانتماء والوعي الثقافي والأخلاقي لدى المتعلمين.') }}
                </p>
            </div>

            <!-- 3. Innovation -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">03</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('الابتكار والتطوير') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نتبنى الأفكار والأساليب التعليمية الحديثة، ونستثمر الإمكانات التقنية المتاحة لتطوير المحتوى والوسائل التعليمية وتحسين تجربة التعلم.') }}
                </p>
            </div>

            <!-- 4. Learner First -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">04</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('المتعلم أولاً') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نجعل احتياجات المتعلم وقدراته وخصائصه العمرية محوراً أساسياً في تصميم مناهجنا، مع الاهتمام بالفروق الفردية وتوفير فرص تعلم أكثر فاعلية.') }}
                </p>
            </div>

            <!-- 5. Partnership -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">05</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('الشراكة والتعاون') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نؤمن بأهمية التعاون مع المدارس والمعاهد والمعلمين والمؤلفين والخبراء التربويين، ونحرص على بناء علاقات مهنية تقوم على الثقة وتبادل الخبرات وتحقيق المصالح المشتركة.') }}
                </p>
            </div>

            <!-- 6. Responsibility -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">06</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('المسؤولية والمصداقية') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نلتزم بالوضوح والصدق في تعاملاتنا، ونسعى إلى الوفاء بالتزاماتنا، وتحمل مسؤولياتنا تجاه شركائنا والمستفيدين والمجتمع.') }}
                </p>
            </div>

            <!-- 7. Sustainability (Span 2 or full) -->
            <div data-reveal class="card p-6 border border-gray-200/80 dark:border-gray-800 hover:border-primary-300 transition-all sm:col-span-2 lg:col-span-3">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 block mb-2">07</span>
                <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-2">{{ __('الاستدامة والتعلم المستمر') }}</h4>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('نعمل على تطوير قدراتنا ومنتجاتنا وخدماتنا بصورة مستمرة، بما يعزز استدامة الأداء، ويرفع كفاءة العمل، ويواكب المتغيرات التعليمية والتقنية.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Callout to Vision & Goals Dedicated Page -->
    <div data-reveal class="card p-8 sm:p-10 max-w-5xl mx-auto border border-primary-200/80 dark:border-primary-800/80 bg-gradient-to-br from-primary-50/60 via-white to-primary-100/30 dark:from-primary-950/40 dark:via-gray-900 dark:to-primary-900/20 shadow-soft">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-start">
            <div class="space-y-2">
                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider block">
                    {{ __('الخطة الاستراتيجية') }}
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">
                    {{ __('رؤيتنا، رسالتنا، وأهدافنا الاستراتيجية') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 max-w-xl">
                    {{ __('تعرف بالتفصيل على رؤيتنا التعليمية ورسالتنا التربوية وخارطة أهدافنا الاستراتيجية العشرة للارتقاء بجودة التعليم.') }}
                </p>
            </div>
            <a href="{{ route('pages.vision') }}" class="btn btn-primary shrink-0 shadow-md">
                <span>{{ __('استكشف الرؤية والأهداف') }}</span>
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

</div>
@endsection
