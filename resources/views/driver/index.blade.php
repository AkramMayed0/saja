@extends('layouts.store')

@section('title', __('تطبيق المندوب - الطلبات'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('طلبات التوصيل الخاصة بي') }}</h2>
        <div id="tracking-status" class="px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
            {{ __('التتبع متوقف') }}
        </div>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between hover:shadow-md transition-all duration-300" id="order-card-{{ $order->id }}">
                <div>
                    <div class="flex justify-between items-start mb-4 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div>
                            <span class="text-xs font-bold bg-gray-50 dark:bg-gray-900 px-2 py-1 rounded-full text-gray-400 dark:text-gray-500">{{ __('طلب رقم') }} #{{ $order->id }}</span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-2">{{ $order->user->name }}</h3>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $order->delivery_status === 'out_for_delivery' ? 'bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/50' : 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50' }}">
                            {{ $order->delivery_status === 'out_for_delivery' ? __('في الطريق إليك') : __('تم تعيين المندوب') }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                        <strong class="text-gray-700 dark:text-gray-300">{{ __('الهاتف:') }}</strong> {{ $order->user->phone ?? __('غير متوفر') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                        <strong class="text-gray-700 dark:text-gray-300">{{ __('العنوان:') }}</strong> {{ $order->shipping_address }}
                    </p>
                    @if($order->shipping_lat && $order->shipping_lng)
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->shipping_lat }},{{ $order->shipping_lng }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 font-bold hover:underline flex items-center gap-1 mb-4 w-fit bg-primary-50 dark:bg-primary-900/30 px-3 py-1.5 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ __('الإبحار للموقع (خرائط جوجل)') }}
                        </a>
                    @else
                        <div class="mb-4"></div>
                    @endif
                    
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 mb-4 border border-gray-100 dark:border-gray-700/50">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </span>
                            {{ __('المنتجات:') }}
                        </h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            @foreach($order->items as $item)
                                <li class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-2 last:border-0 last:pb-0">
                                    <span class="flex items-center gap-2">
                                        <span class="text-primary-600 dark:text-primary-400 font-bold">{{ $item->quantity }}x</span> 
                                        {{ $item->product->title }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="space-y-3 mt-4">
                    @if($order->delivery_status === 'assigned')
                        <form action="{{ route('driver.orders.status', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="delivery_status" value="out_for_delivery">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition-colors">
                                {{ __('بدء التوصيل وتفعيل التتبع') }}
                            </button>
                        </form>
                    @elseif($order->delivery_status === 'out_for_delivery')
                        <button onclick="startTracking({{ $order->id }})" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition-colors mb-2 tracking-btn">
                            {{ __('تحديث الموقع الآن (GPS)') }}
                        </button>
                        <form action="{{ route('driver.orders.status', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="delivery_status" value="delivered">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">
                                {{ __('تم التسليم بنجاح') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-gray-50 dark:bg-gray-800 rounded-[2rem]">
                <p class="text-xl text-gray-500 dark:text-gray-400 font-bold">{{ __('لا توجد طلبات مسندة إليك حالياً.') }}</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    let watchId = null;

    function startTracking(orderId) {
        if (!navigator.geolocation) {
            alert(@json(__('متصفحك لا يدعم خاصية تحديد الموقع.')));
            return;
        }

        const statusBadge = document.getElementById('tracking-status');
        statusBadge.textContent = @json(__('جاري التتبع الحي...'));
        statusBadge.classList.replace('bg-gray-100', 'bg-green-100');
        statusBadge.classList.replace('text-gray-600', 'text-green-600');

        // Watch position continuously
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Send coordinates to the server
                fetch(`/driver/orders/${orderId}/location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ lat: lat, lng: lng })
                }).then(response => response.json())
                  .then(data => console.log('Location updated', data))
                  .catch(error => console.error('Error updating location', error));
            },
            (error) => {
                console.warn('Error getting location', error);
                alert(@json(__('فشل في الوصول لموقعك الحقيقي (قد يكون بسبب استخدام كمبيوتر مكتبي). سيتم تفعيل (وضع المحاكاة) لتجربة الخريطة!')));
                
                // Simulation Mode for Desktop Testing (Sanaa Coordinates)
                let simLat = 15.3483; 
                let simLng = 44.2065;
                
                setInterval(() => {
                    simLat += 0.0002; // Move North
                    simLng += 0.0002; // Move East
                    
                    fetch(`/driver/orders/${orderId}/location`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ lat: simLat, lng: simLng })
                    });
                }, 3000);
            },
            {
                enableHighAccuracy: false,
                maximumAge: 0,
                timeout: 10000
            }
        );
    }
</script>
@endsection
