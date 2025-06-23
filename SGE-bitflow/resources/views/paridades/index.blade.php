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

    <div class="text-right mb-3">
        <a href="{{ route('paridades.fetch') }}" class="btn btn-success mb-3">Actualizar</a>
 
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearParidad">
            Agregar paridad
        </button>
    </div>

    <div class="card">

        <div class="card-body">
            <table id="tabla-paridades" class="table table-bordered table-striped table-hover">
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
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('paridades.edit', $p) }}" class="btn btn-outline-primary btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('paridades.destroy', $p) }}" method="POST" class="form-eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
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
                            @foreach ($monedasDisponibles as $moneda)
                                <option value="{{ $moneda }}">{{ $moneda }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ now()->toDateString() }}">
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
    {{-- Sweet Alert para eliminar paridad --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('.form-eliminar');

            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevenir envío inmediato

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta paridad será eliminada.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Si confirma, enviar el formulario
                        }
                    });
                });
            });
        });
    </script>

@stop