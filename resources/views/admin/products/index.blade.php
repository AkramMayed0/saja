@extends('layouts.admin')

@section('title', __('إدارة المنتجات'))

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ __('المنتجات') }}</h2>
        <a href="{{ route('admin.products.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 text-sm w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('إضافة منتج') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[800px] whitespace-nowrap">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-4 font-bold">{{ __('المنتج') }}</th>
                    <th class="pb-4 font-bold">{{ __('القسم') }}</th>
                    <th class="pb-4 font-bold">{{ __('السعر') }}</th>
                    <th class="pb-4 font-bold">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-16 object-cover rounded-lg shadow-sm" alt="{{ $product->title }}">
                                @else
                                    <div class="w-12 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 shadow-sm shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white line-clamp-1">{{ $product->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $product->sku ?? ('#' . $product->id) }}
                                        @if($product->author) | {{ $product->author }} @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 font-bold text-primary-600 dark:text-primary-400 text-sm">
                            {{ $product->category->name ?? __('بدون قسم') }}
                        </td>
                        <td class="py-4 font-bold text-gray-900 dark:text-white">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-primary-500 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900 p-2 rounded-lg transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا المنتج؟ سيتم حذفه نهائياً!') }}')">
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
                        <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400 text-lg">{{ __('لا توجد منتجات مسجلة بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
