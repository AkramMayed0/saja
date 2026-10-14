@props(['title', 'subtitle' => null])

@php
    $legalLinks = [
        ['route' => 'pages.privacy', 'label' => __('سياسة الخصوصية'), 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],
        ['route' => 'pages.shipping', 'label' => __('الشحن والإرجاع'), 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12'],
        ['route' => 'pages.terms', 'label' => __('الشروط والأحكام'), 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
    ];
@endphp

<x-page-header :title="$title" :subtitle="$subtitle" :eyebrow="__('السياسات')" />

<div class="container-x py-14">
    <div class="grid gap-8 lg:grid-cols-12">
        <aside class="lg:col-span-3">
            <nav class="card p-2 lg:sticky lg:top-28" aria-label="{{ __('السياسات') }}">
                @foreach($legalLinks as $link)
                    @php $active = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" @if($active) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition-colors {{ $active ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="h-5 w-5 {{ $active ? '' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/></svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <article data-reveal class="card p-7 sm:p-10 lg:col-span-9">
            <div class="prose-saja">
                {{ $slot }}
            </div>
        </article>
    </div>
</div>
