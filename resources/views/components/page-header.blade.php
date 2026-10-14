@props(['title', 'subtitle' => null, 'eyebrow' => null, 'crumbs' => []])

<section class="relative overflow-hidden border-b border-gray-200/70 bg-white dark:border-gray-800 dark:bg-gray-950">
    <div class="absolute inset-0 bg-grid mask-fade-b" aria-hidden="true"></div>
    <div class="absolute -top-24 start-1/4 h-64 w-64 rounded-full bg-primary-400/20 blur-3xl dark:bg-primary-600/15" aria-hidden="true"></div>
    <div class="absolute -top-10 end-1/4 h-52 w-52 rounded-full bg-leaf-400/15 blur-3xl dark:bg-leaf-600/10" aria-hidden="true"></div>

    <div class="container-x relative py-12 sm:py-16">
        <nav aria-label="{{ __('مسار التنقل') }}" class="mb-5 animate-fade-up">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary-600 dark:hover:text-primary-400">{{ __('الرئيسية') }}</a></li>
                @foreach($crumbs as $label => $url)
                    <li aria-hidden="true"><svg class="h-3.5 w-3.5 opacity-50 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></li>
                    <li><a href="{{ $url }}" class="transition-colors hover:text-primary-600 dark:hover:text-primary-400">{{ $label }}</a></li>
                @endforeach
                <li aria-hidden="true"><svg class="h-3.5 w-3.5 opacity-50 ltr:-scale-x-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></li>
                <li class="font-semibold text-gray-900 dark:text-white" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>

        @if($eyebrow)
            <span class="eyebrow mb-4 animate-fade-up">{{ $eyebrow }}</span>
        @endif
        <h1 class="font-display text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl animate-fade-up" style="animation-delay:.06s">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-4 max-w-2xl text-lg leading-8 text-gray-500 dark:text-gray-400 animate-fade-up" style="animation-delay:.12s">{{ $subtitle }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
