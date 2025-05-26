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
<style>
    thead input,
    thead select {
        width: 100%;
        box-sizing: border-box;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Transferencias Bancarias</h1>
        <form action="{{ route('transferencias.importar') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="archivo" class="form-control" required>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarTransferencia">
        Agregar transferencia
    </button>

    <button type="button" id="reset-filtros" class="btn btn-secondary mb-3">Resetear Filtros</button>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-transferencias" class="table table-striped table-bordered nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>RUT</th>
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
                            <th>
                                <select class="form-select filtro-select" data-columna="0">
                                    <option value="">Nombre</option>
                                    @foreach($transferencias->pluck('nombre')->unique() as $nombre)
                                    <option value="{{ $nombre }}">{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select class="form-select filtro-select" data-columna="1" style="min-width: 150px;">
                                    <option value="">RUT</option>
                                    @foreach($transferencias->pluck('rut')->unique() as $rut)
                                    <option value="{{ $rut }}">{{ $rut }}</option>
                                    @endforeach
                                </select>
                            </th>
                            @for($i = 2; $i < 15; $i++)
                                <th>
                                </th>
                                @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transferencias as $t)
                        <tr>
                            <td>{{ $t->nombre }}</td>
                            <td>{{ $t->rut }}</td>
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
                            <td colspan="15" class="text-center text-muted">No hay datos</td>
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
    $(document).ready(function() {
        const tabla = $('#tabla-transferencias').DataTable({
            responsive: false,
            scrollX: true,
            paging: true,
            autoWidth: false,
            orderCellsTop: true // 👈 Esto es CLAVE
        });

        $('.filtro-select').select2({
            width: '100%'
        });

        $('.filtro-select').on('change', function() {
            const col = $(this).data('columna');
            const val = $(this).val();
            tabla.column(col).search(val).draw();
        });
    });
</script>

<script>
    $('#reset-filtros').on('click', function() {
        $('.filtro-select').val('').trigger('change');
        tabla.columns().search('').draw();
    });
</script>

@endsection