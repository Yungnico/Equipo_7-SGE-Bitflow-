


@vite(['resources/css/app.css', 'resources/js/app.js'])
@extends('adminlte::page')

@section('title', 'Cambiar Contraseña')

@section('content')
    <div class="py-12 flex justify-center">
        <div class="card shadow-lg rounded-lg p-6 bg-white max-w-xl w-full">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

@endsection
