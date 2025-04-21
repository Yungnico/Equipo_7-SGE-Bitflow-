@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Agregar nuevo contacto a {{ $cliente->nombre_fantasia }}</h2>

    <form action="{{ route('clientes.contactos.store', $cliente->id) }}" method="POST">
        @csrf

        @include('cliente.contactos.form')

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('clientes.contactos.index', $cliente->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

