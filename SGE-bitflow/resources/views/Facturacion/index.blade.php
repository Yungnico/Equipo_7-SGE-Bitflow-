@extends('adminlte::page')
@section('title', 'Cotizaciones')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('content')
<body>
    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    <div class="content pt-4">

        <div class="row align-items-center mb-3">
            <div class="col-md-6 mb-2 mb-md-0">
                <!--<a href=" route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a> -->
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalfacturas">Importar Facturas</button>
            
            </div>
            <div class="col-md-6 text-md-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal_agregacionfacturas">Agregar Facturas</button>
            </div>
        </div>
        <div class="card p-3 mt-4">
            <div class="content">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <th>Folio</th>
                        <th>Tipo DTE </th>
                        <th>Fecha Emision</th>
                        <th>Cliente</th>
                        <th>Razon Social</th>
                        <th>Total Neto</th>
                        <th>Iva</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr>
                                <td>{{ $factura->folio }}</td>
                                <td>{{ $factura->tipo_dte }}</td>
                                <td>{{ $factura->fecha_emision }}</td>
                                <td>{{ $factura->rut_receptor }}</td>
                                <td>{{ $factura->razon_social_receptor }}</td>
                                <td>{{ number_format($factura->total_neto, 2, ',', '.') }}</td>
                                <td>{{ number_format($factura->iva, 2, ',', '.') }}</td>
                                <td>{{ number_format($factura->total, 2, ',', '.') }}</td>
                                <td>{{ $factura->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="modalfacturas" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('facturacion.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Importar Facturas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div
                        id="dropzone"
                        class="border border-secondary rounded p-4 text-center bg-light"
                        style="cursor: pointer;"
                        onclick="document.getElementById('archivo').click();"
                        ondragover="event.preventDefault(); this.classList.add('border-primary');"
                        ondragleave="this.classList.remove('border-primary');"
                        ondrop="handleDrop(event);"
                        >
                        <div class="mb-2">
                            <i class="fas fa-upload fa-2x text-secondary"></i>
                        </div>
                        <p class="mb-0 text-muted">Arrastra tu archivo CSV aquí o haz clic para seleccionarlo</p>
                        <small id="file-name" class="form-text text-muted mt-2"></small>
                    </div>
                    <input type="file" name="archivo" id="archivo" class="d-none" accept=".csv" onchange="showFileName(this)" required>
                    <button type="submit" class="btn btn-primary mt-3">
                        Importar CSV
                    </button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                "language": {
                    responsive: true,
                    autoWidth: true,
                    url: '{{ asset("datatables/es-CL.json")}}'
                }
            });
        });
        
        $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
            });
    </script>
    <script>
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropzone').classList.remove('border-blue-500');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const input = document.getElementById('archivo');
            input.files = files;
            showFileName(input);
        }
    }

    function showFileName(input) {
        const fileNameDisplay = document.getElementById('file-name');
        if (input.files.length > 0) {
            fileNameDisplay.textContent = "Archivo seleccionado: " + input.files[0].name;
        } else {
            fileNameDisplay.textContent = "";
        }
    }
</script>
@endsection