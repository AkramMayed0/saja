@extends('layouts.store')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('courses.index') }}" class="w-10 h-10 bg-white dark:bg-gray-800 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 rounded-full flex items-center justify-center shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">
            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">{{ $course->title }}</h1>
    </div>

    <!-- Video Player Container -->
    <div class="bg-gray-900 rounded-3xl overflow-hidden shadow-2xl mb-8 border border-gray-800 relative w-full" style="padding-top: 56.25%;">
        @if($youtube_id)
            <iframe 
                src="https://www.youtube.com/embed/{{ $youtube_id }}?autoplay=1&rel=0&modestbranding=1" 
                class="absolute top-0 right-0 left-0 bottom-0 w-full h-full"
                title="{{ $course->title }}" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
            </iframe>
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
                <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-lg font-bold">{{ __('رابط الفيديو غير صالح') }}</p>
            </div>
        @endif
    </div>

    <!-- Course Details -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 font-bold">
                <span class="flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('تاريخ النشر:') }} {{ $course->created_at->format('Y-m-d') }}
                </span>
            </div>
            
            @if($course->product)
                <a href="{{ route('shop.show', $course->product->slug) }}" class="inline-flex items-center gap-2 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-4 py-2 rounded-xl font-bold hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    {{ __('عرض الكتاب المرتبط') }}
                </a>
            @endif
        </div>

        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('وصف الدرس') }}</h3>
            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                {!! nl2br(e($course->description)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
