@extends('adminlte::page')

@section('content')
<div class="container">
    <h2>Editar contacto de {{ $cliente->nombre }}</h2>

    <form action="{{ route('clientes.contactos.update', [$cliente->id, $contacto->id]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('cliente.contactos.form')

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('clientes.contactos.index', $cliente->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection


