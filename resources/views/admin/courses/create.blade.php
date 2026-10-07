@extends('layouts.admin')

@section('title', __('إضافة دورة جديدة'))

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.courses.index') }}" class="w-10 h-10 bg-white dark:bg-gray-800 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 rounded-full flex items-center justify-center shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('إضافة دورة جديدة') }}</h2>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('عنوان الدورة') }}</label>
                <input type="text" name="title" id="title" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-2.5 px-4 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
            </div>

            <div>
                <label for="youtube_url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('رابط يوتيوب (Youtube URL)') }}</label>
                <input type="url" name="youtube_url" id="youtube_url" dir="ltr" placeholder="https://www.youtube.com/watch?v=..." class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-2.5 px-4 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500" required>
            </div>

            <div>
                <label for="product_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('المنتج المرتبط (اختياري)') }}</label>
                <select name="product_id" id="product_id" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-2.5 px-4 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    <option value="">{{ __('بدون ارتباط (عامة)') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('وصف الدورة أو الدرس') }}</label>
                <textarea name="description" id="description" rows="5" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 py-3 px-4 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-sm transition-colors text-center">
                    {{ __('حفظ ونشر الدورة') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
