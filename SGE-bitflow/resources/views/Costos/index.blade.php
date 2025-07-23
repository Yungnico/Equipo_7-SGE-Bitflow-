@extends('adminlte::page')

@section('title', 'Costos')



@section('css')
{{-- DataTables + Select2 --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('content')
@if(session('success'))
    <input type="hidden" id="successMessage" value="{{ session('success') }}">
@endif

@if(session('error'))
    <input type="hidden" id="errorMessage" value="{{ session('error') }}">
@endif
<div class="container-fluid mt-5 pt-4 px-0">

    {{-- Botones de acciones --}}
    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCosto">
                Agregar Costo
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMantenedorCategorias">
                Categorías
            </button>
            <button id="reset-filtros" class="btn btn-primary">
                Limpiar filtros
            </button>
        </div>

    </div>

    {{-- Tabla de costos --}}
    <div class="card">
        <div class="card-body">
            <table id="tabla-costos" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Moneda</th>
                        <th>Categoría</th>
                        <th>Frecuencia</th>
                        <th>Acciones</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>
                            @php
                            $monedasUnicas = $costos->pluck('detalles')->flatten()->pluck('moneda.moneda')->filter()->unique();
                            @endphp

                            <select id="filtro-moneda" class="form-select" style="min-width: 120px;">
                                <option value="">Moneda</option>
                                @foreach($monedasUnicas as $moneda)
                                <option value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th>
                            @php
                            $categoriasUnicas = $costos->pluck('categoria.nombre')->filter()->unique();
                            @endphp

                            <select id="filtro-categoria-costos" class="form-select" style="min-width: 150px;">
                                <option value="">Categoría</option>
                                @foreach($categoriasUnicas as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                                @endforeach
                            </select>

                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($costos as $costo)
                    @php
                    // Obtener el primer detalle específico de este costo
                    $detalle = $costo->detalles->where('costo_id', $costo->id)->first(); //last
                    $moneda_id = optional($detalle)->moneda_id;
                    $monto = optional($detalle)->monto;
                    @endphp
                    <tr>
                        <td>{{ $costo->concepto }}</td>
                        <td>
                            @if($detalle)
                            ${{ number_format($detalle->monto, 2, ',', '.') }}
                            @else
                            Sin monto
                            @endif
                        </td>
                        <td>{{ optional($detalle->moneda)->moneda ?? 'Sin moneda' }}</td>
                        <td>{{ $costo->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td>{{ ucfirst($costo->frecuencia_pago) }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            <button
                                class="btn btn-sm btn-outline-primary btn-editar"
                                data-id="{{ $costo->id }}"
                                data-concepto="{{ $costo->concepto }}"
                                data-frecuencia="{{ $costo->frecuencia_pago }}"
                                data-categoria="{{ $costo->categoria_id }}"
                                data-moneda="{{ $moneda_id }}"
                                data-monto="{{ $monto }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarCosto">
                                <i class="fas fa-edit"></i>
                            </button>


                            <form action="{{ route('costos.destroy', $costo->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este costo?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
    </div>

    <!-- Modal Crear Costo -->
    <div class="modal fade" id="modalCrearCosto" tabindex="-1" aria-labelledby="modalCrearCostoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('costos.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearCostoLabel">Agregar Costo</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="concepto" class="form-label">Concepto</label>
                            <input type="text" class="form-control" name="concepto" required>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>

                        <div class="col-md-6">
                            <label for="frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                            <select name="frecuencia_pago" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="único">Único</option>
                                <option value="mensual">Mensual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select name="categoria_id" id="categoria_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-md-6">
                            <label for="subcategoria_id" class="form-label">Subcategoría</label>
                            <select name="subcategoria_id" id="subcategoria_id" class="form-select" required>
                                <option value="">Seleccione una categoría primero</option>
                            </select>
                        </div> --}}

                        <div class="col-md-6">
                            <label for="moneda_id" class="form-label">Moneda</label>
                            <select name="moneda_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}">{{ $moneda->moneda }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="monto" class="form-label">Monto</label>
                            <input type="number" class="form-control" name="monto" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Costo -->
    <div class="modal fade" id="modalEditarCosto" tabindex="-1" aria-labelledby="modalEditarCostoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formEditarCosto" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalEditarCostoLabel">Editar Costo</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <input type="hidden" name="id" id="editar_id">
                        <div class="col-md-6">
                            <label for="editar_concepto" class="form-label">Concepto</label>
                            <input type="text" class="form-control" name="concepto" id="editar_concepto" required>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_modificacion" class="form-label">Aplicar cambios desde</label>
                            <input type="date" class="form-control" name="fecha_modificacion" id="editar_fecha_modificacion" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editar_frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                            <select name="frecuencia_pago" id="editar_frecuencia_pago" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="único">Único</option>
                                <option value="mensual">Mensual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editar_categoria_id" class="form-label">Categoría</label>
                            <select name="categoria_id" id="editar_categoria_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-6">
                            <label for="editar_subcategoria_id" class="form-label">Subcategoría</label>
                            <select name="subcategoria_id" id="editar_subcategoria_id" class="form-select" required>
                                <option value="">Seleccione</option>
                            </select>
                        </div> --}}
                        <div class="col-md-6">
                            <label for="editar_moneda_id" class="form-label">Moneda</label>
                            <select name="moneda_id" id="editar_moneda_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}">{{ $moneda->moneda }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editar_monto" class="form-label">Monto</label>
                            <input type="number" class="form-control" name="monto" id="editar_monto" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="modal-footer">
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
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="tabla-categorias" style="width: 100%">
                            <thead class="table-secondary">
                                <tr>
                                    <th></th>
                                    <th>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-success p-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCrearCategoria"
                                                data-parent="#modalMantenedorCategorias">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
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
                                            <form action="{{ route('categoriascostos.destroy', $categoria->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        <form method="POST" action="{{ route('categoriascostos.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
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
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let table = $('#tabla-costos').DataTable({
                responsive: true,
                orderCellsTop: true,
                fixedHeader: true,
                language: {
                    url: '{{ asset("datatables/es-CL.json")}}'
                },
            });

            $('#filtro-moneda').on('change', function() {
                table.column(2).search($(this).val()).draw();
            });

            $('#filtro-categoria-costos').on('change', function() {
                table.column(3).search($(this).find('option:selected').text()).draw();
            });

            $('#reset-filtros').on('click', function() {
                $('#filtro-moneda, #filtro-categoria-costos').val('').trigger('change');
                table.search('').columns().search('').draw();
            });

            // Crear: cargar subcategorías dinámicamente
            $('#categoria_id').on('change', function() {
                const categoriaId = $(this).val();
                const subSelect = $('#subcategoria_id');
                subSelect.prop('disabled', true).html('<option value="">Cargando...</option>');

                if (categoriaId) {
                    fetch(`/subcategorias/${categoriaId}`)
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">Seleccione</option>';
                            data.forEach(sub => {
                                options += `<option value="${sub.id}">${sub.nombre}</option>`;
                            });
                            subSelect.html(options).prop('disabled', false);
                        })
                        .catch(err => {
                            console.error('Error al cargar subcategorías:', err);
                            subSelect.html('<option value="">Error al cargar</option>').prop('disabled', false);
                        });
                } else {
                    subSelect.html('<option value="">Seleccione una categoría primero</option>').prop('disabled', false);
                }
            });

            // Editar
            let subcategoriaIdSeleccionada = null;

            $('#editar_categoria_id').on('change', function() {
                const categoriaId = $(this).val();
                const subSelect = $('#editar_subcategoria_id');
                subSelect.prop('disabled', true).html('<option value="">Cargando...</option>');

                if (categoriaId) {
                    fetch(`/subcategorias/${categoriaId}`)
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">Seleccione</option>';
                            data.forEach(sub => {
                                const selected = sub.id == subcategoriaIdSeleccionada ? 'selected' : '';
                                options += `<option value="${sub.id}" ${selected}>${sub.nombre}</option>`;
                            });
                            subSelect.html(options).prop('disabled', false);
                        })
                        .catch(err => {
                            console.error('Error al cargar subcategorías (editar):', err);
                            subSelect.html('<option value="">Error al cargar</option>').prop('disabled', false);
                        });
                } else {
                    subSelect.html('<option value="">Seleccione una categoría primero</option>').prop('disabled', false);
                }
            });

            // Botón "Editar"
            $(document).on('click', '.btn-editar', function() {
                const btn = $(this);
                const id = btn.data('id');
                const categoriaId = btn.data('categoria');
                subcategoriaIdSeleccionada = btn.data('subcategoria');

                $('#editar_id').val(id);
                $('#editar_concepto').val(btn.data('concepto'));
                $('#editar_frecuencia_pago').val(btn.data('frecuencia'));
                $('#editar_moneda_id').val(btn.data('moneda'));
                $('#editar_monto').val(btn.data('monto'));

                const hoy = new Date().toISOString().split('T')[0];
                $('#editar_fecha_modificacion').val(hoy);

                $('#formEditarCosto').attr('action', `/costos/${id}`);
                $('#editar_categoria_id').val(categoriaId).trigger('change');
            });
        });

        // SweetAlert2 para success y error
        var successMessage = document.getElementById('successMessage');
        var errorMessage = document.getElementById('errorMessage');

        if (successMessage) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "success",
                title: "Éxito!",
                text: successMessage.value,
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage.value,
                confirmButtonText: 'Aceptar'
            });
        }
    </script>
@endsection