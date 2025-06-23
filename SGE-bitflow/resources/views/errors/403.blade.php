@extends('errors::minimal')

@section('title', __('403'))

@section('message')
    {{-- Redirección automática después de 5 segundos al dashboard --}}
    <meta http-equiv="refresh" content="5;url={{ url('/dashboard') }}">
    <div class="text-center">
        <img src="{{ asset('images/logo-amarillo.png') }}" alt="Logo Light" style="width: 150px; height: auto; ">
    </div>
    <div class="text-center">
        <h1 class="text-6xl font-bold mb-2">403</h1>
        <div class="text-lg text-gray-700 dark:text-gray-300">
            Esta acción no está autorizada.<br>
            Serás redirigido automáticamente a la pagina principal.
        </div>
    </div>
@endsection
