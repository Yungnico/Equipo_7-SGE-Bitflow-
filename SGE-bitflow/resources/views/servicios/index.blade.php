@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
<h1></h1>
@stop

@section('css')
{{-- Carga DataTables CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
@stop

@section('content')
<div class="container mt-4">

    @include('servicios.partials.alertas')
    @include('servicios.partials.tabla')
    @include('servicios.partials.modales', ['monedas' => $monedas])

</div>
@stop

@section('js')
@include('servicios.partials.scripts')
@stop