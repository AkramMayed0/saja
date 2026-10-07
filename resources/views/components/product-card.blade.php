@props(['product'])

<div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600 transition-colors duration-300 relative text-right h-full group/card">
    
    <!-- مساحة الصورة -->
    <a href="{{ route('shop.show', $product->slug) }}" class="block w-full aspect-[3/4] shrink-0 relative overflow-hidden transition-colors duration-300">
        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x550/f9fafb/9ca3af?text=Book' }}" alt="{{ $product->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-105">
    </a>

    <!-- المحتوى -->
    <div class="p-5 flex flex-col flex-grow text-right">
        
        <!-- العنوان -->
        <a href="{{ route('shop.show', $product->slug) }}">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 line-clamp-1 mb-1 transition-colors duration-300">{{ $product->title }}</h3>
        </a>

        <!-- التقييم -->
        <div class="flex items-center gap-1.5 mb-4 text-sm justify-start">
            <div class="text-yellow-400 tracking-widest text-base flex">
                @php $rating = round($product->rating ?? 0); @endphp
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? '' : 'text-gray-200 dark:text-gray-600' }}">★</span>
                @endfor
            </div>
            <span class="text-gray-400 dark:text-gray-500 text-xs font-medium transition-colors duration-300">({{ $product->reviews_count ?? 0 }})</span>
        </div>

        <!-- التاغات -->
        <div class="flex flex-wrap gap-2 mb-6">
            <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold px-3.5 py-1.5 rounded-full transition-colors duration-300">{{ $product->category->name ?? __('كتاب') }}</span>
        </div>

        <!-- السعر وزر الإضافة في الأسفل -->
        <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-100 dark:border-gray-700 transition-colors duration-300">
            <!-- السعر -->
            <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tight transition-colors duration-300">${{$product->price }}</span>
            
            <!-- زر الإضافة -->
            <form action="{{ route('cart.add') }}" method="POST" class="shrink-0">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 dark:hover:border-primary-800 font-bold py-2 px-5 rounded-full transition-colors duration-300 shadow-sm flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <span class="text-sm">{{ __('إضافة') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
