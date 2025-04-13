@extends('adminlte::page')

@section('content')
<div class="m-6">
    <h1 class="mb-4">Gestión de clientes</h1>

    {{-- Botón para crear nuevo cliente --}}
    
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Nuevo cliente</a>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        {{-- Tabla de clientes --}}
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-800 text-center text-sm md:text-base tracking-wide">
                        <th class="px-4 py-2">Razón Social</th>
                        <th class="px-4 py-2">RUT</th>
                        <th class="px-4 py-2">Nombre Fantasía</th>
                        <th class="px-4 py-2">Giro</th>
                        <th class="px-4 py-2">Dirección</th>
                        <th class="px-4 py-2">Logo</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->razon_social }}</td>
                            <td>{{ $cliente->rut }}</td>
                            <td>{{ $cliente->nombre_fantasia ?? '-' }}</td>
                            <td>{{ $cliente->giro ?? '-' }}</td>
                            <td>{{ $cliente->direccion ?? '-' }}</td>
                            <td>
                                @if($cliente->logo)
                                    <img src="{{ asset('storage/' . $cliente->logo) }}" alt="Logo" width="50">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">Editar</a>

                                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" >No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

