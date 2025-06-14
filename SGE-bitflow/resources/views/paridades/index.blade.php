@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Paridades</h1>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success">{{ session('success') }}</x-adminlte-alert>
    @endif
    @if (session('warning'))
        <x-adminlte-alert theme="warning">{{ session('warning') }}</x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger">{{ session('error') }}</x-adminlte-alert>
    @endif

    <a href="{{ route('paridades.fetch') }}" class="btn btn-success mb-3">Actualizar</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Paridades</h3>
        </div>
        <div class="card-body">
            <table id="tabla-paridades" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Moneda</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paridades as $grupo)
                        @php
                            $p = $grupo->first(); // Tomamos el primer registro de cada grupo
                        @endphp
                        <tr>
                            <td>{{ $p->moneda }}</td>
                            <td>${{ number_format($p->valor, 2, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('paridades.edit', $p) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit" style="color: white"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($paridades->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No hay paridades registradas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    {{-- CDN de DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-paridades').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("datatables/es-CL.json")}}'
                }
            });
        });
    </script>
@stop


