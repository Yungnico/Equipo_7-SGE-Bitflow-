@extends('adminlte::page')

@section('title', 'Categorías y Subcategorías')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('content')
<div class="container-fluid mt-4">

    {{-- Tabla Categorías --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Categorías de Costos</h5>
            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalCrearCategoria">+ Nueva</button>
        </div>
        <div class="card-body">
            <table id="tabla-categorias" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $cat)
                    <tr>
                        <td>{{ $cat->nombre }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-editar-categoria" data-id="{{ $cat->id }}" data-nombre="{{ $cat->nombre }}" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria">Editar</button>

                            <form action="{{ route('categorias-costos.destroy', $cat->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabla Subcategorías --}}
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Subcategorías</h5>
            <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalCrearSubcategoria">+ Nueva</button>
        </div>
        <div class="card-body">
            <table id="tabla-subcategorias" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría Asociada</th>
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
                                data-categoria="{{ $sub->categoria_id }}"
                                data-toggle="modal"
                                data-target="#modalEditarSubcategoria">
                                Editar
                            </button>
                            <form action="{{ route('subcategorias-costos.destroy', $sub->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar subcategoría?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modales para Crear y Editar --}}
    @include('CostosCategorias.partials.modal-categoria')
    @include('CostosCategorias.partials.modal-subcategoria')

</div>
@stop

@section('js')
<!-- jQuery (AdminLTE ya lo incluye) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTables
        $('#tabla-categorias, #tabla-subcategorias').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json") }}'
            }
        });

        // Manejar edición de categoría - versión mejorada
        $(document).on('click', '.btn-editar-categoria', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');

            $('#formEditarCategoria').attr('action', '{{ route("categorias-costos.update", "") }}/' + id);
            $('#editar_nombre_categoria').val(nombre);

            // Mostrar el modal
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

            // Mostrar el modal
            $('#modalEditarSubcategoria').modal('show');
        });
    });
</script>
@stop