@extends('layouts.admin')

@section('title', __('تدريب المعلمين'))

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">{{ __('جميع الدورات والتدريب') }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('إدارة الفيديوهات والدورات التدريبية الموجهة للمعلمين') }}</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>{{ __('إضافة دورة جديدة') }}</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right min-w-[700px] whitespace-nowrap text-sm">
            <thead>
                <tr class="text-slate-400 dark:text-slate-500 text-xs border-b border-slate-100 dark:border-slate-800">
                    <th class="pb-3 font-semibold">{{ __('العنوان') }}</th>
                    <th class="pb-3 font-semibold">{{ __('المنتج المرتبط') }}</th>
                    <th class="pb-3 font-semibold">{{ __('رابط الفيديو') }}</th>
                    <th class="pb-3 font-semibold">{{ __('تاريخ الإضافة') }}</th>
                    <th class="pb-3 font-semibold text-center">{{ __('إجراءات') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($courses as $course)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 font-bold text-slate-900 dark:text-white">{{ $course->title }}</td>
                        <td class="py-3.5 text-xs text-slate-600 dark:text-slate-300">
                            @if($course->product)
                                <span class="bg-primary-50 dark:bg-primary-950/60 text-primary-700 dark:text-primary-300 px-2.5 py-0.5 rounded-full text-xs font-semibold">{{ $course->product->title }}</span>
                            @else
                                <span class="text-slate-400">{{ __('عام (غير مرتبط)') }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-xs">
                            <a href="{{ $course->youtube_url }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span>{{ __('مشاهدة على YouTube') }}</span>
                            </a>
                        </td>
                        <td class="py-3.5 text-xs text-slate-500 dark:text-slate-400" dir="ltr">{{ $course->created_at->format('Y-m-d') }}</td>
                        <td class="py-3.5 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('admin.courses.edit', $course) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-primary-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="{{ __('تعديل') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه الدورة؟') }}')">
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
                        <td colspan="5" class="py-12 text-center text-slate-400 text-xs">{{ __('لا توجد دورات مسجلة بعد.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($courses, 'links'))
        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection
