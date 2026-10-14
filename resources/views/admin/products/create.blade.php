@extends('layouts.admin')

@section('title', __('إضافة منتج جديد'))

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-3.5 py-2 rounded-xl transition-colors shadow-2xs">
            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            <span>{{ __('العودة لقائمة المنتجات') }}</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('إضافة منتج أو كتاب جديد') }}</h2>
            <p class="text-xs text-slate-400 mt-1">{{ __('أدخل بيانات الكتاب أو المادة التعليمية (كتاب ورقي مطبوع، أو تسجيلات صوتية MP3)') }}</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Group 1: Basic Info & Type -->
            <div>
                <h3 class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-4">
                    {{ __('البيانات الأساسية وتصنيف المنتج') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('عنوان الكتاب أو المادة التعليمية *') }}</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" 
                               placeholder="{{ __('مثال: سلسلة الحوسبة والذكاء الاصطناعي 1 أو English Friends Audio 1') }}" required>
                        @error('title')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('القسم الدراسي *') }}</label>
                        <select id="category_id" name="category_id" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer" required>
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>{{ __('-- اختر القسم الدراسي --') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
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
                                <option value="{{ $b->id }}" {{ old('bundle_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->category->name ?? '' }})</option>
                            @endforeach
                        </select>
                        @error('bundle_id')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('المستوى التعليمي / الصف *') }}</label>
                        <input type="text" id="level" name="level" value="{{ old('level') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" 
                               placeholder="{{ __('مثال: 1, 2, KG1, تمهيدي...') }}" required>
                        @error('level')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Book Type (with prominent MP3 / Audio Option) -->
                    <div>
                        <label for="book_type" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('نوع المحتوى / الكتاب *') }}</label>
                        <select id="book_type" name="book_type" onchange="handleTypeChange(this.value)"
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer" required>
                            <option value="student" {{ old('book_type', 'student') == 'student' ? 'selected' : '' }}>{{ __('كتاب الحصة / الطالب (Student Book)') }}</option>
                            <option value="workbook" {{ old('book_type') == 'workbook' ? 'selected' : '' }}>{{ __('كتاب التمارين والأنشطة (Workbook)') }}</option>
                            <option value="combined" {{ old('book_type') == 'combined' ? 'selected' : '' }}>{{ __('كتاب شامل - حصة وتمارين (Combined)') }}</option>
                            <option value="teacher" {{ old('book_type') == 'teacher' ? 'selected' : '' }}>{{ __('دليل المعلم (Teacher Guide)') }}</option>
                            <option value="flashcard" {{ old('book_type') == 'flashcard' ? 'selected' : '' }}>{{ __('فلاش كارد / بطاقات تعليمية (Flashcards)') }}</option>
                            <option value="audio" {{ old('book_type') == 'audio' ? 'selected' : '' }}>
                                {{ __('ملفات صوتية وتسجيلات استماع (Audio Tracks - MP3)') }}
                            </option>
                        </select>
                        @error('book_type')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Format (Select Dropdown instead of plain text) -->
                    <div>
                        <label for="format" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('صيغة المنتج (Format) *') }}</label>
                        <select id="format" name="format" 
                                class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium cursor-pointer">
                            <option value="ورقي" {{ old('format', 'ورقي') == 'ورقي' ? 'selected' : '' }}>{{ __('كتاب ورقي مطبوع (Physical)') }}</option>
                            <option value="Digital Audio (MP3)" {{ old('format') == 'Digital Audio (MP3)' ? 'selected' : '' }}>{{ __('ملف صوتي رقمي - Digital Audio (MP3)') }}</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">{{ __('يتم تحديث الصيغة تلقائياً عند اختيار ملفات MP3 الصوتية.') }}</p>
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
                            <input type="number" step="0.01" id="price_sar" name="price_sar" value="{{ old('price_sar') }}" 
                                   class="w-full text-xs p-3 pe-12 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" 
                                   placeholder="0.00" required>
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
                            <input type="number" step="0.01" id="price_usd" name="price_usd" value="{{ old('price_usd') }}" 
                                   class="w-full text-xs p-3 pe-10 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-bold" 
                                   placeholder="0.00" required>
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
                        <input type="text" id="author" name="author" value="{{ old('author', 'دار سجى للنشر') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                        @error('author')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('دار النشر') }}</label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher', 'مؤسسة سجى المتخصصة') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium">
                        @error('publisher')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('لغة الكتاب / المادة *') }}</label>
                        <input type="text" id="language" name="language" value="{{ old('language', 'Arabic') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium" required>
                        @error('language')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('رقم الـ ISBN الدولي') }}</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" 
                               class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-mono" 
                               placeholder="978-...">
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
                    <label for="image" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('صورة الغلاف (Cover Image)') }}</label>
                    <input type="file" id="image" name="image" 
                           class="w-full text-xs p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 file:me-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 dark:file:bg-primary-950/60 dark:file:text-primary-300 hover:file:bg-primary-100 cursor-pointer" 
                           accept="image/*">
                    <p class="text-[11px] text-slate-400 mt-1">{{ __('يُفضل صورة عالية الدقة بنسبة أبعاد 3:4. يتم ضغطها وتحويلها إلى WebP تلقائياً.') }}</p>
                    @error('image')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Sample PDF Preview -->
                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-700/60">
                    <label for="sample_pdf" class="block mb-1 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>{{ __('عينة مقتطفات الكتاب (PDF Sample) - اختياري') }}</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mb-2">
                        {{ __('ارفع ملف PDF يضم أول 10-15 صفحة لمعاينتها كعينة مجانية للزوار. في حال كان المنتج ملفاً صوتياً MP3 يمكنك ترك هذا الحقل فارغاً.') }}
                    </p>
                    <input type="file" id="sample_pdf" name="sample_pdf" 
                           class="w-full text-xs p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 file:me-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 dark:file:bg-slate-800 dark:file:text-slate-300 cursor-pointer" 
                           accept="application/pdf">
                    @error('sample_pdf')<p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block mb-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">{{ __('الوصف والنبذة التعريفية') }}</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full text-xs p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-primary-500 font-medium leading-relaxed" 
                              placeholder="{{ __('نبذة عن محتوى الكتاب، الأهداف التعليمية، أو تفاصيل التسجيلات الصوتية...') }}">{{ old('description') }}</textarea>
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
                    <span>{{ __('حفظ ونشر المنتج') }}</span>
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
