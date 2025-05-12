@extends('adminlte::page')
@section('plugins.Datatables', true)

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
                        <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $cotizacion->id_cotizacion }}').submit();">
                            <i class="fas fa-trash"></i>
                        </a>
                        <form id="delete-form-{{ $cotizacion->id_cotizacion }}" action="{{ route('cotizaciones.destroy', $cotizacion->id_cotizacion) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
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
                url: '{{ asset("datatables/es-CL.json")}}'
            }
        });
    });
    
    $(window).on('resize', function() {
            table.columns.adjust().responsive.recalc();
        });
</script>
@stop
