@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Paridades</h1>
@endsection

@section('content')
    @if($alerta)
        <div class="alert alert-danger">
            {{ $alerta }}
        </div>
    @endif

    <a href="{{ route('paridades.create') }}" class="btn btn-primary mb-3">Agregar nueva paridad</a>

    @if($paridades->isEmpty())
        <p>No hay paridades registradas.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Moneda</th>
                    <th>Valor</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paridades as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->moneda }}</td>
                        <td>{{ $p->valor }}</td>
                        <td>{{ $p->fecha }}</td>
                        <td>
                            <a href="{{ route('paridades.edit', $p->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('paridades.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar esta paridad?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
