@props(['url'])
@php
    $baseUrl = str_contains(config('app.url'), 'localhost') ? 'https://saja-ye.com' : rtrim(config('app.url'), '/');
    $logoUrl = $baseUrl . '/images/logo.png';
@endphp
<tr>
<td class="header" style="text-align: center; padding: 25px 0 15px 0;">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    <img src="{{ $logoUrl }}" class="logo" style="max-height: 60px; width: auto; display: block; margin: 0 auto;" alt="مؤسسة سجى المتخصصة">
</a>
</td>
</tr>
