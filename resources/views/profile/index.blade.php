@extends('layouts.store')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 tracking-tight transition-colors duration-300">{{ __('حسابي') }}</h2>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Account Settings -->
        <div class="w-full lg:w-1/3">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 p-8 sticky top-28 shadow-sm transition-colors duration-300">
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2 transition-colors duration-300">
                    <span class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    {{ __('إعدادات الحساب') }}
                </h3>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('الاسم الكامل') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-full focus:border-primary-500 focus:ring-primary-500 py-3 px-5 transition-colors duration-300">
                        @error('name')
                            <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">{{ __('البريد الإلكتروني') }} <span class="text-xs text-gray-400 font-normal">({{ __('لا يمكن تغييره') }})</span></label>
                        <input type="email" id="email" value="{{ $user->email }}" readonly disabled class="w-full border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-full py-3 px-5 transition-colors duration-300 cursor-not-allowed">
                    </div>

                    <div class="mb-5 border-t border-gray-200 dark:border-gray-700 pt-8 mt-8">
                        <h4 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-6">{{ __('تغيير كلمة المرور') }} <span class="text-xs text-gray-400 font-normal">({{ __('اختياري') }})</span></h4>
                        
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('كلمة المرور الحالية') }}</label>
                            <input type="password" name="current_password" id="current_password" class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-full focus:border-primary-500 focus:ring-primary-500 py-3 px-5 transition-colors duration-300">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('كلمة المرور الجديدة') }}</label>
                            <input type="password" name="password" id="password" class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-full focus:border-primary-500 focus:ring-primary-500 py-3 px-5 transition-colors duration-300">
                            @error('password')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('تأكيد كلمة المرور') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-full focus:border-primary-500 focus:ring-primary-500 py-3 px-5 transition-colors duration-300">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3.5 rounded-full hover:bg-primary-700 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 shadow-sm text-sm">
                        {{ __('حفظ التعديلات') }}
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Order History -->
        <div class="w-full lg:w-2/3">
            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-2 transition-colors duration-300">
                <span class="text-primary-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </span>
                {{ __('تاريخ الطلبات') }}
            </h3>
            
            @forelse($orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 hover:shadow-md transition-all duration-300">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 dark:border-gray-700 pb-4 mb-4 transition-colors duration-300">
                        <div>
                            <span class="text-gray-400 dark:text-gray-500 text-xs font-bold bg-gray-50 dark:bg-gray-900 px-2 py-1 rounded-full mb-2 inline-block transition-colors duration-300">{{ __('طلب رقم') }}</span>
                            <h4 class="font-extrabold text-lg text-gray-900 dark:text-white mb-1 transition-colors duration-300"><a href="{{ route('orders.show', $order) }}" class="hover:text-primary-600 hover:underline">#{{ $order->id }}</a></h4>
                            <span class="text-sm text-gray-400 font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="mt-4 sm:mt-0 text-right">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-orange-50 text-orange-600 border-orange-100 dark:bg-orange-900/30 dark:border-orange-800/50 dark:text-orange-400',
                                    'processing' => 'bg-primary-50 text-primary-600 border-primary-100 dark:bg-primary-900/30 dark:border-primary-800/50 dark:text-primary-400',
                                    'completed' => 'bg-green-50 text-green-600 border-green-100 dark:bg-green-900/30 dark:border-green-800/50 dark:text-green-400',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:border-red-800/50 dark:text-red-400',
                                ];
                                $statusLabels = [
                                    'pending' => __('قيد الانتظار'),
                                    'processing' => __('قيد التجهيز'),
                                    'completed' => __('مكتمل'),
                                    'cancelled' => __('ملغي'),
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold inline-block mb-2 border {{ $statusColors[$order->status] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                @if($order->delivery_status === 'assigned')
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold inline-block mb-2 border bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50">
                                        {{ __('تم تعيين المندوب') }}
                                    </span>
                                @elseif($order->delivery_status === 'out_for_delivery')
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold inline-block mb-2 border bg-indigo-50 text-indigo-600 border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/50">
                                        {{ __('في الطريق إليك') }}
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold inline-block mb-2 border bg-gray-50 text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                        {{ __('بانتظار المندوب') }}
                                    </span>
                                @endif
                            @endif
                            <div class="font-black text-primary-600 dark:text-primary-400 text-xl transition-colors duration-300">{{ $order->formatted_total }}</div>
                        </div>
                    </div>
                    
                    <ul class="divide-y divide-gray-50 dark:divide-gray-700 transition-colors duration-300">
                        @foreach($order->items as $item)
                            <li class="py-4 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 border border-primary-100 dark:border-primary-800/50 flex items-center justify-center rounded-full font-bold transition-colors duration-300">
                                        {{ $item->quantity }}x
                                    </div>
                                    <span class="font-bold text-gray-700 dark:text-gray-200 transition-colors duration-300">{{ $item->product->title ?? __('منتج غير متاح') }}</span>
                                </div>
                                <span class="font-bold text-gray-600 dark:text-gray-300 transition-colors duration-300">{{ number_format($item->price, 2) }} {{ $order->currency_symbol ?? ($order->currency === 'USD' ? '$' : 'ر.س') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 {{ $order->items->isNotEmpty() ? 'border-t border-gray-100 pt-4 dark:border-gray-700' : '' }}">
                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-5 py-2 text-sm font-bold text-primary-700 transition-colors hover:bg-primary-100 dark:border-primary-800/50 dark:bg-primary-900/30 dark:text-primary-300 dark:hover:bg-primary-900/50">
                            {{ __('عرض التفاصيل') }}
                            <svg class="h-4 w-4 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                        </a>

                        @if($order->canBeCancelledByCustomer())
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm(@js(__('هل أنت متأكد من إلغاء هذا الطلب؟')))">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-2 text-sm font-bold text-red-600 transition-colors hover:bg-red-100 dark:border-red-800/50 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    {{ __('إلغاء الطلب') }}
                                </button>
                            </form>
                        @elseif(in_array($order->status, ['pending', 'processing']))
                            <p class="basis-full text-xs font-medium text-gray-400">{{ __('لا يمكن إلغاء الطلب بعد بدء التجهيز أو التوصيل. للمساعدة يرجى التواصل معنا.') }}</p>
                        @endif
                    </div>

                    @if($order->delivery_status === 'out_for_delivery' || $order->delivery_status === 'assigned')
                        <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                                </span>
                                {{ __('تتبع التوصيل المباشر') }}
                            </h4>
                            
                            <!-- Map Container -->
                            <div id="map-{{ $order->id }}" class="w-full h-64 rounded-2xl bg-gray-100 border border-gray-200 z-10 relative"></div>
                            
                            <p class="text-xs text-gray-500 mt-2 text-center" id="last-updated-{{ $order->id }}">{{ __('جاري البحث عن المندوب...') }}</p>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    if(typeof L === 'undefined') return;
                                    
                                    const mapId = 'map-{{ $order->id }}';
                                    const map = L.map(mapId).setView([15.3694, 44.1910], 13); // Default Sanaa
                                    
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OpenStreetMap contributors'
                                    }).addTo(map);

                                    // Fix map size issues if inside hidden divs or modals
                                    setTimeout(() => { map.invalidateSize(); }, 500);

                                    let driverMarker = null;

                                    function updateDriverLocation() {
                                        fetch('{{ route('orders.tracking', $order) }}')
                                            .then(res => res.json())
                                            .then(data => {
                                                if(data.lat && data.lng) {
                                                    const latlng = [parseFloat(data.lat), parseFloat(data.lng)];
                                                    
                                                    if(!driverMarker) {
                                                        driverMarker = L.marker(latlng).addTo(map);
                                                        map.setView(latlng, 15);
                                                    } else {
                                                        driverMarker.setLatLng(latlng);
                                                    }

                                                    if(data.last_updated) {
                                                        document.getElementById('last-updated-{{ $order->id }}').innerText = '{{ __('آخر تحديث:') }} ' + data.last_updated;
                                                    }
                                                }
                                            })
                                            .catch(err => console.error("Map fetch error: ", err));
                                    }

                                    // Poll every 3 seconds
                                    updateDriverLocation();
                                    setInterval(updateDriverLocation, 3000);
                                });
                            </script>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-200 dark:border-gray-700 shadow-sm p-16 text-center transition-colors duration-300">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 dark:text-gray-600 transition-colors duration-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">{{ __('لا توجد طلبات سابقة') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors duration-300">{{ __('لم تقم بإجراء أي طلبات حتى الآن.') }}</p>
                </div>
            @endforelse
        </div>
        
    </div>
</div>
@endsection
