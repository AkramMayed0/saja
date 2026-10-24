@extends('layouts.admin')

@section('title', __('تعديل المنتج') . ' - ' . $product->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-3.5 py-2 rounded-xl transition-colors shadow-2xs">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            <span>{{ __('العودة لقائمة المنتجات') }}</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku ?? ('#' . $product->id) }}</span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('تعديل المنتج:') }} {{ $product->title }}</h2>
            <p class="text-xs text-slate-400 mt-1">{{ __('تحديث مواصفات وأسعار ومرفقات الكتاب أو المادة التعليمية') }}</p>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Group 1: Basic Info & Type -->
            <div>
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('البيانات الأساسية وتصنيف المنتج') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('عنوان الكتاب أو المادة التعليمية *') }}</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $product->title) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" required>
                        @error('title')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('القسم الدراسي *') }}</label>
                        <select id="category_id" name="category_id" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer" required>
                            @php
                                $parentCats = $categories->whereNull('parent_id');
                            @endphp
                            @if($parentCats->isNotEmpty() && $categories->whereNotNull('parent_id')->isNotEmpty())
                                @foreach($parentCats as $pCat)
                                    @if($pCat->children && $pCat->children->isNotEmpty())
                                        <optgroup label="📁 {{ $pCat->name }}">
                                            @foreach($pCat->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $pCat->id }}" {{ old('category_id', $product->category_id) == $pCat->id ? 'selected' : '' }}>{{ $pCat->name }}</option>
                                    @endif
                                @endforeach
                                @foreach($categories->whereNotNull('parent_id')->whereNotIn('parent_id', $parentCats->pluck('id')) as $standalone)
                                    <option value="{{ $standalone->id }}" {{ old('category_id', $product->category_id) == $standalone->id ? 'selected' : '' }}>{{ $standalone->name }}</option>
                                @endforeach
                            @else
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Bundle -->
                    <div>
                        <label for="bundle_id" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('الباقة التابع لها (Curriculum Bundle)') }}</label>
                        <select id="bundle_id" name="bundle_id" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                            <option value="">{{ __('بدون باقة (منتج مستقل)') }}</option>
                            @foreach($bundles as $b)
                                <option value="{{ $b->id }}" {{ old('bundle_id', $product->bundle_id) == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->category->name ?? '' }})</option>
                            @endforeach
                        </select>
                        @error('bundle_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('المستوى التعليمي / الصف *') }}</label>
                        <input type="text" id="level" name="level" value="{{ old('level', $product->level) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" required>
                        @error('level')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Book Type -->
                    <div>
                        <label for="book_type" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('نوع المحتوى / الكتاب *') }}</label>
                        <select id="book_type" name="book_type" onchange="handleTypeChange(this.value)"
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer" required>
                            <option value="student" {{ old('book_type', $product->book_type) == 'student' ? 'selected' : '' }}>{{ __('كتاب الحصة / الطالب (Student Book)') }}</option>
                            <option value="workbook" {{ old('book_type', $product->book_type) == 'workbook' ? 'selected' : '' }}>{{ __('كتاب التمارين والأنشطة (Workbook)') }}</option>
                            <option value="combined" {{ old('book_type', $product->book_type) == 'combined' ? 'selected' : '' }}>{{ __('كتاب شامل - حصة وتمارين (Combined)') }}</option>
                            <option value="teacher" {{ old('book_type', $product->book_type) == 'teacher' ? 'selected' : '' }}>{{ __('دليل المعلم (Teacher Guide)') }}</option>
                            <option value="flashcard" {{ old('book_type', $product->book_type) == 'flashcard' ? 'selected' : '' }}>{{ __('فلاش كارد / بطاقات تعليمية (Flashcards)') }}</option>
                            <option value="audio" {{ old('book_type', $product->book_type) == 'audio' ? 'selected' : '' }}>
                                {{ __('ملفات صوتية وتسجيلات استماع (Audio Tracks - MP3)') }}
                            </option>
                        </select>
                        @error('book_type')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Format -->
                    <div>
                        <label for="format" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('صيغة المنتج (Format) *') }}</label>
                        @php
                            $currentFormat = old('format', $product->format);
                        @endphp
                        <select id="format" name="format" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                            <option value="ورقي" {{ $currentFormat == 'ورقي' || empty($currentFormat) || !str_contains(strtolower($currentFormat ?? ''), 'mp3') ? 'selected' : '' }}>{{ __('كتاب ورقي مطبوع (Physical)') }}</option>
                            <option value="Digital Audio (MP3)" {{ str_contains(strtolower($currentFormat ?? ''), 'mp3') || $currentFormat == 'Digital Audio (MP3)' ? 'selected' : '' }}>{{ __('ملف صوتي رقمي - Digital Audio (MP3)') }}</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">{{ __('يتم تحديث الصيغة تلقائياً عند تغيير نوع المحتوى إلى صوتي MP3.') }}</p>
                        @error('format')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Group 2: Pricing -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('الأسعار والعملات') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Price SAR -->
                    <div>
                        <label for="price_sar" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('السعر داخل اليمن (ريال سعودي - ر.س) *') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" id="price_sar" name="price_sar" value="{{ old('price_sar', $product->price_sar ?? $product->price) }}" 
                                   class="w-full text-xs p-3 pe-12 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" required>
                            <span class="absolute end-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">ر.س</span>
                        </div>
                        @error('price_sar')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Price USD -->
                    <div>
                        <label for="price_usd" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ __('السعر خارج اليمن (دولار أمريكي - $) *') }}
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" id="price_usd" name="price_usd" value="{{ old('price_usd', $product->price_usd ?? round($product->price / 3.75, 2)) }}" 
                                   class="w-full text-xs p-3 pe-10 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" required>
                            <span class="absolute end-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">$</span>
                        </div>
                        @error('price_usd')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Group 3: Publishing Info -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('بيانات النشر والمواصفات') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Author -->
                    <div>
                        <label for="author" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('المؤلف / الفريق العلمي') }}</label>
                        <input type="text" id="author" name="author" value="{{ old('author', $product->author) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                        @error('author')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('دار النشر') }}</label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $product->publisher) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                        @error('publisher')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('لغة الكتاب / المادة *') }}</label>
                        <input type="text" id="language" name="language" value="{{ old('language', $product->language) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" required>
                        @error('language')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('رقم الـ ISBN الدولي') }}</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $product->isbn) }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-mono">
                        @error('isbn')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Group 4: Assets & Description -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('الصور والمرفقات والوصف') }}
                </h3>

                <!-- Cover Image -->
                <div>
                    <label for="image" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('تغيير صورة الغلاف') }}</label>
                    <input type="file" id="image" name="image" 
                           class="w-full text-xs p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 file:me-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-950/60 dark:file:text-primary-300 hover:file:bg-primary-100 cursor-pointer" 
                           accept="image/*">
                    @if($product->image)
                        <div class="mt-2.5 flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200/60 dark:border-slate-700 w-fit">
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-12 w-9 rounded-md object-cover shadow-2xs">
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('الصورة الحالية للغلاف') }}</span>
                        </div>
                    @endif
                    @error('image')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Sample PDF Preview -->
                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-700/60">
                    <label for="sample_pdf" class="block mb-1 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>{{ __('عينة مقتطفات الكتاب (PDF Sample) - اختياري') }}</span>
                    </label>
                    
                    <input type="file" id="sample_pdf" name="sample_pdf" 
                           class="w-full text-xs p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 file:me-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 cursor-pointer" 
                           accept="application/pdf">
                    
                    @if($product->sample_pdf)
                        <div class="mt-3 flex items-center justify-between p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 rounded text-[11px] font-bold font-mono">PDF</span>
                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate max-w-xs">{{ basename($product->sample_pdf) }}</span>
                                <a href="{{ asset('storage/' . $product->sample_pdf) }}" target="_blank" class="text-xs text-primary-600 dark:text-primary-400 hover:underline font-bold ms-2">
                                    {{ __('معاينة') }} ↗
                                </a>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 cursor-pointer font-bold">
                                <input type="checkbox" name="remove_sample_pdf" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>{{ __('حذف العينة') }}</span>
                            </label>
                        </div>
                    @endif
                    @error('sample_pdf')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('الوصف والنبذة التعريفية') }}</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium leading-relaxed">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ __('حفظ التعديلات') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleTypeChange(val) {
        const formatSelect = document.getElementById('format');
        if (val === 'audio') {
            formatSelect.value = 'Digital Audio (MP3)';
        } else if (formatSelect.value === 'Digital Audio (MP3)') {
            formatSelect.value = 'ورقي';
        }
    }
</script>
@endsection
