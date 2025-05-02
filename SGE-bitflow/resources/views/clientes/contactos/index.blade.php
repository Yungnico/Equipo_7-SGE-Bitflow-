@extends('adminlte::page')
@section('content')
<div class="container">

    {{-- Mensaje de éxito después de actualizar o crear --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <h2>Contactos de {{ $cliente->nombre_fantasia }}</h2>
    
    <a href="{{ route('clientes.contactos.create', $cliente->id) }}" class="btn btn-primary">Agregar contacto</a>

    @if ($cliente->contactos->isEmpty())
        <div class="alert alert-info">No hay contactos registrados.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cliente->contactos as $contacto)
                    <tr>
                        <td>{{ $contacto->nombre_contacto }}</td>
                        <td>{{ $contacto->email_contacto }}</td>
                        <td>{{ $contacto->telefono_contacto }}</td>
                        <td>{{ $contacto->tipo_contacto }}</td>
                        <td>
                            <a href="{{ route('contactos.edit', $contacto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('clientes.contactos.destroy', [$cliente->id, $contacto->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
