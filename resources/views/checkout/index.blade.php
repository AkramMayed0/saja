@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <div class="flex items-center gap-3 mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('إتمام الطلب') }}</h2>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Shipping Details Form -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-200 dark:border-gray-700 p-8 md:p-10 transition-colors duration-300">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    {{ __('بيانات الشحن') }}
                </h3>
                
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('الاسم الكامل') }}</label>
                            <input type="text" name="name" id="name" required class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('رقم الهاتف') }}</label>
                            <input type="text" name="phone" id="phone" required class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="city" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('المدينة / المحافظة') }}</label>
                        <input type="text" name="city" id="city" required class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-full py-3 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                    </div>
                    
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('العنوان التفصيلي') }}</label>
                        <textarea name="address" id="address" rows="3" required class="w-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800 focus:border-primary-500 focus:ring-primary-500 rounded-[1.5rem] py-4 px-5 shadow-sm transition-colors duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                    </div>

                    <!-- Map for precise location -->
                    <div class="mb-10">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('حدد موقعك الدقيق على الخريطة (اختياري)') }}</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('يساعدنا تحديد موقعك على الخريطة في إيصال الطلب لك بشكل أسرع وأدق. انقر على الخريطة لوضع الدبوس.') }}</p>
                        <div id="checkout-map" class="w-full h-64 rounded-2xl bg-gray-100 border border-gray-200 dark:border-gray-700 z-10 relative"></div>
                        <input type="hidden" name="shipping_lat" id="shipping_lat">
                        <input type="hidden" name="shipping_lng" id="shipping_lng">
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-600 text-white font-bold py-4 rounded-full shadow-md hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transform hover:-translate-y-0.5 transition-all duration-300 text-lg flex justify-center items-center gap-2">
                        <span>{{ __('تأكيد الطلب والدفع') }}</span>
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="w-full lg:w-1/3">
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8 sticky top-28 transition-colors duration-300">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </span>
                    {{ __('مراجعة المنتجات') }}
                </h3>
                
                <ul class="divide-y divide-gray-200 dark:divide-gray-700 mb-6 transition-colors duration-300">
                    @foreach($cart->cartItems as $item)
                        <li class="py-5 flex justify-between items-center group">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $item->product->title }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium transition-colors">{{ __('الكمية') }}: {{ $item->quantity }}</span>
                            </div>
                            <span class="font-extrabold text-primary-600 dark:text-primary-400 whitespace-nowrap bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-full text-sm">{{ number_format($item->price * $item->quantity, 2) }} $</span>
                        </li>
                    @endforeach
                </ul>
                
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 transition-colors duration-300 mt-2">
                    <div class="flex justify-between items-center text-2xl font-black text-gray-900 dark:text-white">
                        <span>{{ __('الإجمالي') }}</span>
                        <span class="text-primary-600 dark:text-primary-400">{{ number_format($total, 2) }} $</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof L === 'undefined') return;
        
        const map = L.map('checkout-map').setView([15.3694, 44.1910], 13); // Default Sanaa
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = null;

        // Try to get user's current location to start
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                setMarker(lat, lng);
            }, function() {
                // Ignore errors
            });
        }

        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
        });

        function setMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
            document.getElementById('shipping_lat').value = lat;
            document.getElementById('shipping_lng').value = lng;
        }

        // Fix map size issues if inside flex/hidden containers
        setTimeout(() => { map.invalidateSize(); }, 500);
    });
</script>
@endpush
