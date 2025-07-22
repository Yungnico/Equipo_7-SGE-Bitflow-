@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo-dark-mail.png') }}" class="logo" alt="Logo de SGE-Bitflow" style="height: 80px; width: auto; border-radius: 10px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
