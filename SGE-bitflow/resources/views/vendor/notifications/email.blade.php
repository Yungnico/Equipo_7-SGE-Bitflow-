<x-mail::message>
{{-- Greeting --}}
# Hola!

{{-- Intro Lines --}}
<p>Has solicitado restablecer tu contraseña. Haz clic en el botón de abajo para continuar.</p>

{{-- Action Button --}}
@isset($actionText)
<x-mail::button :url="$actionUrl" color="primary">
Cambiar Contraseña
</x-mail::button>
@endisset

{{-- Outro Lines --}}
<p>Si no solicitaste este cambio, puedes ignorar este correo.</p>

{{-- Salutation --}}
Saludos,<br>
Bitflow

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Si tienes problemas para hacer clic en el botón "Cambiar Contraseña", copia y pega la siguiente URL en tu navegador:<br>
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
