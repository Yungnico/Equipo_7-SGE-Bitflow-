@extends('adminlte::page')
<a href="{{ route('clientes.contactos.create', $cliente->id) }}" class="btn btn-primary">
    Agregar Contacto
</a>

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
                    <form action="{{ route('clientes.contactos.destroy', [$cliente->id, $contacto->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Estás segura?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
