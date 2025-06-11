@vite(['resources/css/app.css', 'resources/js/app.js'])
@extends('adminlte::page')
@section('title', 'Perfil')

@section('content')

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 ">

            <div class="card mb-4 ">
                <div class="card-body">
                    <!-- Sección Perfil -->
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                            <h2 class="mb-1">Perfil</h2>
                            <p class=" text-muted" style="font-size: 16px;">
                                Actualiza tu información del perfil o tu dirección de correo electrónico.
                            </p>
                            <hr class="mb-4 border">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Cambio de Contraseña -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                            <h2 class="mb-1">Contraseña</h2>
                            <p class=" text-muted" style="font-size: 16px;">
                                Actualiza tu contraseña, asegurate de usar una combinación larga y aleatoria.
                            </p>
                            <hr class="mb-4 border">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zona de peligro -->
            <div class="card ">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                            <h2 class="mb-2 text-danger">Eliminar Cuenta</h2>
                            <hr class="mb-4 border">
                            <p class="mb-4 text-muted" style="font-size: 0.95rem;">
                                ¿Estas seguro de querer eliminar tu cuenta?, Al eliminar tu cuenta perderás todos tus datos y acceso a los espacios de trabajo asociados.
                            </p>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
