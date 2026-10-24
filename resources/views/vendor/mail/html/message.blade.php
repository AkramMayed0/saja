<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
<div style="direction: rtl; text-align: right; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; line-height: 1.8;">
{!! $slot !!}
</div>

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
<div style="direction: rtl; text-align: right; font-size: 11px; color: #64748b; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
{!! $subcopy !!}
</div>
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
<div style="direction: rtl; text-align: center; font-size: 12px; color: #94a3b8; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
جميع الحقوق محفوظة © {{ date('Y') }} مؤسسة سجى المتخصصة.
</div>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
