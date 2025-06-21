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
 
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearParidad">
        Agregar Paridad
    </button>

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
                    
                    @foreach ($paridades as $p)
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
                    {{-- Repetir el bloque anterior para cada paridad --}}

                    {{-- Si no hay paridades, mostrar un mensaje --}}

                    @if ($paridades->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">No hay paridades registradas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="modalCrearParidad" tabindex="-1" role="dialog" aria-labelledby="modalCrearParidadLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('paridades.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearParidadLabel">Agregar Nueva Paridad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="moneda">Moneda</label>
                        <select class="form-control" id="moneda" name="moneda" required>
                            <option value="">Seleccione una moneda</option>
                            <option value="CLP">CLP</option>
                            <option value="USD">USD</option>
                            <option value="UF">UF</option>
                            <option value="UTM">UTM</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="CHF">CHF</option>
                            <option value="JPY">JPY</option>
                            <option value="HKD">HKD</option>
                            <option value="CAD">CAD</option>
                            <option value="CNY">CNY</option>
                            <option value="AUD">AUD</option>
                            <option value="BRL">BRL</option>
                            <option value="RUB">RUB</option>
                            <option value="MXN">MXN</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@stop