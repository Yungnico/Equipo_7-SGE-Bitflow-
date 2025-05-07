@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Futuras Metricas</h1>
@stop

@section('content')

    <div class="container mt-4">
        <div class="row">
            <!-- Tarjeta de Clientes (ya existente) -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <p class="card-text">Ingresar, editar o eliminar clientes registrados en el sistema.</p>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-primary">Ir a gestión de clientes</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop