@extends('adminlte::page')

@section('title', 'Categorías y Subcategorías')

@section('content_header')
<h1 class="h3">Administración de Categorías</h1>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" />
@stop

@section('content')
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap">
        <button class="btn btn-success btn-sm btn-agregar" data-toggle="modal" data-target="#modalCrearCategoria">
            <i class="fas fa-plus mr-1"></i> Agregar Categoria
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Categoría</h5>
        </div>
        <div class="card-body">
            <table id="tabla-categorias" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $cat)
                    <tr>
                        <td>{{ $cat->nombre }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-editar-categoria"
                                data-id="{{ $cat->id }}"
                                data-nombre="{{ $cat->nombre }}">
                                <i class="fas fa-pencil-alt mr-1"></i>
                            </button>
                            <form action="{{ route('categorias-costos.destroy', $cat->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta categoría?')">
                                    <i class="fas fa-trash-alt mr-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap">
        <button class="btn btn-success btn-sm btn-agregar" data-toggle="modal" data-target="#modalCrearSubcategoria">
            <i class="fas fa-plus mr-1"></i> Agregar Subcategoria
        </button>
    </div>

    <!-- Tabla Subcategorías -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Subcategorías</h5>
        </div>
        <div class="card-body">
            <table id="tabla-subcategorias" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subcategorias as $sub)
                    <tr>
                        <td>{{ $sub->nombre }}</td>
                        <td>{{ $sub->categoria->nombre }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-editar-subcategoria"
                                data-id="{{ $sub->id }}"
                                data-nombre="{{ $sub->nombre }}"
                                data-categoria="{{ $sub->categoria_id }}">
                                <i class="fas fa-pencil-alt mr-1"></i>
                            </button>
                            <form action="{{ route('subcategorias-costos.destroy', $sub->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta subcategoría?')">
                                    <i class="fas fa-trash-alt mr-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Categoría -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCrearCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCrearCategoriaLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Nueva Categoría
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categorias-costos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_categoria" class="font-weight-bold">Nombre de la categoría</label>
                        <input type="text" name="nombre" id="nombre_categoria" class="form-control" placeholder="Ej: Oficina" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalEditarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarCategoriaLabel">
                    <i class="fas fa-edit mr-2"></i>Editar Categoría
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarCategoria" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editar_nombre_categoria" class="font-weight-bold">Nombre de la categoría</label>
                        <input type="text" name="nombre" id="editar_nombre_categoria" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Crear Subcategoría -->
<div class="modal fade" id="modalCrearSubcategoria" tabindex="-1" role="dialog" aria-labelledby="modalCrearSubcategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCrearSubcategoriaLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Nueva Subcategoría
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('subcategorias-costos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_subcategoria" class="font-weight-bold">Nombre de la subcategoría</label>
                        <input type="text" name="nombre" id="nombre_subcategoria" class="form-control" placeholder="Ej: Tecnología" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria_id" class="font-weight-bold">Categoría asociada</label>
                        <select name="categoria_id" id="categoria_id" class="form-control select2" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Subcategoría -->
<div class="modal fade" id="modalEditarSubcategoria" tabindex="-1" role="dialog" aria-labelledby="modalEditarSubcategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarSubcategoriaLabel">
                    <i class="fas fa-edit mr-2"></i>Editar Subcategoría
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarSubcategoria" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editar_nombre_subcategoria" class="font-weight-bold">Nombre de la subcategoría</label>
                        <input type="text" name="nombre" id="editar_nombre_subcategoria" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editar_categoria_id" class="font-weight-bold">Categoría asociada</label>
                        <select name="categoria_id" id="editar_categoria_id" class="form-control select2" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('#tabla-categorias, #tabla-subcategorias').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json") }}'
            }
        });

        // Inicializar Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Manejar edición de categoría
        $(document).on('click', '.btn-editar-categoria', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');

            $('#formEditarCategoria').attr('action', '{{ route("categorias-costos.update", "") }}/' + id);
            $('#editar_nombre_categoria').val(nombre);
            $('#modalEditarCategoria').modal('show');
        });

        // Manejar edición de subcategoría
        $(document).on('click', '.btn-editar-subcategoria', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            var categoria_id = $(this).data('categoria');

            $('#formEditarSubcategoria').attr('action', '{{ route("subcategorias-costos.update", "") }}/' + id);
            $('#editar_nombre_subcategoria').val(nombre);
            $('#editar_categoria_id').val(categoria_id).trigger('change');
            $('#modalEditarSubcategoria').modal('show');
        });

        // Cargar subcategorías al cambiar categoría (para crear)
        $('#categoria_id').on('change', function() {
            $('#subcategoria_id').val('').trigger('change');
        });

        // Cargar subcategorías al cambiar categoría (para editar)
        $('#editar_categoria_id').on('change', function() {
            $('#editar_subcategoria_id').val('').trigger('change');
        });
    });
</script>
@stop