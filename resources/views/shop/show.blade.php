@extends('layouts.store')

@section('title', $product->title)

@section('content')
<div class="py-8 sm:py-12 transition-colors duration-300">
    <div class="container-x">
        
        <!-- ============================== BREADCRUMBS ============================== -->
        @php
            $category = $product->category ?? $product->bundle?->category;
        @endphp
        <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400 font-medium" aria-label="{{ __('المسار') }}">
            <ol class="flex items-center space-x-2 space-x-reverse flex-wrap gap-y-1">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('الرئيسية') }}</a>
                </li>
                <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                <li>
                    <a href="{{ route('shop.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('المناهج') }}</a>
                </li>
                @if($category)
                    <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                    <li>
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" 
                           title="{{ $category->name }}"
                           class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            {{ Str::words($category->name, 4, '...') }}
                        </a>
                    </li>
                @endif
                @if($product->bundle)
                    <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                    <li>
                        <a href="{{ route('bundles.show', $product->bundle->slug) }}" 
                           title="{{ $product->bundle->name }}"
                           class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            {{ Str::words($product->bundle->name, 5, '...') }}
                        </a>
                    </li>
                @endif
                <li><span class="mx-1.5 text-gray-300 dark:text-gray-700">/</span></li>
                <li class="font-bold text-gray-900 dark:text-white" 
                    title="{{ $product->title }}" 
                    aria-current="page">
                    {{ Str::words($product->title, 5, '...') }}
                </li>
            </ol>
        </nav>

        <!-- ============================== MAIN PRODUCT SHOWCASE ============================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            
            <!-- Left Column: Sticky Cover & Direct Actions (5 cols) -->
            <div class="lg:col-span-5 lg:sticky lg:top-24">
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-4 sm:p-6 shadow-soft transition-colors">
                    
                    <!-- Book Cover Image -->
                    <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 dark:bg-gray-800/60 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-center group">
                        @php
                            $cover = $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x800/f1f5f9/94a3b8?text=Book+Cover';
                        @endphp
                        <img src="{{ $cover }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                        @if($product->book_type_label)
                            <span class="absolute top-3 start-3 px-3 py-1 rounded-full text-xs font-bold bg-white/90 dark:bg-gray-900/90 text-primary-700 dark:text-primary-300 shadow-sm backdrop-blur border border-white/40 dark:border-gray-700">
                                {{ $product->book_type_label }}
                            </span>
                        @endif
                    </div>

                    <!-- Direct Sample PDF Download Button (If available) -->
                    @if($product->sample_pdf)
                        <div class="mt-4">
                            <a href="{{ route('shop.sample', $product->slug) }}"
                               class="btn btn-outline w-full rounded-2xl py-3.5 text-sm font-bold flex items-center justify-center gap-2 shadow-soft hover:shadow-lift transition-all">
                                <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>{{ __('تحميل عينة من الكتاب (PDF)') }}</span>
                            </a>
                        </div>
                    @endif

                    <!-- Trust & Delivery Highlights -->
                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-800/80 space-y-2.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ __('طباعة عالية الجودة ومعتمدة للمدارس والمراكز التعليمية') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-leaf-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ __('شحن سريع وتوصيل متاح لجميع المناطق والمحافظات') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Information, Specs & Purchase Box (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                
                <!-- Header Block: Badges & Title -->
                <div>
                    <!-- Badges Row -->
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if($product->category)
                            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary-50 dark:bg-primary-950/60 text-primary-700 dark:text-primary-300 border border-primary-200/60 dark:border-primary-800/60 hover:bg-primary-100 transition-colors">
                                {{ $product->category->name }}
                            </a>
                        @endif

                        @if($product->level)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                {{ __('المستوى:') }} {{ $product->level }}
                            </span>
                        @endif
                    </div>

                    <!-- Book Title -->
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white leading-tight tracking-tight">
                        {{ $product->title }}
                    </h1>
                </div>

                <!-- Bundle Connection Callout (If part of a bundle) -->
                @if($product->bundle)
                    <a href="{{ route('bundles.show', $product->bundle->slug) }}" 
                       class="group rounded-2xl p-3 sm:px-4 sm:py-3 bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800/80 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm hover:border-gray-200 dark:hover:border-gray-700 transition-all flex items-center justify-between gap-3 cursor-pointer">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 shrink-0 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <div class="min-w-0 text-xs sm:text-sm">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">{{ __('متوفر ضمن باقة:') }}</span>
                                <span class="font-bold text-gray-900 dark:text-white ms-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                    {{ $product->bundle->name }}
                                </span>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 dark:bg-primary-950/60 border border-primary-200/60 dark:border-primary-800/60 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/60 group-hover:border-primary-300 dark:group-hover:border-primary-700 transition-all shrink-0">
                            <span>{{ __('عرض الباقة') }}</span>
                            <svg class="w-3.5 h-3.5 rtl:rotate-180 transition-transform duration-200 group-hover:translate-x-0.5 rtl:group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </a>
                @endif

                <!-- Clean Purchase & Price Box -->
                <div class="p-6 sm:p-7 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 shadow-soft">
                    <div class="flex items-baseline justify-between gap-4 mb-5 pb-5 border-b border-gray-100 dark:border-gray-800">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('سعر النسخة') }}
                            </span>
                            <div class="flex items-baseline gap-2">
                                <span class="font-display text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                                    {{ $product->formatted_price }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex flex-col sm:flex-row items-stretch gap-3">
                            <!-- Quantity -->
                            <div class="w-full sm:w-28 shrink-0">
                                <label for="quantity" class="sr-only">{{ __('الكمية') }}</label>
                                <div class="relative">
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="100" 
                                           class="w-full h-12 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl text-center font-bold text-base focus:ring-primary-500 focus:border-primary-500">
                                    <span class="absolute start-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 pointer-events-none">{{ __('العدد') }}</span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" data-ripple
                                    class="flex-1 h-12 btn btn-primary rounded-xl text-base font-bold flex items-center justify-center gap-2 shadow-soft hover:shadow-lift transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>{{ __('أضف للسلة') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Overview / Description -->
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 sm:p-7 shadow-soft">
                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white mb-3">
                        {{ __('نبذة عن الكتاب والمحتوى التعليمي') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm sm:text-base text-justify font-normal">
                        {{ $product->description ?? __('لا توجد نبذة متوفرة لهذا الكتاب حالياً.') }}
                    </p>
                </div>

                <!-- Clean Specifications Grid -->
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 sm:p-7 shadow-soft">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white mb-4">
                        {{ __('بيانات ومواصفات الكتاب') }}
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                        @if($product->author)
                            <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('المؤلف') }}</span>
                                <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold block" title="{{ $product->author }}">
                                    {{ $product->author }}
                                </strong>
                            </div>
                        @endif

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('دار النشر') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold block" title="{{ $product->publisher ?? __('مؤسسة سجى المتخصصة') }}">
                                {{ $product->publisher ?? __('مؤسسة سجى المتخصصة') }}
                            </strong>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('اللغة') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold block">
                                {{ $product->language ?? 'English' }}
                            </strong>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('الصيغة') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-bold block">
                                {{ $product->isAudio() ? __('صوتيات MP3') : __('ورقي مطبوع') }}
                            </strong>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1">{{ __('رمز المنتج') }}</span>
                            <strong class="text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-mono font-bold block">
                                {{ $product->sku ?? ('#' . $product->id) }}
                            </strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================== RELATED ACCESSORIES / BOOKS ============================== -->
        @if($relatedAccessories->count() > 0)
            <div class="mt-16 pt-12 border-t border-gray-200/80 dark:border-gray-800">
                <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4">
                    <div class="min-w-0 max-w-2xl">
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white leading-snug">
                            {{ __('ملحقات وكتب أخرى لنفس المستوى') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            {{ __('يمكنك إكمال المنهج بطلب الكتب والملفات التكميلية لهذا المستوى:') }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedAccessories as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ============================== REVIEWS SECTION ============================== -->
        <div class="mt-16 pt-12 border-t border-gray-200/80 dark:border-gray-800">
            <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 p-6 sm:p-10 shadow-soft">
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span>{{ __('التقييمات والمراجعات') }}</span>
                    <span class="text-sm font-medium text-gray-400">({{ $product->reviews->count() }})</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Form -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('أضف تقييمك للكتاب') }}</h4>
                        @auth
                            <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="bg-gray-50/80 dark:bg-gray-800/60 p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5">{{ __('التقييم') }}</label>
                                    <div class="flex items-center gap-1.5 flex-row-reverse justify-end" style="direction: ltr;">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="peer hidden" {{ old('rating') == $i ? 'checked' : '' }} required />
                                            <label for="star{{ $i }}" class="cursor-pointer text-gray-300 dark:text-gray-600 peer-hover:text-amber-400 peer-checked:text-amber-400 text-2xl transition-colors">
                                                ★
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="comment" class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5">{{ __('تعليقك (اختياري)') }}</label>
                                    <textarea id="comment" name="comment" rows="3" class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 text-sm text-gray-800 dark:text-gray-200" placeholder="{{ __('شاركنا رأيك في هذا الكتاب ومحتواه...') }}">{{ old('comment') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 font-bold">
                                    {{ __('إرسال التقييم') }}
                                </button>
                            </form>
                        @else
                            <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ __('يرجى تسجيل الدخول لإضافة تقييمك.') }}</p>
                                <a href="{{ route('login') }}" class="btn btn-outline btn-sm rounded-xl font-bold">{{ __('تسجيل الدخول') }}</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Reviews List -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('آراء المعلمين والعملاء') }}</h4>
                        @if($product->reviews->count() > 0)
                            <div class="space-y-3.5 max-h-[380px] overflow-y-auto pe-1">
                                @foreach($product->reviews as $review)
                                    <div class="p-4 rounded-2xl bg-gray-50/80 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-bold text-gray-800 dark:text-gray-200 text-xs sm:text-sm flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-300 flex items-center justify-center font-bold text-xs uppercase">
                                                    {{ Str::substr($review->user->name, 0, 2) }}
                                                </div>
                                                {{ $review->user->name }}
                                            </div>
                                            <span class="text-[11px] text-gray-400">
                                                {{ $review->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="flex text-amber-400 text-xs mb-1.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->rating ? '' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                            @endfor
                                        </div>
                                        @if($review->comment)
                                            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm leading-relaxed">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <p class="text-xs sm:text-sm text-gray-400">{{ __('لا توجد تقييمات بعد. كن أول من يكتب مراجعة لهذا الكتاب!') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
