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
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $cotizacion->estado }}</span>
                            <a href="{{ route('cotizaciones.edit', $cotizacion->id_cotizacion) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('cotizaciones.prepararPDF', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('cotizaciones.prepararEmail', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-envelope"></i>
                        </a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        $(document).ready(function () {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif
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