@extends('layouts.admin')

@section('title', __('تعديل قسم'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        {{ __('العودة للأقسام') }}
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8 max-w-2xl mx-auto">
    <h2 class="text-xl font-black text-gray-900 dark:text-white mb-6">{{ __('تعديل قسم:') }} {{ $category->name }}</h2>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="name" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('اسم القسم') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white font-medium" required>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="image" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('صورة القسم (اختياري)') }}</label>
            @if($category->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 p-2.5 font-medium">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('اترك هذا الحقل فارغاً إذا لم ترغب بتغيير الصورة الحالية.') }}</p>
            @error('image')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 font-bold py-2.5 px-5 rounded-xl transition-colors">
                {{ __('إلغاء') }}
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm hover:shadow-md transition-all">
                {{ __('حفظ التعديلات') }}
            </button>
        </div>
    </form>
</div>
@endsection
