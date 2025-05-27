<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Servicios Registrados</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalMantenedorCategorias">
                Categorías
            </button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalMantenedorMonedas">
                Monedas
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabla-servicios" class="table table-striped table-bordered align-middle">
                {{-- Fila de encabezados --}}
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

                {{-- Fila de filtros --}}
                <thead class="table-dark filtros">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <select class="form-select filtro-select" data-columna="3">
                                <option value="">Moneda</option>
                                @foreach($monedas as $moneda)
                                <option value="{{ $moneda->nombre }}">{{ $moneda->nombre }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th>
                            <select class="form-select filtro-select" data-columna="4">
                                <option value="">Categoría</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button id="reset-filtros" class="btn btn-danger p-2" title="Resetear filtros">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button type="button" class="btn btn-success p-2" title="Crear servicio" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>

                {{-- Cuerpo de la tabla --}}
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>${{ number_format($servicio->precio, 2, ',', '.') }}</td>
                        <td>{{ $servicio->moneda->nombre ?? 'Sin moneda' }}</td>
                        <td>{{ $servicio->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio"
                                data-id="{{ $servicio->id }}"
                                data-nombre="{{ $servicio->nombre_servicio }}"
                                data-descripcion="{{ $servicio->descripcion }}"
                                data-precio="{{ $servicio->precio }}"
                                data-moneda="{{ $servicio->moneda }}"
                                data-categoria="{{ $servicio->categoria_id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay servicios</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>