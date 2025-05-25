@extends('adminlte::page')

@section('title', 'Transferencias Bancarias')

@section('content_header')
<h1></h1>
@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
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
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="text-center text-muted">No hay datos</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

<script>
    $(document).ready(function() {
        $('#tabla-transferencias').DataTable({
            responsive: false,
            scrollX: true,
            paging: true,
            autoWidth: false
        });
    });
</script>
@endsection