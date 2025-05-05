@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
<h1></h1>
@stop

@section('content')
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <div class="container mt-4">
        <h1 class="mb-4">Listado de Servicios</h1>

        @include('servicios.partials.filtros')

        @include('servicios.partials.alertas')

        @include('servicios.partials.tabla')

        @include('servicios.partials.modales', ['monedas' => $monedas])
    </div>


    @include('servicios.partials.scripts')

</body>

</html>

@stop

@section('css')
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop