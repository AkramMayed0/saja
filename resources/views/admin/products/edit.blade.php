@extends('layouts.admin')

@section('title', __('تعديل منتج'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:underline flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        {{ __('العودة للمنتجات') }}
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8 max-w-4xl mx-auto">
    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-8">{{ __('تعديل:') }} {{ $product->title }}</h2>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Title -->
            <div>
                <label for="title" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('عنوان الكتاب *') }}</label>
                <input type="text" id="title" name="title" value="{{ old('title', $product->title) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white font-medium" required>
                @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('القسم *') }}</label>
                <select id="category_id" name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium cursor-pointer" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('السعر ($) *') }}</label>
                <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $product->price) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium" required>
                @error('price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Level -->
            <div>
                <label for="level" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('المستوى *') }}</label>
                <input type="text" id="level" name="level" value="{{ old('level', $product->level) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium" placeholder="مثال: 1, 2, Beginner..." required>
                @error('level')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Book Type -->
            <div>
                <label for="book_type" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('نوع الكتاب *') }}</label>
                <select id="book_type" name="book_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium" required>
                    <option value="" {{ old('book_type', $product->book_type) == '' ? 'selected' : '' }}>{{ __('بدون نوع') }}</option>
                    <option value="student" {{ old('book_type', $product->book_type) == 'student' ? 'selected' : '' }}>{{ __('كتاب الحصة') }}</option>
                    <option value="workbook" {{ old('book_type', $product->book_type) == 'workbook' ? 'selected' : '' }}>{{ __('كتاب التمارين') }}</option>
                    <option value="combined" {{ old('book_type', $product->book_type) == 'combined' ? 'selected' : '' }}>{{ __('كتاب حصة وتمارين') }}</option>
                    <option value="teacher" {{ old('book_type', $product->book_type) == 'teacher' ? 'selected' : '' }}>{{ __('دليل المعلم') }}</option>
                    <option value="flashcard" {{ old('book_type', $product->book_type) == 'flashcard' ? 'selected' : '' }}>{{ __('فلاش كارد') }}</option>
                </select>
                @error('book_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <!-- Author -->
            <div>
                <label for="author" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('المؤلف') }}</label>
                <input type="text" id="author" name="author" value="{{ old('author', $product->author) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium">
                @error('author')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Publisher -->
            <div>
                <label for="publisher" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('دار النشر') }}</label>
                <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $product->publisher) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium">
                @error('publisher')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Language -->
            <div>
                <label for="language" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('اللغة *') }}</label>
                <input type="text" id="language" name="language" value="{{ old('language', $product->language) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium" required>
                @error('language')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Format -->
            <div>
                <label for="format" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('صيغة الكتاب (ورقي/الكتروني)') }}</label>
                <input type="text" id="format" name="format" value="{{ old('format', $product->format) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium">
                @error('format')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- ISBN -->
            <div>
                <label for="isbn" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('رقم الـ ISBN') }}</label>
                <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $product->isbn) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium">
                @error('isbn')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('تغيير صورة الغلاف') }}</label>
                <input type="file" id="image" name="image" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-700 dark:text-white cursor-pointer" accept="image/*">
                @if($product->image)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('الصورة الحالية:') }}</p>
                        <img src="{{ asset('storage/' . $product->image) }}" class="h-20 rounded-lg object-cover shadow-sm border border-gray-200 dark:border-gray-700">
                    </div>
                @endif
                @error('image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mb-8">
            <label for="description" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('الوصف') }}</label>
            <textarea id="description" name="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-900 dark:border-gray-700 dark:text-white font-medium">{{ old('description', $product->description) }}</textarea>
            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('admin.products.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 font-bold py-3 px-6 rounded-xl transition-colors">
                {{ __('إلغاء') }}
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm hover:shadow-md transition-all">
                {{ __('تحديث المنتج') }}
            </button>
        </div>
    </form>
</div>
@endsection
