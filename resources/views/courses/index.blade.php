@extends('layouts.store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 transition-colors duration-300">
    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('تدريب المعلمين') }}</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">{{ __('دورات وشروحات تدريبية للمعلمين لشرح المناهج والكتب التعليمية بأفضل الطرق التربوية الحديثة.') }}</p>
    </div>

    @forelse($groupedCourses as $categoryName => $courses)
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 p-2 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                    {{ $categoryName }}
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    @php
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $course->youtube_url, $match);
                        $youtube_id = $match[1] ?? null;
                        $thumbnail = $youtube_id ? "https://img.youtube.com/vi/{$youtube_id}/maxresdefault.jpg" : asset('images/default-course.jpg');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600 transition-colors duration-300 relative text-right h-full group/card">
                        
                        <!-- مساحة الصورة -->
                        <a href="{{ route('courses.show', $course) }}" class="block w-full aspect-video shrink-0 bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 relative overflow-hidden transition-colors duration-300">
                            <img src="{{ $thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-105">
                            <div class="absolute inset-0 bg-black/20 group-hover/card:bg-black/10 transition-colors duration-300 flex items-center justify-center">
                                <div class="w-12 h-12 bg-red-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-sm shadow-lg group-hover/card:scale-110 group-hover/card:bg-red-600 transition-all duration-300">
                                    <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </a>

                        <!-- المحتوى -->
                        <div class="p-5 flex flex-col flex-grow text-right">
                            
                            <!-- التاغات -->
                            @if($course->product)
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold px-3.5 py-1.5 rounded-full transition-colors duration-300">{{ $course->product->title }}</span>
                            </div>
                            @endif

                            <!-- العنوان -->
                            <a href="{{ route('courses.show', $course) }}">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 line-clamp-2 mb-2 transition-colors duration-300">{{ $course->title }}</h3>
                            </a>
                            
                            <!-- الوصف -->
                            <p class="text-gray-500 dark:text-gray-400 text-sm line-clamp-2 mb-4 flex-1">
                                {{ $course->description }}
                            </p>

                            <!-- السعر وزر الإضافة في الأسفل -->
                            <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-100 dark:border-gray-700 transition-colors duration-300">
                                <span class="text-gray-400 dark:text-gray-500 text-xs font-medium">{{ $course->created_at->format('Y-m-d') }}</span>
                                
                                <a href="{{ route('courses.show', $course) }}" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 dark:hover:border-primary-800 font-bold py-2 px-5 rounded-full transition-colors duration-300 shadow-sm flex items-center gap-1.5 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span class="text-sm">{{ __('شاهد') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="py-20 text-center">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('لا توجد دورات حالياً') }}</h3>
            <p class="text-gray-500 dark:text-gray-400">{{ __('سيتم إضافة الدورات التدريبية قريباً.') }}</p>
        </div>
    @endforelse
</div>
@endsection
