@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400 font-medium">
        <ol class="flex items-center space-x-2 space-x-reverse">
            <li><a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors hover:underline underline-offset-4">{{ __('الرئيسية') }}</a></li>
            <li><span class="mx-2 text-gray-300 dark:text-gray-600">/</span></li>
            <li><a href="{{ route('shop.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors hover:underline underline-offset-4">{{ __('المتجر') }}</a></li>
            <li><span class="mx-2 text-gray-300 dark:text-gray-600">/</span></li>
            <li class="font-bold text-gray-800 dark:text-gray-200 truncate max-w-[200px] sm:max-w-md">{{ $product->title }}</li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-10 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-10 transition-colors duration-300">
        
        <!-- Book Cover -->
        <div class="w-full md:w-1/3 flex-shrink-0">
            <div class="aspect-[3/4] rounded-[1.5rem] overflow-hidden flex items-center justify-center text-gray-300 dark:text-gray-600 relative group transition-colors duration-300 shadow-sm border border-gray-100 dark:border-gray-800">
                <!-- If image exists it goes here, else placeholder -->
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <svg class="w-24 h-24 text-gray-200 dark:text-gray-700 transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                @endif
            </div>
        </div>
        
        <!-- Book Details -->
        <div class="w-full md:w-2/3 flex flex-col">
            <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-6 flex flex-col sm:flex-row justify-between items-start gap-6 transition-colors duration-300">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 leading-tight tracking-tight">{{ $product->title }}</h1>
                    <div class="flex flex-wrap items-center gap-3">
                        @if($product->author)
                            <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 font-bold px-4 py-1.5 rounded-full text-sm transition-colors duration-300">{{ __('تأليف:') }} {{ $product->author }}</span>
                        @endif
                        @if($product->category)
                            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-bold px-4 py-1.5 rounded-full text-sm border border-gray-200 dark:border-gray-600 transition-colors duration-300">{{ $product->category->name }}</a>
                        @endif
                    </div>
                </div>
                <div class="text-right w-full sm:w-auto flex-shrink-0 bg-gray-50 dark:bg-gray-900 p-4 rounded-[1.5rem] border border-gray-100 dark:border-gray-700 min-w-[140px] flex flex-col items-center sm:items-end justify-center transition-colors duration-300">
                    <div class="text-3xl font-black text-primary-600 dark:text-primary-400 mb-2">${{ number_format($product->price, 2) }}</div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold w-full text-center bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 transition-colors duration-300">
                        {{ __('متوفر') }}
                    </span>
                </div>
            </div>
            
            <div class="mb-8 flex-grow">
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-4 flex items-center gap-2 transition-colors duration-300">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span> {{ __('نبذة عن الكتاب') }}
                </h3>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-justify font-medium transition-colors duration-300">
                    {{ $product->description ?? __('لا توجد نبذة متوفرة لهذا الكتاب حالياً.') }}
                </p>
            </div>
            
            <!-- Book Specs Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-10 bg-gray-50 dark:bg-gray-900 rounded-[1.5rem] p-6 border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                @if($product->publisher)
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">{{ __('دار النشر') }}</span>
                    <strong class="text-gray-800 dark:text-gray-200 text-sm transition-colors duration-300">{{ $product->publisher }}</strong>
                </div>
                @endif
                
                @if($product->language)
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">{{ __('اللغة') }}</span>
                    <strong class="text-gray-800 dark:text-gray-200 text-sm transition-colors duration-300">{{ $product->language }}</strong>
                </div>
                @endif
                
                @if($product->age_suited)
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">{{ __('الفئة العمرية') }}</span>
                    <strong class="text-gray-800 dark:text-gray-200 text-sm transition-colors duration-300">{{ $product->age_suited }}</strong>
                </div>
                @endif
                
                @if($product->isbn)
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">ISBN</span>
                    <strong class="text-gray-800 dark:text-gray-200 font-mono text-sm transition-colors duration-300">{{ $product->isbn }}</strong>
                </div>
                @endif
                
                @if($product->format)
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">{{ __('الصيغة') }}</span>
                    <strong class="text-gray-800 dark:text-gray-200 text-sm transition-colors duration-300">{{ $product->format }}</strong>
                </div>
                @endif
                
                <div>
                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1 transition-colors duration-300">{{ __('رقم المنتج') }}</span>
                    <strong class="text-gray-800 dark:text-gray-200 text-sm transition-colors duration-300">{{ $product->sku ?? ('#' . $product->id) }}</strong>
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="w-full sm:w-32 flex-shrink-0 relative">
                        <label for="quantity" class="sr-only">{{ __('الكمية') }}</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-full focus:border-primary-500 focus:ring-primary-500 focus:bg-white dark:focus:bg-gray-800 py-4 px-4 text-center font-bold text-lg transition-colors duration-300">
                    </div>
                    
                    <button type="submit" class="flex-grow bg-primary-600 text-white py-4 px-8 rounded-full font-bold text-lg hover:bg-primary-700 hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        {{ __('أضف للسلة') }}
                    </button>
                </form>
            </div>
            
        </div>
    </div>

    <!-- Related Accessories Section -->
    @if($relatedAccessories->count() > 0)
    <div class="mt-12 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-10 transition-colors duration-300">
        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
            <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 p-2 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </span>
            {{ __('ملحقات هذا المستوى') }}
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($relatedAccessories as $accessory)
                <x-product-card :product="$accessory" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    <div class="mt-12 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-10 transition-colors duration-300">
        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
            <svg class="w-7 h-7 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            {{ __('التقييمات والمراجعات') }}
            <span class="text-lg font-medium text-gray-500 dark:text-gray-400">({{ $product->reviews_count ?? 0 }})</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Add Review Form -->
            <div>
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">{{ __('أضف تقييمك') }}</h4>
                @auth
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="bg-gray-50 dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('التقييم') }}</label>
                            <div class="flex items-center gap-2 flex-row-reverse justify-end" style="direction: ltr;">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="peer hidden" {{ old('rating') == $i ? 'checked' : '' }} required />
                                    <label for="star{{ $i }}" class="cursor-pointer text-gray-300 dark:text-gray-600 peer-hover:text-yellow-400 peer-checked:text-yellow-400 text-2xl transition-colors">
                                        ★
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('تعليقك (اختياري)') }}</label>
                            <textarea id="comment" name="comment" rows="3" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-primary-500 focus:border-primary-500 p-3 text-sm text-gray-700 dark:text-gray-300" placeholder="{{ __('شاركنا رأيك في هذا الكتاب...') }}">{{ old('comment') }}</textarea>
                        </div>
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-full text-sm transition-colors w-full sm:w-auto">
                            {{ __('إرسال التقييم') }}
                        </button>
                    </form>
                @else
                    <div class="bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 rounded-2xl p-6 text-center">
                        <p class="text-primary-800 dark:text-primary-300 mb-4 font-medium">{{ __('يرجى تسجيل الدخول لإضافة تقييمك.') }}</p>
                        <a href="{{ route('login') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded-full text-sm transition-colors">{{ __('تسجيل الدخول') }}</a>
                    </div>
                @endauth
            </div>

            <!-- Existing Reviews List -->
            <div>
                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">{{ __('آراء القراء') }}</h4>
                @if($product->reviews->count() > 0)
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($product->reviews as $review)
                            <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-bold text-gray-800 dark:text-gray-200 text-sm flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ Str::substr($review->user->name, 0, 2) }}
                                        </div>
                                        {{ $review->user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $review->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="flex text-yellow-400 text-sm mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->rating ? '' : 'text-gray-300 dark:text-gray-600' }}">★</span>
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('لا توجد تقييمات بعد. كن أول من يقيم هذا الكتاب!') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS for simple star rating system */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    
    /* Star Rating CSS Hack */
    input[name="rating"]:checked ~ label,
    label:hover,
    label:hover ~ label { 
        color: #facc15 !important; 
    }
</style>
@endsection
