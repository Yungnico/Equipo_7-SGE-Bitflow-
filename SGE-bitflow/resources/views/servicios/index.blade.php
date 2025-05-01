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

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h1 class="mb-4">Listado de Servicios</h1>

        <!-- ✅ Filtros -->
        @include('servicios.partials.filtros')

        <!-- ✅ Alertas -->
        @include('servicios.partials.alertas')

        <!-- ✅ Tabla de servicios -->
        @include('servicios.partials.tabla')

        <!-- ✅ Modales (crear, editar, eliminar) -->
        @include('servicios.partials.modales', ['uf' => $uf])
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @include('servicios.partials.scripts')

</body>

</html>

@stop

@section('css')
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop