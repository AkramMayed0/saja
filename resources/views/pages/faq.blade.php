@extends('layouts.store')

@section('title', __('الأسئلة الشائعة والمتكررة') . ' - ' . __('مؤسسة سجى المتخصصة'))

@section('content')
<x-page-header 
    :eyebrow="__('إجابات شاملة ومفصلة')"
    :title="__('الأسئلة الشائعة والمتكررة')" 
    :subtitle="__('إجابات عن استفساراتكم حول مؤسسة سجى ومنتجاتها وخدماتها التعليمية وطرق الطلب والشراكة.')" 
/>

@php
$faqCategories = [
    'general' => [
        'name' => __('أسئلة عامة عن المؤسسة'),
        'short' => __('عن المؤسسة'),
        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    ],
    'curricula' => [
        'name' => __('أسئلة حول المناهج والمنتجات التعليمية'),
        'short' => __('المناهج والمنتجات'),
        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    ],
    'pricing' => [
        'name' => __('أسئلة حول الأسعار والشراء والتوريد'),
        'short' => __('الأسعار والتوريد'),
        'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
    ],
    'shipping' => [
        'name' => __('أسئلة حول الشحن والتوصيل والإرجاع'),
        'short' => __('الشحن والإرجاع'),
        'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
    ],
    'services' => [
        'name' => __('أسئلة حول الخدمات التعليمية والدعم الفني'),
        'short' => __('الخدمات والدعم'),
        'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
    ],
    'partnerships' => [
        'name' => __('أسئلة حول التعاون والشراكات'),
        'short' => __('التعاون والشراكات'),
        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    ],
    'privacy' => [
        'name' => __('أسئلة حول الخصوصية واستخدام الموقع'),
        'short' => __('الخصوصية والحقوق'),
        'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    ],
];

$faqs = [
    // Section 1: General (Q1 - Q5)
    [
        'id' => 1,
        'category' => 'general',
        'q' => __('ما مؤسسة سجى المتخصصة؟'),
        'a' => __('مؤسسة سجى المتخصصة – قطاع النشر والخدمات التعليمية – مؤسسة يمنية تعمل في مجال إعداد وتطوير ونشر المناهج والكتب والمواد التعليمية، وتقديم الخدمات والحلول الداعمة للعملية التعليمية، بما يسهم في تحسين جودة التعليم وتلبية احتياجات المدارس والمعاهد والمعلمين والمتعلمين.'),
    ],
    [
        'id' => 2,
        'category' => 'general',
        'q' => __('ما طبيعة الخدمات التي تقدمها المؤسسة؟'),
        'a' => __('تقدم المؤسسة مجموعة من المنتجات والخدمات التعليمية، تشمل إعداد ونشر المناهج والكتب المدرسية، وأدلة المعلمين، والأنشطة والوسائل التعليمية المساندة، والمواد الرقمية، والبرامج التدريبية والخدمات الداعمة للتعليم، بحسب المنتجات والخدمات المتاحة.'),
    ],
    [
        'id' => 3,
        'category' => 'general',
        'q' => __('ما الذي يميز منتجات مؤسسة سجى؟'),
        'a' => __('نحرص على تقديم محتوى تعليمي منظم وهادف، يراعي احتياجات المتعلمين وخصائصهم العمرية ومستوياتهم التعليمية، ويجمع بين الأسس التربوية الحديثة والهوية اليمنية والعربية والإسلامية. كما نهتم بتوفير المواد المساندة التي تساعد المعلمين على تنفيذ الدروس وتحقيق أهداف التعلم.'),
    ],
    [
        'id' => 4,
        'category' => 'general',
        'q' => __('هل منتجات المؤسسة مخصصة للمدارس فقط؟'),
        'a' => __('لا تقتصر منتجات المؤسسة على المدارس، بل تشمل، بحسب طبيعة كل منتج، رياض الأطفال، والمدارس، والمعاهد التعليمية، والمعلمين، والأفراد الراغبين في الاستفادة من المواد التعليمية المتاحة.'),
    ],
    [
        'id' => 5,
        'category' => 'general',
        'q' => __('كيف يمكنني التواصل مع المؤسسة؟'),
        'a' => __('يمكنكم التواصل معنا عبر أرقام الهواتف أو واتساب أو البريد الإلكتروني أو نماذج الاتصال المنشورة على الموقع. ويسعد فريق المؤسسة استقبال استفساراتكم حول المنتجات والأسعار والتوريد والخدمات التعليمية.'),
    ],

    // Section 2: Curricula & Products (Q6 - Q11)
    [
        'id' => 6,
        'category' => 'curricula',
        'q' => __('ما أنواع المناهج والمنتجات التي توفرها المؤسسة؟'),
        'a' => __('توفر المؤسسة منتجات تعليمية في عدد من المجالات، من أبرزها تعليم اللغة الإنجليزية، ورياض الأطفال، والحوسبة والذكاء الاصطناعي والأمن السيبراني، إلى جانب المواد والوسائل التعليمية المساندة. ويمكنكم الاطلاع على تفاصيل المنتجات المتاحة من خلال قسم المنتجات في الموقع.'),
    ],
    [
        'id' => 7,
        'category' => 'curricula',
        'q' => __('كيف يمكنني التعرف إلى تفاصيل كل سلسلة تعليمية؟'),
        'a' => __('يمكنكم زيارة صفحة السلسلة المطلوبة للاطلاع على وصفها ومكوناتها والمستويات التي تغطيها وخصائصها التعليمية والمواد المساندة المرتبطة بها، بحسب المعلومات المتاحة لكل منتج.'),
    ],
    [
        'id' => 8,
        'category' => 'curricula',
        'q' => __('هل تتوفر أدلة للمعلمين مع المناهج؟'),
        'a' => __('تتضمن بعض السلاسل التعليمية أدلة خاصة بالمعلمين تساعدهم على فهم أهداف الدروس وتنفيذ الأنشطة وتوظيف الوسائل التعليمية. وتختلف مكونات كل سلسلة؛ لذلك يُرجى مراجعة وصف المنتج أو التواصل مع المؤسسة لمعرفة ما يتوفر معه من أدلة ومواد إضافية.'),
    ],
    [
        'id' => 9,
        'category' => 'curricula',
        'q' => __('هل تتوفر ملفات صوتية أو وسائل تعليمية إضافية؟'),
        'a' => __('قد تتضمن بعض المنتجات ملفات صوتية وبطاقات تعليمية ومواد رقمية أو وسائل مساندة أخرى. ويعتمد ذلك على طبيعة السلسلة ومكوناتها. ويمكن لفريق المؤسسة توضيح المواد المتاحة لكل منتج وطريقة الحصول عليها.'),
    ],
    [
        'id' => 10,
        'category' => 'curricula',
        'q' => __('هل يمكن الاطلاع على نماذج من الكتب قبل طلبها؟'),
        'a' => __('يمكنكم الاستفسار عن النماذج التعريفية أو الصفحات التجريبية أو العينات المتاحة من المنتجات. وتحدد المؤسسة طريقة الاطلاع عليها أو الحصول عليها بحسب طبيعة المنتج وتوافر العينات.'),
    ],
    [
        'id' => 11,
        'category' => 'curricula',
        'q' => __('هل تطور المؤسسة منتجاتها باستمرار؟'),
        'a' => __('تسعى المؤسسة إلى تطوير منتجاتها وخدماتها التعليمية بصورة مستمرة، والاستفادة من الملاحظات التربوية وآراء المعلمين والمؤسسات التعليمية، بما يساعد على تحسين المحتوى والأنشطة والمواد المساندة.'),
    ],

    // Section 3: Pricing, Purchasing & Supply (Q12 - Q18)
    [
        'id' => 12,
        'category' => 'pricing',
        'q' => __('كيف يمكنني معرفة أسعار المنتجات؟'),
        'a' => __('يمكنكم التعرف إلى الأسعار من خلال صفحات المنتجات، إذا كانت الأسعار منشورة، أو بالتواصل المباشر مع المؤسسة. وقد تختلف الأسعار بحسب المنتج والكمية المطلوبة وطبيعة الطلب وأي عروض أو اتفاقيات توريد معتمدة.'),
    ],
    [
        'id' => 13,
        'category' => 'pricing',
        'q' => __('هل تختلف الأسعار عند الشراء بكميات كبيرة؟'),
        'a' => __('قد تتوفر ترتيبات أو عروض خاصة للمدارس والمعاهد والطلبات الكبيرة وفقاً لسياسة التسعير المعتمدة لدى المؤسسة. ويمكن للجهات التعليمية التواصل معنا للحصول على عرض سعر يتناسب مع احتياجاتها وكمياتها المطلوبة.'),
    ],
    [
        'id' => 14,
        'category' => 'pricing',
        'q' => __('كيف يمكن للمدارس والمعاهد طلب المناهج؟'),
        'a' => __('يمكن للمدرسة أو المعهد التواصل مع المؤسسة مباشرة لتحديد المنتجات والمستويات والكميات المطلوبة، ثم استكمال إجراءات عرض السعر واعتماد الطلب والتوريد وفق الآلية المتفق عليها.'),
    ],
    [
        'id' => 15,
        'category' => 'pricing',
        'q' => __('هل يمكن طلب المنتجات للأفراد؟'),
        'a' => __('يمكن للأفراد الاستفسار عن المنتجات المتاحة للبيع المباشر، والكميات الممكن توفيرها، ومواقع الاستلام أو خيارات التوصيل، بحسب المنتج والمنطقة.'),
    ],
    [
        'id' => 16,
        'category' => 'pricing',
        'q' => __('ما وسائل الدفع المتاحة؟'),
        'a' => __('تُحدد وسائل الدفع المتاحة عند تأكيد الطلب، وفق الإجراءات المعتمدة لدى المؤسسة. ويمكن الاستفسار من فريق المبيعات عن طريقة السداد المناسبة للطلب، سواء كان شراءً فردياً أو توريداً لمؤسسة تعليمية.'),
    ],
    [
        'id' => 17,
        'category' => 'pricing',
        'q' => __('هل يمكن الحصول على عرض سعر رسمي؟'),
        'a' => __('نعم، يمكن للمدارس والمعاهد والمؤسسات التعليمية طلب عرض سعر رسمي يتضمن المنتجات والكميات والأسعار والشروط المتعلقة بالتوريد، بحسب الإجراءات المعتمدة لدى المؤسسة.'),
    ],
    [
        'id' => 18,
        'category' => 'pricing',
        'q' => __('هل يمكن التعاقد على توريد المناهج للمدرسة؟'),
        'a' => __('يمكن للمدارس والمعاهد التواصل مع المؤسسة لبحث احتياجاتها من المناهج والمنتجات التعليمية، ومناقشة الكميات والأسعار وآلية التوريد وشروط التعاون، وفق الاتفاق الذي يعتمده الطرفان.'),
    ],

    // Section 4: Shipping, Delivery & Returns (Q19 - Q24)
    [
        'id' => 19,
        'category' => 'shipping',
        'q' => __('هل توفر المؤسسة خدمة توصيل المنتجات؟'),
        'a' => __('تعتمد خيارات التسليم والتوصيل على موقع العميل وطبيعة الطلب والوسائل المتاحة. ويمكنكم التواصل مع المؤسسة لمعرفة إمكانية التوصيل إلى منطقتكم والتكلفة والمدة المتوقعة.'),
    ],
    [
        'id' => 20,
        'category' => 'shipping',
        'q' => __('كم يستغرق وصول الطلب؟'),
        'a' => __('تختلف مدة تجهيز الطلب وتوصيله بحسب توافر المنتجات، وحجم الطلب، وموقع التسليم، وطريقة الشحن. وتوضح المدة المتوقعة عند تأكيد الطلب.'),
    ],
    [
        'id' => 21,
        'category' => 'shipping',
        'q' => __('ماذا أفعل إذا وصلني منتج تالف أو غير مطابق للطلب؟'),
        'a' => __('يُرجى التواصل مع المؤسسة في أقرب وقت ممكن، مع توضيح المشكلة وتقديم رقم الطلب أو مستند الشراء، وإرفاق صور توضح التلف أو الاختلاف متى أمكن. وستتم مراجعة الحالة لتحديد الإجراء المناسب وفق سياسة الشحن والإرجاع.'),
    ],
    [
        'id' => 22,
        'category' => 'shipping',
        'q' => __('هل يمكن إرجاع المنتجات أو استبدالها؟'),
        'a' => __('يمكن طلب الإرجاع أو الاستبدال في الحالات التي تشملها سياسة المؤسسة، مثل استلام منتج تالف أو غير مطابق للطلب أو وجود نقص في الكميات. وتخضع الطلبات للمراجعة وفق سياسة الشحن والإرجاع والاتفاقات الخاصة بالطلب، مع مراعاة الحقوق التي يقررها القانون.'),
    ],
    [
        'id' => 23,
        'category' => 'shipping',
        'q' => __('هل يمكن للمدارس إرجاع الكتب الزائدة بعد التوزيع؟'),
        'a' => __('تخضع مرتجعات المدارس والطلبات المؤسسية الكبيرة لشروط التوريد والاتفاق المعتمد مع المؤسسة. لذلك يُنصح بالاتفاق مسبقاً على سياسة المرتجعات والكميات الزائدة والمواعيد والإجراءات المتعلقة بها قبل اعتماد الطلب.'),
    ],
    [
        'id' => 24,
        'category' => 'shipping',
        'q' => __('أين أجد تفاصيل سياسة الشحن والإرجاع؟'),
        'a' => __('يمكنكم الاطلاع على صفحة «سياسة الشحن والإرجاع» المنشورة على الموقع، والتي توضح الإجراءات العامة للتوصيل، والتعامل مع المنتجات التالفة أو غير المطابقة، وشروط طلب الإرجاع أو الاستبدال.'),
    ],

    // Section 5: Services & Support (Q25 - Q28)
    [
        'id' => 25,
        'category' => 'services',
        'q' => __('هل تقدم المؤسسة خدمات تدريبية للمعلمين؟'),
        'a' => __('تعمل المؤسسة على توفير خدمات وبرامج داعمة للمعلمين والمؤسسات التعليمية بحسب خططها وخدماتها المتاحة. ويمكن للمدارس والمعاهد الاستفسار عن البرامج التدريبية والورش والأنشطة المهنية التي يمكن توفيرها.'),
    ],
    [
        'id' => 26,
        'category' => 'services',
        'q' => __('هل يمكن الحصول على مساعدة في استخدام المواد التعليمية؟'),
        'a' => __('يمكنكم التواصل مع المؤسسة للاستفسار عن أدلة المعلمين والمواد المساندة والإرشادات المتاحة لكل منتج. وإذا كانت هناك مشكلة فنية تتعلق بملف رقمي أو مادة إلكترونية توفرها المؤسسة، فيُرجى توضيح طبيعة المشكلة ونوع الجهاز أو البرنامج المستخدم لتسهيل معالجتها.'),
    ],
    [
        'id' => 27,
        'category' => 'services',
        'q' => __('ماذا أفعل إذا تعذر فتح ملف رقمي أو الوصول إلى مادة تعليمية؟'),
        'a' => __('يُرجى التأكد أولاً من اكتمال تنزيل الملف وتوافر البرنامج المناسب لفتحه. وإذا استمرت المشكلة، يمكنكم التواصل مع فريق الدعم عبر قنوات الاتصال الرسمية، مع توضيح اسم المنتج والملف ونوع المشكلة التي تواجهكم.'),
    ],
    [
        'id' => 28,
        'category' => 'services',
        'q' => __('هل يمكن للمدارس تقديم ملاحظاتها ومقترحاتها حول المناهج؟'),
        'a' => __('نرحب بملاحظات المدارس والمعاهد والمعلمين ومقترحاتهم، وننظر إليها بوصفها مصدراً مهماً للتطوير والتحسين. ويمكن إرسال الملاحظات عبر قنوات التواصل الرسمية، مع تحديد اسم المنتج أو السلسلة التعليمية والصفحة أو الموضوع محل الملاحظة.'),
    ],

    // Section 6: Partnerships & Cooperation (Q29 - Q31)
    [
        'id' => 29,
        'category' => 'partnerships',
        'q' => __('هل يمكن للمدارس والمعاهد التعاون مع المؤسسة؟'),
        'a' => __('نرحب بالتواصل مع المدارس والمعاهد والمؤسسات التعليمية الراغبة في التعاون في مجالات المناهج والكتب والمواد التعليمية والتدريب والخدمات المساندة. ويمكن للجهات المهتمة توضيح احتياجاتها ومجالات التعاون المقترحة عبر قنوات الاتصال الرسمية.'),
    ],
    [
        'id' => 30,
        'category' => 'partnerships',
        'q' => __('هل يمكن العمل مع المؤسسة في مجال توزيع المنتجات التعليمية؟'),
        'a' => __('يمكن للراغبين في التعاون التجاري أو توزيع منتجات المؤسسة التواصل مع الإدارة المختصة، وتقديم معلومات عن المنطقة المستهدفة والخبرة في مجال التوزيع والقدرات التشغيلية. وتخضع فرص التعاون لشروط المؤسسة وإجراءات التقييم والاعتماد.'),
    ],
    [
        'id' => 31,
        'category' => 'partnerships',
        'q' => __('هل يمكن للمؤلفين والخبراء التربويين تقديم مقترحات لتطوير المناهج؟'),
        'a' => __('ترحب المؤسسة بالمقترحات المهنية والأفكار التطويرية التي تتصل بمجالات عملها. ويمكن للمهتمين تقديم نبذة عن خبراتهم ومجالات تخصصهم وفكرة المشروع المقترح، ليتم النظر فيها وفق احتياجات المؤسسة وإجراءاتها.'),
    ],

    // Section 7: Privacy & Website (Q32 - Q34)
    [
        'id' => 32,
        'category' => 'privacy',
        'q' => __('كيف تتعامل المؤسسة مع بيانات المستخدمين؟'),
        'a' => __('تحرص المؤسسة على التعامل مع البيانات الشخصية بمسؤولية، واستخدامها للأغراض المرتبطة بالتواصل وتقديم الخدمات ومتابعة الطلبات وتحسين تجربة المستخدم، وفقاً لسياسة الخصوصية المنشورة على الموقع.'),
    ],
    [
        'id' => 33,
        'category' => 'privacy',
        'q' => __('هل يمكنني طلب تعديل بياناتي أو حذفها؟'),
        'a' => __('يمكنكم التواصل مع المؤسسة لطلب تحديث البيانات الشخصية أو تصحيحها أو حذفها، بحسب طبيعة البيانات والغرض من الاحتفاظ بها والالتزامات القانونية ذات الصلة. وتتم معالجة الطلبات وفق سياسة الخصوصية والإجراءات المعمول بها.'),
    ],
    [
        'id' => 34,
        'category' => 'privacy',
        'q' => __('كيف يمكنني معرفة المزيد عن حقوق استخدام الكتب والمواد التعليمية؟'),
        'a' => __('يمكنكم مراجعة الشروط والأحكام المنشورة على الموقع، والتي تتضمن أحكاماً تتعلق بحقوق الملكية الفكرية واستخدام المناهج والكتب والمواد الرقمية. وللحصول على إذن لاستخدام مادة تعليمية محمية خارج نطاق الاستخدام المعتاد، يُرجى التواصل مع المؤسسة أو صاحب الحق المختص.'),
    ],
];
@endphp

<div class="container-x py-10 sm:py-16 space-y-10" 
     x-data="{
        searchQuery: '',
        activeCategory: 'all',
        openItems: [1],
        toggle(id) {
            if (this.openItems.includes(id)) {
                this.openItems = this.openItems.filter(item => item !== id);
            } else {
                this.openItems.push(id);
            }
        },
        isOpen(id) {
            return this.openItems.includes(id);
        },
        expandAll() {
            this.openItems = [{{ implode(',', array_column($faqs, 'id')) }}];
        },
        collapseAll() {
            this.openItems = [];
        },
        matchesSearch(text) {
            if (!this.searchQuery.trim()) return true;
            return text.toLowerCase().includes(this.searchQuery.toLowerCase().trim());
        }
     }">

    <!-- Filter & Search Controls Header -->
    <div data-reveal class="card p-6 sm:p-8 bg-gradient-to-br from-white via-white to-primary-50/30 dark:from-gray-900 dark:via-gray-900 dark:to-primary-950/20 border border-gray-200/80 dark:border-gray-800 shadow-soft">
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <!-- Live Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="search" 
                    x-model="searchQuery"
                    placeholder="{{ __('ابحث عن سؤال أو كلمة مفتاحية (مثل: التوصيل، المدارس، العينات، الأسعار...)') }}" 
                    class="w-full ps-11 pe-10 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                >
                <button 
                    type="button" 
                    x-show="searchQuery.length > 0" 
                    x-cloak
                    @click="searchQuery = ''" 
                    class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                    title="{{ __('مسح البحث') }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Quick Actions: Expand/Collapse All -->
            <div class="flex items-center gap-2 shrink-0">
                <button 
                    type="button" 
                    @click="expandAll()" 
                    class="btn btn-sm btn-outline text-xs font-bold"
                >
                    <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"/></svg>
                    {{ __('توسيع الكل') }}
                </button>
                <button 
                    type="button" 
                    @click="collapseAll()" 
                    class="btn btn-sm btn-outline text-xs font-bold"
                >
                    <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"/></svg>
                    {{ __('طي الكل') }}
                </button>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800">
            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 block mb-3 uppercase tracking-wider">
                {{ __('تصفية حسب الموضوع:') }}
            </span>
            <div class="flex flex-wrap gap-2">
                <button 
                    type="button"
                    @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-primary-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5"
                >
                    <span>{{ __('جميع الأسئلة') }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="activeCategory === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">{{ count($faqs) }}</span>
                </button>

                @foreach($faqCategories as $catKey => $catInfo)
                    @php
                        $count = count(array_filter($faqs, fn($f) => $f['category'] === $catKey));
                    @endphp
                    <button 
                        type="button"
                        @click="activeCategory = '{{ $catKey }}'"
                        :class="activeCategory === '{{ $catKey }}' ? 'bg-primary-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5"
                    >
                        <span>{{ $catInfo['short'] }}</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="activeCategory === '{{ $catKey }}' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Questions Groups by Category -->
    <div class="space-y-8">
        @foreach($faqCategories as $catKey => $catInfo)
            @php
                $categoryFaqs = array_filter($faqs, fn($f) => $f['category'] === $catKey);
            @endphp
            
            <section 
                x-show="(activeCategory === 'all' || activeCategory === '{{ $catKey }}')" 
                x-cloak
                class="space-y-3"
            >
                <!-- Category Section Heading -->
                <div class="flex items-center gap-3 pb-2 border-b border-gray-200/80 dark:border-gray-800">
                    <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-950/60 text-primary-600 dark:text-primary-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $catInfo['icon'] }}"/></svg>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">
                        {{ $catInfo['name'] }}
                    </h2>
                    <span class="text-xs text-gray-400 font-semibold ms-auto">
                        {{ count($categoryFaqs) }} {{ __('أسئلة') }}
                    </span>
                </div>

                <!-- Questions List in this Category -->
                <div class="space-y-3">
                    @foreach($categoryFaqs as $faq)
                        <article 
                            x-show="matchesSearch(@js($faq['q'] . ' ' . $faq['a']))"
                            class="card border border-gray-200/80 dark:border-gray-800 overflow-hidden transition-all duration-200 hover:border-primary-300 dark:hover:border-primary-700/60"
                        >
                            <!-- Question Button -->
                            <button 
                                type="button" 
                                @click="toggle({{ $faq['id'] }})"
                                class="w-full p-4 sm:p-5 text-start flex items-start justify-between gap-4 transition-colors focus:outline-none"
                                :class="isOpen({{ $faq['id'] }}) ? 'bg-primary-50/40 dark:bg-primary-950/20' : 'bg-white dark:bg-gray-900 hover:bg-gray-50/70 dark:hover:bg-gray-800/40'"
                                :aria-expanded="isOpen({{ $faq['id'] }}) ? 'true' : 'false'"
                            >
                                <div class="flex items-start gap-3">
                                    <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $faq['id'] }}
                                    </span>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 leading-snug">
                                        {{ $faq['q'] }}
                                    </h3>
                                </div>

                                <div class="shrink-0 ms-2 mt-1">
                                    <span 
                                        class="w-6 h-6 rounded-full flex items-center justify-center text-gray-400 transition-transform duration-200"
                                        :class="isOpen({{ $faq['id'] }}) ? 'rotate-180 text-primary-600 dark:text-primary-400' : ''"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </div>
                            </button>

                            <!-- Answer Body -->
                            <div 
                                x-show="isOpen({{ $faq['id'] }})"
                                x-collapse
                                class="border-t border-gray-100 dark:border-gray-800 p-4 sm:p-6 bg-white dark:bg-gray-900"
                            >
                                <p class="text-sm sm:text-base leading-relaxed text-gray-700 dark:text-gray-300 text-justify">
                                    {{ $faq['a'] }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <!-- Contact & Direct Assistance Callout Card -->
    <article data-reveal class="card p-8 sm:p-10 border border-primary-200/80 dark:border-primary-800/80 bg-gradient-to-br from-primary-50/70 via-white to-primary-100/30 dark:from-primary-950/40 dark:via-gray-900 dark:to-primary-900/20 shadow-soft">
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-primary-100 dark:bg-primary-900/60 text-primary-600 dark:text-primary-400 flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            
            <h3 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">
                {{ __('لم تجد إجابة عن سؤالك؟') }}
            </h3>
            
            <p class="text-sm sm:text-base leading-relaxed text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                {{ __('يسعدنا في مؤسسة سجى المتخصصة مساعدتكم والإجابة عن استفساراتكم المتعلقة بمنتجاتنا ومناهجنا وخدماتنا التعليمية. تواصلوا معنا عبر قنوات الاتصال الرسمية المنشورة على الموقع، وسيعمل فريقنا على توجيه استفساركم إلى الجهة المختصة.') }}
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-3">
                <a href="https://wa.me/967781818809" target="_blank" rel="noopener" class="btn btn-primary shadow-md">
                    <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    {{ __('مراسلتنا عبر واتساب') }}
                </a>
                <a href="mailto:info@saja-edu.com" class="btn btn-outline">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>info@saja-edu.com</span>
                </a>
                <a href="tel:+967781818809" class="btn btn-outline" dir="ltr">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>+967 781 818 809</span>
                </a>
            </div>

            <div class="pt-4 border-t border-primary-200/50 dark:border-primary-800/50">
                <span class="text-xs sm:text-sm font-black text-primary-700 dark:text-primary-300">
                    {{ __('سجى... محتوى تعليمي هادف، وهوية راسخة، ومستقبل واعد.') }}
                </span>
            </div>
        </div>
    </article>
</div>
@endsection
