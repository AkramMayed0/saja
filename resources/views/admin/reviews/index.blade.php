@extends('layouts.admin')

@section('title', __('إدارة التقييمات والمراجعات'))

@section('content')
<div class="space-y-5">
    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Reviews -->
        <a href="{{ route('admin.reviews.index') }}" class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ __('إجمالي التقييمات') }}</span>
                <span class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ $totalCount }}</div>
        </a>

        <!-- Pending Approval -->
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-amber-300 dark:hover:border-amber-700 transition-colors {{ request('status') === 'pending' ? 'ring-2 ring-amber-500/30' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ __('بانتظار المراجعة والاعتماد') }}</span>
                <span class="p-2 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-black text-amber-600 dark:text-amber-400">{{ $pendingCount }}</div>
        </a>

        <!-- Approved -->
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors {{ request('status') === 'approved' ? 'ring-2 ring-emerald-500/30' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ __('تقييمات معتمدة ومنشورة') }}</span>
                <span class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-2 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $approvedCount }}</div>
        </a>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('طابور مراجعة تقييمات المنتجات') }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ __('مراقبة وتقييم آراء العملاء، واعتمادها للنشر أو إخفائها أو حذف المخالف منها') }}</p>
            </div>
            @if($pendingCount > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/80">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>{{ $pendingCount }} {{ __('تقييم بانتظار قرارك') }}</span>
                </span>
            @endif
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-6 p-3.5 bg-slate-50/70 dark:bg-slate-800/40 rounded-xl border border-slate-200/60 dark:border-slate-700/60 flex flex-col md:flex-row gap-3 items-stretch md:items-center">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('ابحث بنص التعليق، أو اسم العميل، أو المنتج...') }}" 
                       class="w-full text-xs ps-9 pe-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500">
            </div>

            <!-- Status Dropdown -->
            <div class="w-full md:w-44">
                <select name="status" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                    <option value="">{{ __('كل الحالات') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('بانتظار الموافقة') }}</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('معتمدة ومنشورة') }}</option>
                </select>
            </div>

            <!-- Rating Dropdown -->
            <div class="w-full md:w-36">
                <select name="rating" class="w-full text-xs py-2 px-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                    <option value="">{{ __('كل التقييمات') }}</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 {{ __('نجوم') }}</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 {{ __('نجوم') }}</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 {{ __('نجوم') }}</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 {{ __('نجوم') }}</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 {{ __('نجمة') }}</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 rounded-xl transition-colors shadow-2xs">
                    {{ __('تصفية') }}
                </button>
                @if(request('search') || request('status') || request('rating'))
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 hover:bg-slate-100 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors shrink-0">
                        {{ __('إلغاء') }}
                    </a>
                @endif
            </div>
        </form>

        <!-- Moderation Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-right min-w-[850px] text-sm">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                        <th class="pb-3 font-semibold">{{ __('المنتج') }}</th>
                        <th class="pb-3 font-semibold">{{ __('العميل') }}</th>
                        <th class="pb-3 font-semibold">{{ __('التقييم') }}</th>
                        <th class="pb-3 font-semibold w-2/5">{{ __('نص التعليق / المراجعة') }}</th>
                        <th class="pb-3 font-semibold text-center">{{ __('الحالة') }}</th>
                        <th class="pb-3 font-semibold text-center">{{ __('إجراءات الإشراف') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors {{ !$review->is_approved ? 'bg-amber-50/25 dark:bg-amber-950/10' : '' }}">
                            <!-- Product -->
                            <td class="py-3.5 align-top">
                                @if($review->product)
                                    <div class="flex items-center gap-2.5 max-w-xs">
                                        @if($review->product->cover_image)
                                            <a href="{{ route('admin.products.edit', $review->product->id) }}" class="shrink-0" title="{{ __('تعديل المنتج') }}">
                                                <img src="{{ asset('storage/' . $review->product->cover_image) }}" class="w-9 h-12 object-cover rounded-md shadow-2xs" alt="{{ $review->product->title }}">
                                            </a>
                                        @else
                                            <a href="{{ route('admin.products.edit', $review->product->id) }}" class="w-9 h-12 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-slate-400 shrink-0" title="{{ __('تعديل المنتج') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            </a>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.products.edit', $review->product->id) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 line-clamp-2 leading-snug" title="{{ __('تعديل المنتج') }}">
                                                {{ $review->product->title }}
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[10px] text-slate-400">
                                                    {{ $review->product->category->name ?? '' }}
                                                </span>
                                                <span class="text-slate-300 dark:text-slate-600 text-[10px]">•</span>
                                                <a href="{{ route('shop.show', $review->product->slug ?? $review->product->id) }}" target="_blank" class="text-[10px] text-primary-600 dark:text-primary-400 hover:underline inline-flex items-center gap-0.5 font-medium" title="{{ __('فتح صفحة المنتج في المتجر') }}">
                                                    <span>{{ __('معاينة') }}</span>
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">{{ __('منتج محذوف') }}</span>
                                @endif
                            </td>

                            <!-- User -->
                            <td class="py-3.5 align-top whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ mb_substr($review->user->name ?? '؟', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $review->user->name ?? __('مستخدم غير معروف') }}</div>
                                        <div class="text-[11px] text-slate-400 truncate">{{ $review->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rating -->
                            <td class="py-3.5 align-top whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-slate-200 dark:text-slate-700 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block">
                                        {{ $review->rating }} / 5
                                    </span>
                                </div>
                            </td>

                            <!-- Comment & Date -->
                            <td class="py-3.5 align-top">
                                @if($review->comment)
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed break-words line-clamp-3">
                                        "{{ $review->comment }}"
                                    </p>
                                @else
                                    <span class="text-xs text-slate-400 italic">({{ __('بدون تعليق نصي، تقييم رقمي فقط') }})</span>
                                @endif
                                <div class="mt-1 text-[10px] text-slate-400 font-medium">
                                    {{ $review->created_at->format('Y-m-d H:i') }} ({{ $review->created_at->diffForHumans() }})
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 align-top text-center whitespace-nowrap">
                                @if($review->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>{{ __('معتمد ومنشور') }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ __('بانتظار الموافقة') }}</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 align-top text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Toggle Approval Button -->
                                    <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="inline">
                                        @csrf
                                        @if($review->is_approved)
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/50 hover:bg-amber-100 dark:hover:bg-amber-900/60 border border-amber-200 dark:border-amber-800 transition-all cursor-pointer shadow-2xs active:scale-95"
                                                    title="{{ __('إخفاء التقييم عن العملاء') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                                <span>{{ __('إخفاء') }}</span>
                                            </button>
                                        @else
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 border border-emerald-300 dark:border-emerald-700 transition-all cursor-pointer shadow-2xs active:scale-95"
                                                    title="{{ __('اعتماد ونشر التقييم') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ __('اعتماد') }}</span>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Delete Review Button -->
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التقييم نهائياً؟') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer" title="{{ __('حذف') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                {{ __('لا توجد تقييمات تطابق خيارات التصفية الحالية.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $reviews->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
