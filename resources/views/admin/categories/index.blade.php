@extends('layouts.admin')

@section('title', __('إدارة الأقسام'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('الأقسام والتصنيفات') }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('إدارة أقسام وتصنيفات المناهج والكتب والأنشطة') }}</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>{{ __('إضافة قسم جديد') }}</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[700px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('الرقم') }}</th>
                    <th class="pb-3 font-semibold">{{ __('اسم القسم') }}</th>
                    <th class="pb-3 font-semibold">{{ __('نوع القسم / التبعية') }}</th>
                    <th class="pb-3 font-semibold">{{ __('الرابط الدائم (Slug)') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('المحتوى') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors {{ $category->is_parent ? 'bg-slate-50/40 dark:bg-slate-900/40' : '' }}">
                        <td class="py-3.5 text-slate-400 text-xs font-mono">#{{ $category->id }}</td>
                        <td class="py-3.5 font-bold text-slate-900 dark:text-white">
                            <div class="flex items-center gap-2 {{ $category->is_parent ? '' : 'ps-5' }}">
                                @if($category->is_parent)
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary-100 text-primary-700 dark:bg-primary-950 dark:text-primary-300 text-xs font-bold shadow-xs">📁</span>
                                @else
                                    <span class="text-slate-400 font-mono text-sm">↳</span>
                                @endif
                                <span>{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5">
                            @if($category->is_parent)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-50 dark:bg-primary-950/60 text-primary-700 dark:text-primary-300 border border-primary-200/70 dark:border-primary-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                                    {{ __('قسم رئيسي') }} ({{ $category->children->count() }} {{ __('سلاسل') }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs text-slate-600 dark:text-slate-400">
                                    <span class="text-slate-400">{{ __('تابع لـ:') }}</span>
                                    <strong class="font-bold text-slate-800 dark:text-slate-200">{{ $category->parent->name ?? '-' }}</strong>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400 text-xs font-mono" dir="ltr">{{ $category->slug }}</td>
                        <td class="py-3.5 text-center">
                            @if($category->is_parent)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <span>{{ $category->total_products_count }} {{ __('منتج') }}</span>
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span>{{ $category->total_bundles_count }} {{ __('باقة') }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <span>{{ $category->products_count }} {{ __('منتج') }}</span>
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span>{{ $category->bundles_count }} {{ __('باقة') }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-primary-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا القسم؟') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="{{ __('حذف') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-xs">{{ __('لا توجد أقسام مسجلة بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
        {{ $categories->links() }}
    </div>
</div>
@endsection
