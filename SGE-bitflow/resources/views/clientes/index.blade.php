@extends('adminlte::page')

@section('title', 'Clientes')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Clientes</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario de búsqueda --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('clientes.index') }}" method="GET">
                <div class="form-row row">
                    <div class="form-group col-12 col-md-4">
                        <input type="text" name="razon_social" class="form-control" placeholder="Razón social" value="{{ request('razon_social') }}">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <input type="text" name="rut" class="form-control" placeholder="RUT" value="{{ request('rut') }}">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <input type="text" name="nombre_fantasia" class="form-control" placeholder="Nombre fantasía" value="{{ request('nombre_fantasia') }}">
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="fas fa-broom"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <div class="col-md-6 mb-2 mb-md-0">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('clientes.exportar', array_merge(request()->all(), ['formato_exportacion' => 'pdf'])) }}" class="btn btn-success">Exportar a PDF</a>
        </div>
    </div>


    @if($clientes->count())
        <div class="card">
            <div class="card-body">
                
                    <table id="clientes-table" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Razón social</th>
                                <th>RUT</th>
                                <th>Nombre fantasía</th>
                                <th>Giro</th>
                                <th>Dirección</th>
                                <th>Logo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->razon_social }}</td>
                                    <td>{{ $cliente->rut }}</td>
                                    <td>{{ $cliente->nombre_fantasia }}</td>
                                    <td>{{ $cliente->giro }}</td>
                                    <td>{{ $cliente->direccion }}</td>
                                    <td>
                                        @if($cliente->logo)
                                            <img src="{{ asset('storage/' . $cliente->logo) }}" class="img-fluid" style="max-width: 80px;">
                                        @else
                                            Sin logo
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm mb-1">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm mb-1">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('clientes.contactos.index', [$cliente->id, $cliente->nombre_fantasia]) }}" class="btn btn-primary btn-sm mb-1">
                                            <i class="fas fa-id-badge"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
            </div>
        </div>
    @else
        <div class="alert alert-info">
            No se encontraron resultados.
        </div>
    @endif
</div>
@endsection


@section('js')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar DataTable
            $('#clientes-table').DataTable({
                language: {
                    responsive: true,
                    autoWidth: false,
                    url: '{{ asset("datatables/es-CL.json")}}'
                }
            });

            // Confirmación SweetAlert
            const forms = document.querySelectorAll('.form-eliminar');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "¡Esta acción no se puede deshacer!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminarlo",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
