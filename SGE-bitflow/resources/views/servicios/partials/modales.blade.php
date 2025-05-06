{{-- Modal: Crear Servicio --}}
<div class="modal fade" id="modalCrearServicio" tabindex="-1" aria-labelledby="modalCrearServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('servicios.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_servicio" class="form-label">Nombre del Servicio</label>
                        <input type="text" name="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="text" name="precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="moneda" class="form-label">Moneda</label>
                        <select name="moneda_id" class="form-select" required>
                            @foreach($monedas as $moneda)
                            <option value="{{ $moneda->id }}">{{ $moneda->codigo }} - {{ $moneda->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar Servicio --}}
<div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="modalEditarServicioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formEditarServicio">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editar-id" name="id">
                    <div class="mb-3">
                        <label for="editar-nombre" class="form-label">Nombre del Servicio</label>
                        <input type="text" id="editar-nombre" name="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-descripcion" class="form-label">Descripción</label>
                        <textarea id="editar-descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editar-precio" class="form-label">Precio</label>
                        <input type="number" id="editar-precio" name="precio" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-moneda" class="form-label">Moneda</label>
                        <select id="editar-moneda_id" name="moneda_id" class="form-select" required>
                            @foreach($monedas as $moneda)
                            <option value="{{ $moneda->id }}">{{ $moneda->codigo }} - {{ $moneda->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Mantenedor de Categorías --}}
<div class="modal fade" id="modalMantenedorCategorias" tabindex="-1" aria-labelledby="modalMantenedorCategoriasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Mantenedor de Categorías</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                    Crear Nueva Categoría
                </button>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla-categorias" style="width: 100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->nombre }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarCategoria"
                                        data-id="{{ $categoria->id }}"
                                        data-nombre="{{ $categoria->nombre }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Crear Categoría --}}
<div class="modal fade" id="modalCrearCategoria" tabindex="-1" aria-labelledby="modalCrearCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('categorias.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Crear Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Editar Categoría --}}
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditarCategoria">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCategoriaId">
                    <div class="mb-3">
                        <label for="editCategoriaNombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="editCategoriaNombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Mantenedor de Monedas --}}

<div class="modal fade" id="modalMantenedorMonedas" tabindex="-1" aria-labelledby="modalMantenedorMonedasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Mantenedor de Monedas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearMoneda">
                    Crear Nueva Moneda
                </button>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaMonedas">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Valor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monedas as $moneda)
                            <tr>
                                <td>{{ $moneda->nombre }}</td>
                                <td>{{ $moneda->valor }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarMoneda"
                                        data-id="{{ $moneda->id }}"
                                        data-nombre="{{ $moneda->nombre }}"
                                        data-valor="{{ $moneda->valor }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('monedas.destroy', $moneda->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta moneda?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrearMoneda" tabindex="-1" aria-labelledby="modalCrearMonedaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('monedas.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Crear Moneda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Moneda</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor</label>
                        <input type="number" name="valor" step="0.01" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarMoneda" tabindex="-1" aria-labelledby="modalEditarMonedaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditarMoneda">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Moneda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editMonedaId">
                    <div class="mb-3">
                        <label for="editMonedaNombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="editMonedaNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMonedaValor" class="form-label">Valor</label>
                        <input type="number" name="valor" id="editMonedaValor" step="0.01" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>