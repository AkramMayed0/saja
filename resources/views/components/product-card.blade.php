@props(['product'])

@php
    $rating = round($product->rating ?? 0);
    $image = $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x550/f1f5f9/94a3b8?text=Book';
@endphp

<div data-reveal class="h-full">
<article data-tilt class="tilt group/card relative flex h-full flex-col rounded-2xl sm:rounded-3xl border border-gray-200/80 bg-white p-2 sm:p-3 text-start shadow-soft transition-shadow duration-300 hover:shadow-lift dark:border-gray-800 dark:bg-gray-900">

    <!-- Cover -->
    <a href="{{ route('shop.show', $product->slug) }}" class="relative block shrink-0 overflow-hidden rounded-xl sm:rounded-2xl aspect-[3/4] sm:aspect-auto sm:h-52 w-full bg-gray-100 dark:bg-gray-800" aria-label="{{ $product->title }}">
        <div class="relative w-full h-full transition-transform duration-500 ease-out group-hover/card:scale-105">
            <img src="{{ $image }}" alt="{{ $product->title }}" loading="lazy" class="h-full w-full object-cover">
        </div>

        <!-- Category chip -->
        <span class="absolute top-2 start-2 sm:top-3 sm:start-3 max-w-[85%] truncate rounded-full bg-white/90 px-2 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-[11px] font-medium text-gray-700 shadow-sm backdrop-blur border border-gray-200/50 dark:bg-gray-950/80 dark:text-gray-300 dark:border-gray-800" title="{{ $product->book_type ? $product->book_type_label : ($product->category->name ?? __('كتاب')) }}">
            {{ $product->book_type ? $product->book_type_label : ($product->category->name ?? __('كتاب')) }}
        </span>

        <!-- Quick view hint -->
        <span class="absolute top-3 end-3 hidden sm:flex h-8 w-8 translate-y-1 items-center justify-center rounded-full bg-white/90 text-gray-700 opacity-0 shadow-sm backdrop-blur transition-all duration-300 group-hover/card:translate-y-0 group-hover/card:opacity-100 dark:bg-gray-950/80 dark:text-gray-200" aria-hidden="true">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </span>

        @if($product->has_discount)
            <span class="absolute bottom-2 start-2 sm:bottom-2.5 sm:start-2.5 rounded-full bg-rose-600 text-white px-2 py-0.5 text-[9px] sm:text-[10px] font-extrabold shadow-sm">
                -{{ $product->savings_percentage }}%
            </span>
        @endif
    </a>

    <!-- Body -->
    <div class="flex flex-grow flex-col px-1 sm:px-2 pb-1 pt-2 sm:pt-4">
        <a href="{{ route('shop.show', $product->slug) }}" class="block">
            <h3 class="line-clamp-2 min-h-[2.4rem] sm:min-h-[3rem] font-display text-xs sm:text-base font-bold leading-tight sm:leading-6 text-gray-900 transition-colors group-hover/card:text-primary-700 dark:text-white dark:group-hover/card:text-primary-300">{{ $product->title }}</h3>
        </a>

        <!-- Tags in the box under the title (Gray) -->
        <div class="mt-1.5 sm:mt-2.5 flex items-center gap-1 sm:gap-1.5 flex-wrap">
            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200/80 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                {{ $product->book_type ? $product->book_type_label : ($product->category->name ?? __('كتاب')) }}
            </span>
            @if($product->level)
                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-medium bg-gray-50 text-gray-500 border border-gray-200/60 dark:bg-gray-800/60 dark:text-gray-400 dark:border-gray-700/60">
                    {{ __('مستوى') }} {{ $product->level }}
                </span>
            @endif
            @if($product->pages_count)
                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-medium bg-gray-50 text-gray-500 border border-gray-200/60 dark:bg-gray-800/60 dark:text-gray-400 dark:border-gray-700/60">
                    {{ $product->pages_count }} {{ $product->isFlashcard() ? __('بطاقة') : __('صفحة') }}
                </span>
            @endif
        </div>

        @if(($product->reviews_count ?? 0) > 0)
            <div class="mt-1.5 sm:mt-2 flex items-center gap-1 sm:gap-1.5 text-xs sm:text-sm" aria-label="{{ __('التقييم') }}: {{ $rating }}/5">
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-3 w-3 sm:h-3.5 sm:w-3.5 {{ $i <= $rating ? '' : 'text-gray-200 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500">({{ $product->reviews_count }})</span>
            </div>
        @else
            <div class="mt-1.5 sm:mt-2 flex items-center gap-1 sm:gap-1.5 text-xs text-gray-400 dark:text-gray-500" aria-label="{{ __('لا توجد تقييمات') }}">
                <div class="flex text-gray-200 dark:text-gray-700">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500">(0)</span>
            </div>
        @endif

        <div class="mt-auto flex items-center justify-between gap-1.5 sm:gap-3 pt-3 sm:pt-4">
            <div class="flex items-baseline gap-1.5 flex-wrap">
                <p class="font-display text-sm sm:text-xl font-extrabold tracking-tight text-gray-900 dark:text-white flex items-baseline gap-0.5 sm:gap-1">
                    @if(($currencySymbol ?? '$') === '$')
                        <span class="text-xs sm:text-sm font-bold text-gray-400">$</span><span>{{ number_format($product->current_price, 2) }}</span>
                    @else
                        <span>{{ number_format($product->current_price, 2) }}</span> <span class="text-[10px] sm:text-xs font-bold text-gray-400">{{ __('ر.س') }}</span>
                    @endif
                </p>
                @if($product->has_discount)
                    <span class="text-[10px] sm:text-xs text-gray-400 line-through font-semibold">
                        @if(($currencySymbol ?? '$') === '$')
                            ${{ number_format($product->original_price, 2) }}
                        @else
                            {{ number_format($product->original_price, 2) }}
                        @endif
                    </span>
                @endif
            </div>

            <form action="{{ route('cart.add') }}" method="POST" class="shrink-0">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" data-add-to-cart data-ripple
                        class="btn btn-sm text-[11px] sm:text-xs px-2.5 sm:px-3 py-1 sm:py-1.5 relative bg-gray-900 text-white hover:bg-primary-600 dark:bg-white dark:text-gray-900 dark:hover:bg-primary-500 dark:hover:text-white rounded-xl font-bold [&.is-loading]:pointer-events-none"
                        aria-label="{{ __('إضافة') }} {{ $product->title }}">
                    <span class="flex items-center gap-1 sm:gap-1.5 transition-opacity [.is-loading_&]:opacity-0">
                        <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>{{ __('إضافة') }}</span>
                    </span>
                    <span class="absolute inset-0 hidden items-center justify-center [.is-loading_&]:flex" aria-hidden="true">
                        <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <span class="tilt-glare" aria-hidden="true"></span>
</article>
</div>
