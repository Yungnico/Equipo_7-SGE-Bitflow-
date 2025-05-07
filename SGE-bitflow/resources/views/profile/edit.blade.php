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

    <div class="py-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Formulario de cambio de nombre -->
                    <div class="col-md-6">
                        <div class="p-4 sm:p-8">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de eliminación de cuenta -->
                    <div class="col-md-6">
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
