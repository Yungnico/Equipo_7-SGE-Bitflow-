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



    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 ">

            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Sección Perfil -->
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección Cambio de Contraseña -->
            <div class="card border-danger shadow">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-11 col-md-11 col-lg-11 col-xl-11">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zona de peligro -->
            <div class="card border-danger shadow">
                <div class="card-body">
                    <h5 class="mb-2 text-danger">Zona de peligro</h5>
                    <p class="mb-2 text-muted" style="font-size: 0.95rem;">
                        Al eliminar tu cuenta perderás todos tus datos y acceso a los espacios de trabajo asociados.
                    </p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>

    <div class="container ">
    <div class="row justify-content-center">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-12 col-xl-11">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="py-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Formulario de cambio de nombre -->
                    <div class="col-md-4">
                        <div class="p-4 sm:p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                    <!-- Formulario de cambio de contraseña -->
                                        <div class="col-md-4">
                        <div class="p-4 sm:p-8">
                            <div class="max-w-xl ">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de eliminación de cuenta -->
                    <div class="col-md-4">
                        <div class="p-4 sm:p-8">
                            <div class="max-w-xl ">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    



@endsection
