<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Servicios Registrados</h1>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <!-- Izquierda: Agregar y Limpiar -->
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
                Agregar Servicio
            </button>

            <button id="reset-filtros" class="btn btn-secondary px-4 py-2">
                Limpiar filtros
            </button>

            <button class="btn btn-warning d-none" data-bs-toggle="modal" data-bs-target="#modalMantenedorMonedas">
                Monedas
            </button>
        </div>

        <!-- Derecha: Categorías -->
        <div>
            <button class="btn btn-warning px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalMantenedorCategorias">
                Categorías
            </button>
        </div>
    </div>



    <div class="card">
        <div class="card-body">
            <table id="tabla-servicios" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Moneda</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>

                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <select id="filtro-moneda" class="form-select">
                                <option value="">Moneda</option>
                                @foreach($monedas as $moneda)
                                <option value="{{ $moneda->moneda }}">{{ $moneda->moneda }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th>
                            <select id="filtro-categoria" class="form-select">
                                <option value="">Categoría</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>${{ number_format($servicio->precio, 2, ',', '.') }}</td>
                        <td>{{ $servicio->moneda->moneda ?? 'Sin moneda' }}</td>
                        <td>{{ $servicio->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio"
                                data-id="{{ $servicio->id }}"
                                data-nombre="{{ $servicio->nombre_servicio }}"
                                data-descripcion="{{ $servicio->descripcion }}"
                                data-precio="{{ $servicio->precio }}"
                                data-moneda="{{ $servicio->moneda->moneda}}"
                                data-categoria="{{ $servicio->categoria_id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>