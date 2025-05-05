@extends('adminlte::page')
@section('content')
<div class="container">
    <h2>Contactos de {{ $cliente->nombre_fantasia }}</h2>
    
    <!-- AQUI ESTA EL ERROR 
    <a href="{{ route('clientes.contactos.create',$cliente->nombre_fantasia) }}" class="btn btn-primary mb-3">Nuevo Contacto</a>-->
    
    <a href="{{ route('clientes.contactos.create', $cliente->id) }}" class="btn btn-primary">Agregar contacto</a>

    <!-- AQUI ESTA EL ERROR -->


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
                            <a href="{{ route('clientes.contactos.edit', [$cliente->id, $contacto->id]) }}" class="btn btn-warning btn-sm">Editar</a>
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

