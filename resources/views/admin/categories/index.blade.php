@extends('layouts.admin')

@section('title', __('إدارة الأقسام'))

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ __('الأقسام') }}</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('إضافة قسم') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-4 font-bold">{{ __('الرقم') }}</th>
                    <th class="pb-4 font-bold">{{ __('اسم القسم') }}</th>
                    <th class="pb-4 font-bold">{{ __('الرابط الدائم (Slug)') }}</th>
                    <th class="pb-4 font-bold">{{ __('عدد المنتجات') }}</th>
                    <th class="pb-4 font-bold">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 text-gray-500 dark:text-gray-400 font-bold">#{{ $category->id }}</td>
                        <td class="py-4 font-bold text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="py-4 text-gray-500 dark:text-gray-400 text-sm">{{ $category->slug }}</td>
                        <td class="py-4">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm font-bold">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-primary-500 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900 p-2 rounded-lg transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا القسم؟') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900 p-2 rounded-lg transition-colors" title="{{ __('حذف') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400 text-lg">{{ __('لا توجد أقسام مسجلة بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $categories->links() }}
    </div>
</div>
@endsection
