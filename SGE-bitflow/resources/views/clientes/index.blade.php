@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Clientes</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('clientes.exportar', array_merge(request()->all(), ['formato_exportacion' => 'pdf'])) }}" class="btn btn-outline-danger">Exportar a PDF</a>
    
    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif



    {{-- Formulario de búsqueda --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('clientes.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="razon_social" class="form-control" placeholder="Razón Social" value="{{ request('razon_social') }}">
                </div>
                <div class="form-group mr-2">
                    <input type="text" name="rut" class="form-control" placeholder="RUT" value="{{ request('rut') }}">
                </div>
                <div class="form-group mr-2">
                    <input type="text" name="nombre_fantasia" class="form-control" placeholder="Nombre Fantasía" value="{{ request('nombre_fantasia') }}">
                </div>
                <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Limpiar</a>
            </form>
        </div>
    </div>

    {{-- Botón crear cliente --}}
    <a href="{{ route('clientes.create') }}" class="btn btn-success mb-3">Crear Cliente</a>


    {{-- Tabla de clientes --}}
    @if($clientes->count())
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

                            <a href="{{ route('clientes.contactos.index', [$cliente->id, $cliente->nombre_fantasia]) }}" class="btn btn-primary btn-sm mt-1">
                                Contactos
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación con filtros mantenidos --}}


        
    @else
        <div class="alert alert-info">
            No se encontraron resultados.
        </div>
    @endif
</div>
@endsection


