<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Servicios Registrados</h1>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
                Agregar Servicio
            </button>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalMantenedorCategorias">
                Administrar Categorías
            </button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalMantenedorMonedas">
                Mantenedor de Monedas
            </button>
        </div>
    </div>

    @if(!$servicios->isEmpty())
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Moneda</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->nombre_servicio }}</td>
                    <td>{{ $servicio->descripcion }}</td>
                    <td>${{ number_format($servicio->precio, 2, ',', '.') }}</td>
                    <td>{{ $servicio->moneda }}</td>
                    <td>{{ $servicio->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#modalEditarServicio"
                            data-id="{{ $servicio->id }}"
                            data-nombre="{{ $servicio->nombre_servicio }}"
                            data-descripcion="{{ $servicio->descripcion }}"
                            data-precio="{{ $servicio->precio }}"
                            data-moneda="{{ $servicio->moneda_id }}">
                            Editar
                        </button>
                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $servicios->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>