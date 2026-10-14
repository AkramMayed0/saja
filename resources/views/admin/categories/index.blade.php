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
                    <th class="pb-3 font-semibold">{{ __('الرابط الدائم (Slug)') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('عدد المنتجات') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 text-slate-400 text-xs font-mono">#{{ $category->id }}</td>
                        <td class="py-3.5 font-bold text-slate-900 dark:text-white">{{ $category->name }}</td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400 text-xs font-mono" dir="ltr">{{ $category->slug }}</td>
                        <td class="py-3.5 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $category->products_count }}
                            </span>
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
                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">{{ __('لا توجد أقسام مسجلة بعد.') }}</td>
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
