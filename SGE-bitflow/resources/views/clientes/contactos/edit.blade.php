{{-- resources/views/contactos/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'Editar Contacto')

@section('content_header')
    <h1>Editar Contacto</h1>
@stop

@section('content')
    <form action="{{ route('contactos.update', $contacto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre_contacto">Nombre:</label>
            <input type="text" name="nombre_contacto" value="{{ old('nombre_contacto', $contacto->nombre_contacto) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="email_contacto">Email:</label>
            <input type="email" name="email_contacto" value="{{ old('email_contacto', $contacto->email_contacto) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="telefono_contacto">Teléfono:</label>
            <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto', $contacto->telefono_contacto) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="tipo_contacto">Tipo de contacto:</label>
            <select name="tipo_contacto" class="form-control">
                <option value="Comercial" {{ $contacto->tipo_contacto == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                <option value="TI" {{ $contacto->tipo_contacto == 'TI' ? 'selected' : '' }}>TI</option>
                <option value="Contable" {{ $contacto->tipo_contacto == 'Contable' ? 'selected' : '' }}>Contable</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@stop




