<div class="py-4">


    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
            Agregar Servicio
        </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMantenedorCategorias">
            Categorías
        </button>
        <button id="reset-filtros" class="btn btn-primary">
            Limpiar filtros
        </button>
    </div>



    <div class="card">
        <div class="card-body">
            <table id="tabla-servicios" class="table table-striped table-bordered align-middle">
                <thead>
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
                            @php
                            $monedasUsadas = $servicios->pluck('moneda.moneda')->filter()->unique();
                            @endphp

                            <select id="filtro-moneda" class="form-select">
                                <option value="">Moneda</option>
                                @foreach($monedasUsadas as $moneda)
                                <option value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>

                        </th>
                        <th>
                            @php
                            $categoriasUsadas = $servicios->pluck('categoria.nombre')->filter()->unique();
                            @endphp

                            <select id="filtro-categoria" class="form-select">
                                <option value="">Categoría</option>
                                @foreach($categoriasUsadas as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
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
                                <i class="fas fa-edit"></i>
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