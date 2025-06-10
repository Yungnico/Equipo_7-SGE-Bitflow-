@extends('adminlte::page')

@section('title', 'Transferencias Bancarias')

@section('content_header')
<h1></h1>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Transferencias Bancarias</h1>
        <form action="{{ route('transferencias.importar') }}" method="POST" enctype="multipart/form-data" class="form-inline">
            @csrf
            <input type="file" name="archivo" class="form-control mr-2" required>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>

    <a href="{{ route('transferencias.conciliar') }}" class="btn btn-success mb-3">
        Conciliar Transferencias
    </a>

    <button type="button" id="reset-filtros" class="btn btn-secondary mb-3">
        Limpiar Filtros
    </button>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-transferencias" class="table table-bordered table-hover w-100">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RUT</th>
                            <th>Estado</th>
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
                        <tr class="filtros bg-light">
                            <th></th>
                            <th>
                                <select class="form-control filtro-select" data-columna="1">
                                    <option value="">Nombre</option>
                                    @foreach($transferencias->pluck('nombre')->unique() as $nombre)
                                    <option value="{{ $nombre }}">{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select class="form-control filtro-select" data-columna="2">
                                    <option value="">RUT</option>
                                    @foreach($transferencias->pluck('rut')->unique() as $rut)
                                    <option value="{{ $rut }}">{{ $rut }}</option>
                                    @endforeach
                                </select>
                            </th>
                            @for($i = 2; $i < 16; $i++)<th>
                                </th>@endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transferencias as $t)
                        <tr>
                            <td class="text-center font-weight-bold">{{ $t->id }}</td>
                            <td>{{ $t->nombre }}</td>
                            <td>{{ $t->rut }}</td>
                            <td>
                                @if($t->estado === 'Pendiente')
                                <button
                                    class="btn btn-sm btn-warning btn-conciliar"
                                    data-toggle="modal"
                                    data-target="#modalConciliar"
                                    data-id="{{ $t->id }}">
                                    Pendiente
                                </button>
                                @elseif($t->estado === 'Conciliada')
                                <span class="badge badge-success">Conciliada</span>
                                @else
                                {{ $t->estado }}
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
                            <td colspan="17" class="text-center text-muted">No hay transferencias</td>
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
                            <thead class="table-dark">
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



@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let tabla;
    $(document).ready(function() {
        tabla = $('#tabla-transferencias').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json") }}'
            }
        });

        $('.filtro-select').select2({
            theme: 'bootstrap4',
            placeholder: 'Seleccione una opción',
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            const colIndex = $(this).data('columna');
            const valor = $(this).val();

            if (valor) {
                tabla.column(colIndex).search('^' + $.fn.dataTable.util.escapeRegex(valor) + '$', true, false).draw();
            } else {
                tabla.column(colIndex).search('', true, false).draw();
            }
        });


        $('#reset-filtros').on('click', function() {
            $('.filtro-select').val('').trigger('change');
            tabla.columns().search('').draw();
        });

        $('#modalConciliar').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let transferenciaId = button.data('id');
            $(this).find('.input-transferencia-id').val(transferenciaId);
        });

        $('#tabla-cotizaciones').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json") }}'
            }
        });
    });
</script>
@endsection