@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center w-full transition-colors duration-300">
        
        {{-- نسخة الجوال --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 cursor-default rounded-full transition-colors duration-300">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 dark:hover:border-primary-800/50 transition-colors duration-300">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 dark:hover:border-primary-800/50 transition-colors duration-300">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 cursor-default rounded-full transition-colors duration-300">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- نسخة الكمبيوتر --}}
        <div class="hidden sm:flex-1 sm:flex sm:flex-col sm:items-center sm:justify-center gap-5">
            
            {{-- القسم الأول: أزرار الصفحات (الدوائر) --}}
            <div class="flex justify-center">
                <span class="relative z-0 inline-flex rounded-full gap-2 transition-colors duration-300">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="w-10 h-10 inline-flex items-center justify-center text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 cursor-default rounded-full transition-colors duration-300" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 inline-flex items-center justify-center text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-300 dark:hover:border-primary-700 transition-colors duration-300" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="w-10 h-10 inline-flex items-center justify-center text-sm font-medium text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 cursor-default rounded-full transition-colors duration-300">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="w-10 h-10 inline-flex items-center justify-center text-sm font-black text-white bg-primary-600 dark:bg-primary-500 border border-transparent cursor-default rounded-full shadow-md shadow-primary-600/30 transition-all duration-300">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-10 h-10 inline-flex items-center justify-center text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-300 dark:hover:border-primary-700 rounded-full transition-colors duration-300">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 inline-flex items-center justify-center text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-300 dark:hover:border-primary-700 transition-colors duration-300" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="w-10 h-10 inline-flex items-center justify-center text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 cursor-default rounded-full transition-colors duration-300" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>

            {{-- القسم الثاني: النص التوضيحي (تحت الدوائر) --}}
            <div class="flex justify-center text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-5 transition-colors duration-300">
                    {{ __('إظهار') }}
                    <span class="font-bold text-gray-800 dark:text-gray-100">{{ $paginator->firstItem() }}</span>
                    {{ __('إلى') }}
                    <span class="font-bold text-gray-800 dark:text-gray-100">{{ $paginator->lastItem() }}</span>
                    {{ __('من') }}
                    <span class="font-bold text-gray-800 dark:text-gray-100">{{ $paginator->total() }}</span>
                    {{ __('نتائج') }}
                </p>
            </div>

        </div>
    </nav>
@endif
