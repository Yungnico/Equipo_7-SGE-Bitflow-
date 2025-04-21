@extends('adminlte::page')


@section('content')
<div class="container">
    <h1>Clientes</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>RUT</th>
                <th>Nombre Fantasía</th>
                <th>Giro</th>
                <th>Dirección</th>
                <th>Logo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->razon_social }}</td>
                    <td>{{ $cliente->rut }}</td>
                    <td>{{ $cliente->nombre_fantasia }}</td>
                    <td>{{ $cliente->giro }}</td>
                    <td>{{ $cliente->direccion }}</td>
                    <td>
                        @if($cliente->logo)
                            <img src="{{ asset('storage/' . $cliente->logo) }}" width="80">
                        @else
                            Sin logo
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <a href="{{ route('clientes.contactos.index', [$cliente->id, $cliente->nombre_fantasia]) }}" class="btn btn-primary">
                    Gestionar Contactos
                </a>
            @endforeach
        </tbody>
    </table>
    

</div>
@endsection

