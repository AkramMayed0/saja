@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-8 transition-colors duration-300">{{ __('سلة المشتريات') }}</h2>

    @if(!$cart || $cart->cartItems->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm p-12 text-center border border-gray-200 dark:border-gray-700 transition-colors duration-300">
            <svg class="w-20 h-20 mx-auto text-primary-200 dark:text-primary-900/50 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 transition-colors duration-300">{{ __('سلتك فارغة حالياً') }}</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-8 font-medium transition-colors duration-300">{{ __('يبدو أنك لم تضف أي منتجات إلى سلتك بعد.') }}</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-primary-600 text-white font-bold py-3.5 px-8 rounded-full shadow-sm hover:shadow-md hover:bg-primary-700 hover:-translate-y-0.5 transition-all duration-300">{{ __('تصفح المنتجات') }}</a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700 transition-colors duration-300">
                        @foreach($cart->cartItems as $item)
                            <li class="p-6 flex flex-col sm:flex-row items-center gap-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-300">
                                <!-- Placeholder Image -->
                                <div class="w-24 h-24 bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 rounded-[1.25rem] flex-shrink-0 flex items-center justify-center text-primary-300 dark:text-primary-500 transition-colors duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                
                                <div class="flex-grow text-center sm:text-right">
                                    <a href="{{ route('shop.show', $item->product->slug) }}" class="text-lg font-bold text-gray-800 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 mb-1 inline-block transition-colors duration-300">{{ $item->product->title }}</a>
                                    <p class="text-gray-500 dark:text-gray-400 mb-3 text-sm font-medium transition-colors duration-300">{{ __('السعر:') }} {{ number_format($item->price, 2) }} $</p>
                                    <div class="flex items-center justify-center sm:justify-start gap-3">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full pl-1 pr-3 py-1 shadow-sm transition-colors duration-300">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-14 text-center border-none bg-transparent focus:ring-0 text-sm font-bold text-gray-700 dark:text-gray-200 py-1 transition-colors duration-300">
                                            <button type="submit" class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 px-3 py-1.5 rounded-full font-bold transition-colors duration-300">{{ __('تحديث') }}</button>
                                        </form>
                                        
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors p-2 rounded-full duration-300" title="{{ __('حذف المنتج') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="text-2xl font-black text-gray-900 dark:text-white text-center sm:text-left min-w-[100px] transition-colors duration-300">
                                    {{ number_format($item->price * $item->quantity, 2) }} $
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8 sticky top-28 shadow-sm transition-colors duration-300">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 transition-colors duration-300">{{ __('ملخص الطلب') }}</h3>
                    
                    <div class="flex justify-between text-gray-600 dark:text-gray-400 mb-4 font-medium transition-colors duration-300">
                        <span>{{ __('المجموع الفرعي') }}</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ number_format($total, 2) }} $</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700 font-medium transition-colors duration-300">
                        <span>{{ __('الضرائب (0%)') }}</span>
                        <span class="text-gray-900 dark:text-gray-100">0.00 $</span>
                    </div>
                    
                    <div class="flex justify-between text-2xl font-black text-gray-900 dark:text-white mb-8 transition-colors duration-300">
                        <span>{{ __('الإجمالي') }}</span>
                        <span class="text-primary-600 dark:text-primary-400">{{ number_format($total, 2) }} $</span>
                    </div>
                    
                    <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary-600 text-white font-bold py-4 rounded-full hover:bg-primary-700 hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                        {{ __('إتمام الطلب') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
