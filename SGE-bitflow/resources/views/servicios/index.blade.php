@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
<h1></h1>
@stop

@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Lista de Servicios</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <form method="GET" action="{{ route('servicios.index') }}" class="mb-4">
        <div class="container">
            <div class="row justify-content-center align-items-center g-2">

                {{-- Barra de búsqueda --}}
                <div class="col-md-6">
                    <input type="text" name="nombre_servicio" class="form-control form-control-lg" placeholder="Buscar servicio..." value="{{ request('nombre_servicio') }}">
                </div>

                {{-- Filtro de moneda --}}
                <div class="col-md-2">
                    <select name="moneda" class="form-select text-center" title="Filtrar por moneda">
                        <option value="">Moneda</option>
                        <option value="UF" {{ request('moneda') == 'UF' ? 'selected' : '' }}>UF</option>
                        <option value="USD" {{ request('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="CLP" {{ request('moneda') == 'CLP' ? 'selected' : '' }}>CLP</option>
                    </select>
                </div>

                {{-- Filtro de categoría --}}
                <div class="col-md-2">
                    <select name="categoria_id" class="form-select text-center" title="Filtrar por categoría">
                        <option value="">Categoría</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botón de buscar --}}
                <div class="col-md-1">
                    <button class="btn btn-primary w-100 text-white" type="submit">
                        Buscar
                    </button>
                </div>

                {{-- Botón de reset --}}
                <div class="col-md-1">
                    <a href="{{ route('servicios.index') }}" class="btn btn-danger w-100 text-white">
                        Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

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
            </div>
        </div>


        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($servicios->isEmpty())
        <div class="alert alert-warning">No hay servicios registrados.</div>
        @else
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
                        <td>${{ number_format($servicio->precio, 0, ',', '.') }}</td>
                        <td>{{ $servicio->moneda }}</td>
                        <td>{{ $servicio->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio"
                                data-id="{{ $servicio->id }}"
                                data-nombre="{{ $servicio->nombre_servicio }}"
                                data-descripcion="{{ $servicio->descripcion }}"
                                data-precio="{{ $servicio->precio }}"
                                data-moneda="{{ $servicio->moneda }}">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Crear Servicio -->
    <div class="modal fade" id="modalCrearServicio" tabindex="-1" aria-labelledby="crearServicioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('servicios.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearServicioLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" name="precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Moneda</label>
                        <select name="moneda" class="form-select" required>
                            <option value="">Seleccione una moneda</option>
                            <option value="CLP">CLP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <!-- Agrega más monedas si necesitas -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Categoría</label>
                        <select name="categoria_id" class="form-select">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Servicio -->
    <div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="editarServicioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formEditarServicio" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_servicio" id="editar-nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="editar-descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" name="precio" id="editar-precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Moneda</label>
                        <select name="moneda" id="editar-moneda" class="form-select" required>
                            <option value="CLP">CLP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <!-- Más si quieres -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Categoría</label>
                        <select name="categoria_id" id="editar-categoria" class="form-select">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mantenedor de Categorías -->
    <div class="modal fade" id="modalMantenedorCategorias" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Mantenedor de Categorías</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <!-- Botón para abrir modal crear -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0">Categorías Registradas</h6>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                            Agregar Categoría
                        </button>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($categorias->isEmpty())
                    <div class="alert alert-warning">No hay categorías registradas.</div>
                    @else
                    <table class="table table-bordered table-sm table-fixed">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50%;">Nombre</th>
                                <th style="width: 50%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categorias as $categoria)
                            <tr>
                                <td class="text-truncate align-middle" style="max-width: 0;">{{ $categoria->nombre }}</td>
                                <td class="align-middle" style="max-width: 0;">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCategoria"
                                            data-id="{{ $categoria->id }}"
                                            data-nombre="{{ $categoria->nombre }}">
                                            Editar
                                        </button>

                                        <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta categoría?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Categoría -->
    <div class="modal fade" id="modalCrearCategoria" tabindex="-1" aria-labelledby="crearCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('categorias.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearCategoriaLabel">Agregar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombreCategoria" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Categoría -->
    <div class="modal fade" id="modalEditarCategoria" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="formEditarCategoria">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editCategoriaId">
                    <div class="mb-3">
                        <label for="editCategoriaNombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="editCategoriaNombre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para cargar datos en modal editar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEditar = document.getElementById('modalEditarCategoria');
            modalEditar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                const form = document.getElementById('formEditarCategoria');
                form.action = `/categorias/${id}`;
                document.getElementById('editCategoriaId').value = id;
                document.getElementById('editCategoriaNombre').value = nombre;
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editarModal = document.getElementById('modalEditarServicio');
            editarModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                // Obtiene datos del botón
                var id = button.getAttribute('data-id');
                var nombre = button.getAttribute('data-nombre');
                var descripcion = button.getAttribute('data-descripcion');
                var precio = button.getAttribute('data-precio');
                var moneda = button.getAttribute('data-moneda');

                // Rellena los campos del formulario
                document.getElementById('editar-id').value = id;
                document.getElementById('editar-nombre').value = nombre;
                document.getElementById('editar-descripcion').value = descripcion;
                document.getElementById('editar-precio').value = precio;
                document.getElementById('editar-moneda').value = moneda;

                // Actualiza la acción del formulario
                var form = document.getElementById('formEditarServicio');
                form.action = '/servicios/' + id; // Asegúrate de que coincida con tu ruta update
            });
        });
    </script>

    <script>
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>


</body>

</html>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop