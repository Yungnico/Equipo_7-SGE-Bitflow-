@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Clientes</h5>
            <p class="card-text">Ingresar, editar o eliminar clientes registrados en el sistema.</p>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-primary">Ir a Gestión de Clientes</a>
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