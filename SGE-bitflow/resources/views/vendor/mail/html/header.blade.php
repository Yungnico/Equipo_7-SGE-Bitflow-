@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo_dark.svj') }}" class="logo" alt="Logo de SGE-Bitflow" style="height: 50px; width: auto; border-radius: 10px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
