@extends('adminlte::page')

@section('title', 'Transferencias Bancarias')

@section('content_header')
<h1></h1>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<style>
    td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
    }
</style>

@endsection

@section('content')
<div class="container-fluid mt-1 px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Transferencias Bancarias</h1>
        <form action="{{ route('transferencias.importar') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="archivo" class="form-control" required>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ route('transferencias.conciliar') }}" class="btn btn-success px-4 py-2 mb-3">
            Conciliar Transferencias
        </a>

        <button type="button" id="reset-filtros" class="btn btn-secondary px-4 py-2 mb-3">
            Limpiar Filtros
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-transferencias" class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Estado</th>
                            <th>Tipo Movimiento</th>
                            <th>Fecha Transacción</th>
                            <th>Hora</th>
                            <th>Fecha Contable</th>
                            <th>Cuenta</th>
                            <th>Tipo Cuenta</th>
                            <th>Banco</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Glosa</th>
                            <th>Ingreso</th>
                            <th>Egreso</th>
                            <th>Saldo</th>
                            <th>Comentario</th>
                        </tr>

                        <tr class="filtros">
                            <th></th>
                            <th></th>
                            <th>
                                <!-- Filtro Nombre -->
                                <select class="form-select filtro-select" data-columna="2" style="min-width: 150px;">
                                    <option value="">Nombre</option>
                                    @foreach($transferencias->pluck('nombre')->unique() as $nombre)
                                    <option value="{{ $nombre }}">{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <!-- Filtro RUT -->
                                <select class="form-select filtro-select" data-columna="3" style="min-width: 150px;">
                                    <option value="">RUT</option>
                                    @foreach($transferencias->pluck('rut')->unique() as $rut)
                                    <option value="{{ $rut }}">{{ $rut }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select class="form-select filtro-select select2" data-columna="4" style="min-width: 150px;">
                                    <option value="">Estado</option>
                                    @foreach($transferencias->pluck('estado')->unique() as $estado)
                                    <option value="{{ $estado }}">{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select class="form-select filtro-select select2" data-columna="5" style="min-width: 150px;">
                                    <option value="">Tipo Movimiento</option>
                                    @foreach($transferencias->pluck('tipo_movimiento')->unique() as $mov)
                                    <option value="{{ $mov }}">{{ ucfirst($mov) }}</option>
                                    @endforeach
                                </select>
                            </th>

                            @for($i = 6; $i < 19; $i++)
                                <th>
                                </th>
                                @endfor
                        </tr>

                    </thead>
                    <tbody>
                        @forelse($transferencias as $t)
                        <tr>
                            <td class="details-control"></td>
                            <td class="text-center fw-bold">{{ $t->id }}</td>
                            <td>{{ $t->nombre }}</td>
                            <td>{{ $t->rut }}</td>
                            <td>
                                @if($t->estado === 'Pendiente')
                                <button
                                    class="btn btn-sm btn-warning btn-conciliar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalConciliar"
                                    data-id="{{ $t->id }}">
                                    Pendiente
                                </button>
                                @elseif($t->estado === 'Conciliada')
                                <span class="badge bg-success">Conciliada</span>
                                @else
                                {{ $t->estado }}
                                @endif
                            </td>
                            <td>
                                @if($t->tipo_movimiento === 'ingreso')
                                <button class="btn btn-sm btn-primary">Ingreso</button>
                                @elseif($t->tipo_movimiento === 'egreso' && !$t->costoDetalle)
                                <button type="button"
                                    class="btn btn-sm btn-danger btn-ver-egresos"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEgresos"
                                    data-transferencia-id="{{ $t->id }}">
                                    Egreso
                                </button>

                                @else
                                <span class="badge bg-success">Conciliado</span>
                                @endif
                            </td>

                            <td>{{ $t->fecha_transaccion }}</td>
                            <td>{{ $t->hora_transaccion }}</td>
                            <td>{{ $t->fecha_contable }}</td>
                            <td>{{ $t->numero_cuenta }}</td>
                            <td>{{ $t->tipo_cuenta }}</td>
                            <td>{{ $t->banco }}</td>
                            <td>{{ $t->codigo_transferencia }}</td>
                            <td>{{ $t->tipo_transaccion }}</td>
                            <td>{{ $t->glosa_detalle }}</td>
                            <td>${{ number_format($t->ingreso, 2, ',', '.') }}</td>
                            <td>${{ number_format($t->egreso, 2, ',', '.') }}</td>
                            <td>${{ number_format($t->saldo_contable, 2, ',', '.') }}</td>
                            <td>{{ $t->comentario_transferencia }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="text-center text-muted"></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAgregarTransferencia" tabindex="-1" aria-labelledby="modalAgregarTransferenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('transferencias.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarTransferenciaLabel">Agregar Transferencia Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">RUT</label>
                        <input type="text" name="rut" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Transacción</label>
                        <input type="date" name="fecha_transaccion" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Hora</label>
                        <input type="time" name="hora_transaccion" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Contable</label>
                        <input type="date" name="fecha_contable" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número de Cuenta</label>
                        <input type="text" name="numero_cuenta" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo Cuenta</label>
                        <input type="text" name="tipo_cuenta" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Banco</label>
                        <input type="text" name="banco" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código Transferencia</label>
                        <input type="text" name="codigo_transferencia" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo Transacción</label>
                        <input type="text" name="tipo_transaccion" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Glosa Detalle</label>
                        <textarea name="glosa_detalle" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ingreso</label>
                        <input type="number" step="0.01" name="ingreso" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Egreso</label>
                        <input type="number" step="0.01" name="egreso" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Saldo Contable</label>
                        <input type="number" step="0.01" name="saldo_contable" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Comentario Transferencia</label>
                        <textarea name="comentario_transferencia" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Conciliar Transferencia -->
<div class="modal fade" id="modalConciliar" tabindex="-1" aria-labelledby="modalConciliarLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Cotización para Conciliar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="table-responsive">
                        <table id="tabla-cotizaciones" class="table table-striped table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Código Cotización</th>
                                    <th>Nombre</th>
                                    <th>Fecha Cotización</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cotizacionesDisponibles as $cotizacion)
                                <tr>
                                    <td>{{ $cotizacion->codigo_cotizacion }}</td>
                                    <td>{{ $cotizacion->cliente->razon_social }}</td>
                                    <td>{{ $cotizacion->fecha_cotizacion }}</td>
                                    <td>${{ number_format($cotizacion->total, 0, ',', '.') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('transferencias.conciliar.manual') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="transferencias_bancarias_id" class="input-transferencia-id">
                                            <input type="hidden" name="cotizaciones_id_cotizacion" value="{{ $cotizacion->id_cotizacion }}">
                                            <button type="submit" class="btn btn-sm btn-primary">Conciliar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conciliar Egreso -->
<div class="modal fade" id="modalEgresos" tabindex="-1" aria-labelledby="modalEgresosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEgresosLabel">Seleccionar Costo para Conciliar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="table-responsive">
                        <table id="tabla-costos" class="table table-striped table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Moneda</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                                <tr class="bg-light">
                                    <th></th>
                                    <th>
                                        @php
                                        $conceptosUsados = $costosDisponibles->pluck('costo.concepto')->filter()->unique();
                                        @endphp
                                        <select class="form-select form-select-sm filtro-columna select2" data-columna="1">
                                            <option value="">Concepto</option>
                                            @foreach($conceptosUsados as $concepto)
                                            <option value="{{ $concepto }}">{{ $concepto }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        @php
                                        $monedasUsadas = $costosDisponibles->pluck('moneda.moneda')->filter()->unique();
                                        @endphp
                                        <select class="form-select form-select-sm filtro-columna select2" data-columna="3">
                                            <option value="">Moneda</option>
                                            @foreach($monedasUsadas as $moneda)
                                            <option value="{{ $moneda }}">{{ $moneda }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        @php
                                        $categoriasUsadas = $costosDisponibles->pluck('costo.categoria.nombre')->filter()->unique();
                                        @endphp
                                        <select class="form-select form-select-sm filtro-columna select2" data-columna="4">
                                            <option value="">Categoría</option>
                                            @foreach($categoriasUsadas as $categoria)
                                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($costosDisponibles as $detalle)
                                @php
                                $costo = $detalle->costo;
                                @endphp
                                <tr>
                                    <td>{{ $detalle->id }}</td>
                                    <td>{{ $costo->concepto ?? 'Sin concepto' }}</td>
                                    <td>${{ number_format($detalle->monto, 2, ',', '.') }}</td>
                                    <td>{{ $detalle->moneda->moneda ?? 'Sin moneda' }}</td>
                                    <td>{{ $costo->categoria->nombre ?? 'Sin categoría' }}</td>
                                    <td>{{ $costo->subcategoria->nombre ?? 'Sin subcategoría' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($detalle->fecha)->format('d-m-Y') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('transferencias.conciliar.egreso') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="transferencias_bancarias_id" class="input-transferencia-id">
                                            <input type="hidden" name="costos_detalle_id" value="{{ $detalle->id }}">
                                            <button type="submit" class="btn btn-sm btn-success">Conciliar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $.fn.dataTable.ext.errMode = 'throw';

    let tabla;

    $(document).ready(function() {
        tabla = $('#tabla-transferencias').DataTable({
            responsive: false,
            scrollX: true,
            paging: true,
            autoWidth: false,
            orderCellsTop: true,
            language: {
                url: '{{ asset("datatables/es-CL.json")}}'
            },
            columnDefs: [{
                    targets: 0,
                    orderable: false,
                    className: 'details-control',
                    data: null,
                    defaultContent: ''
                },
                {
                    targets: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                    visible: false
                }
            ]
        });

        $('.filtro-select').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return 'No se encontraron resultados';
                }
            }
        });

        $('.filtro-select').on('change', function() {
            const columna = $(this).data('columna');
            const valor = $(this).val();
            tabla.column(columna).search(valor || '', false, true).draw();
        });

        function format(rowData) {
            return `
                <table class="table table-bordered mb-0">
                    <tr><th>Estado</th><td>${rowData[4]}</td></tr>
                    <tr><th>Tipo Movimiento</th><td>${rowData[5]}</td></tr>
                    <tr><th>Fecha Transacción</th><td>${rowData[6]}</td></tr>
                    <tr><th>Hora</th><td>${rowData[7]}</td></tr>
                    <tr><th>Fecha Contable</th><td>${rowData[8]}</td></tr>
                    <tr><th>Cuenta</th><td>${rowData[9]}</td></tr>
                    <tr><th>Tipo Cuenta</th><td>${rowData[10]}</td></tr>
                    <tr><th>Banco</th><td>${rowData[11]}</td></tr>
                    <tr><th>Código</th><td>${rowData[12]}</td></tr>
                    <tr><th>Tipo</th><td>${rowData[13]}</td></tr>
                    <tr><th>Glosa</th><td>${rowData[14]}</td></tr>
                    <tr><th>Ingreso</th><td>${rowData[15]}</td></tr>
                    <tr><th>Egreso</th><td>${rowData[16]}</td></tr>
                    <tr><th>Saldo</th><td>${rowData[17]}</td></tr>
                    <tr><th>Comentario</th><td>${rowData[18]}</td></tr>
                </table>
            `;
        }

        $('#tabla-transferencias tbody').on('click', 'td.details-control', function() {
            let tr = $(this).closest('tr');
            let row = tabla.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        $('#reset-filtros').on('click', function() {
            $('.filtro-select').val('').trigger('change');
            tabla.columns().search('').draw();
        });

        $('#tabla-cotizaciones').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json")}}'
            }
        });

        document.querySelectorAll('.btn-ver-egresos').forEach(btn => {
            btn.addEventListener('click', function() {
                const transferenciaId = this.getAttribute('data-transferencia-id');
                document.querySelectorAll('#modalEgresos .input-transferencia-id').forEach(input => {
                    input.value = transferenciaId;
                });
            });
        });

        const modal = document.getElementById('modalConciliar');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const transferenciaId = button.getAttribute('data-id');
            modal.querySelectorAll('.input-transferencia-id').forEach(input => {
                input.value = transferenciaId;
            });
        });

        const tablaCostos = $('#tabla-costos').DataTable({
            responsive: true,
            autoWidth: false,
            orderCellsTop: true,
            language: {
                url: '{{ asset("datatables/es-CL.json") }}'
            },
            initComplete: function() {
                this.api().columns().every(function() {
                    let column = this;
                    $('select', column.header()).on('change clear', function() {
                        let val = $(this).val();
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                });
            }
        });

        // Evitar propagación en filtros
        $('#tabla-costos thead tr:eq(1) th').each(function() {
            $('select', this).on('click', function(e) {
                e.stopPropagation();
            });
        });

        // Activar Select2
        $('#modalEgresos .select2').select2({
            dropdownParent: $('#modalEgresos'),
            theme: 'bootstrap4',
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%'
        });

    });
</script>
@endsection