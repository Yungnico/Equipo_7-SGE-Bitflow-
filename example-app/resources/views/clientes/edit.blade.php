@extends('adminlte::page')
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Cliente</h1>

    <form action="{{ route('clientes.update', $cliente) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('clientes.form')
    </form>
</div>
@endsection
