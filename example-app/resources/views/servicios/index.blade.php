<!DOCTYPE html>
<html>
<head>
    <title>Lista de Servicios</title>
</head>
<body>
    <h1>Servicios Registrados</h1>

    <div class="col ">
      <a class="btn btn-sm btn-success" href={{ route('servicios.create') }}>Add service</a>
    </div>

    @if($servicios->isEmpty())
        <p>No hay servicios registrados.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Moneda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>{{ $servicio->precio }}</td>
                        <td>{{ $servicio->moneda }}</td>
                        <td>
                            <a href="{{ route('servicios.edit', $servicio->id) }}">
                                <button>Editar</button>
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('servicios.toggleEstado', $servicio->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit">
                                    {{ $servicio->estado ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
