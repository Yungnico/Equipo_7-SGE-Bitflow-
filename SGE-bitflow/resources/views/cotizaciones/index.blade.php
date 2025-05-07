@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('content')
<div class="content py-5">
    <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Codigo Cotizacion</th>
                <th>Cliente </th>
                <th>Fecha</th>
                <th>Moneda</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->codigo_cotizacion }}</td>
                    <td>{{ $cotizacion->cliente->razon_social }}</td>
                    <td>{{ $cotizacion->fecha_cotizacion }}</td>
                    <td>{{ $cotizacion->moneda }}</td>
                    <td>{{ $cotizacion->estado }}</td>
                    <td>
                        <a href="{{ route('cotizaciones.prepararPDF', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-file-pdf"></i>
                        </a>                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    
@stop
@section('js')
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                "language": {
                    responsive: true,
                    autoWidth: true,
                    "url": "https://cdn.datatables.net/plug-ins/2.3.0/i18n/es-CL.json"
                }
            });
        });

        $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
            });
    </script>
@stop