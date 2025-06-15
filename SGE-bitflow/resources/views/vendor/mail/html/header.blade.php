@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://github.com/Yungnico/Equipo_7-SGE-Bitflow-/blob/Vlillo/SGE-bitflow/public/images/logo-light-mail.png?raw=true" class="logo" alt="Logo de SGE-Bitflow" style="height: 80px; width: auto; border-radius: 10px;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
