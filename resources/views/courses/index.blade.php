@extends('layouts.store')

@section('content')
    <x-page-header :title="__('تدريب المعلمين')" :subtitle="__('دورات وشروحات تدريبية للمعلمين لشرح المناهج والكتب التعليمية بأفضل الطرق التربوية الحديثة.')" />
    
    <div class="container-x py-10">

        @forelse($groupedCourses as $categoryName => $courses)
            <div class="mb-16 animate-fade-up" style="animation-delay: .1s">
                <div class="mb-8 flex items-center gap-3 border-b border-gray-100 pb-4 dark:border-gray-800/60">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                    <h2 class="font-display text-2xl font-bold text-gray-900 dark:text-white">{{ $categoryName }}</h2>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($courses as $course)
                        @php
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $course->youtube_url, $match);
                            $youtube_id = $match[1] ?? null;
                            $thumbnail = $youtube_id ? "https://img.youtube.com/vi/{$youtube_id}/maxresdefault.jpg" : asset('images/default-course.jpg');
                        @endphp
                        
                        <article data-tilt class="tilt group/card relative flex h-full flex-col overflow-hidden rounded-3xl border border-gray-200/80 bg-white shadow-soft transition-all duration-300 hover:shadow-lift dark:border-gray-800 dark:bg-gray-900">
                            <!-- Thumbnail -->
                            <a href="{{ route('courses.show', $course) }}" class="relative block aspect-video w-full shrink-0 overflow-hidden bg-gray-100 dark:bg-gray-800" aria-label="{{ $course->title }}">
                                <img src="{{ $thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover/card:scale-105">
                                <div class="absolute inset-0 flex items-center justify-center bg-gray-900/20 opacity-0 backdrop-blur-[2px] transition-all duration-300 group-hover/card:opacity-100">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-600 text-white shadow-lg transition-transform duration-300 group-hover/card:scale-110">
                                        <svg class="h-6 w-6 translate-x-[1px]" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="flex flex-grow flex-col p-5">
                                @if($course->product)
                                    <span class="mb-3 inline-block w-fit rounded-full bg-primary-50 px-2.5 py-1 text-[11px] font-bold text-primary-700 dark:bg-primary-900/20 dark:text-primary-300">
                                        {{ $course->product->title }}
                                    </span>
                                @endif

                                <a href="{{ route('courses.show', $course) }}" class="block">
                                    <h3 class="line-clamp-2 min-h-[3rem] font-display text-lg font-bold leading-6 text-gray-900 transition-colors group-hover/card:text-primary-700 dark:text-white dark:group-hover/card:text-primary-300">
                                        {{ $course->title }}
                                    </h3>
                                </a>
                                
                                <p class="mt-2 line-clamp-2 flex-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $course->description }}
                                </p>

                                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500">{{ $course->created_at->format('Y-m-d') }}</span>
                                    <a href="{{ route('courses.show', $course) }}" class="flex items-center gap-1.5 text-sm font-bold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ __('شاهد الدورة') }}
                                        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <span class="tilt-glare" aria-hidden="true"></span>
                        </article>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-20 text-center animate-fade-up" style="animation-delay:.16s">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800/50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-400 border border-gray-200/50 dark:border-gray-700/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path></svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('لا توجد دورات حالياً') }}</h3>
                <p class="text-gray-500 dark:text-gray-400">{{ __('سيتم إضافة شروحات للمناهج والدورات التدريبية قريباً، يرجى العودة لاحقاً.') }}</p>
            </div>
        @endforelse
    </div>
@endsection
