@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Clientes</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('clientes.exportar', array_merge(request()->all(), ['formato_exportacion' => 'pdf'])) }}" class="btn btn-success">Exportar a PDF</a>
    
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario de búsqueda --}}
    <div class="card mb-3 mt-3">
        <div class="card-body">
            <form action="{{ route('clientes.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="razon_social" class="form-control" placeholder="Razón social" value="{{ request('razon_social') }}">
                </div>
                <div class="form-group mr-2">
                    <input type="text" name="rut" class="form-control" placeholder="RUT" value="{{ request('rut') }}">
                </div>
                <div class="form-group mr-2">
                    <input type="text" name="nombre_fantasia" class="form-control" placeholder="Nombre fantasía" value="{{ request('nombre_fantasia') }}">
                </div>
                <button type="submit" class="btn btn-primary mr-2"><i class="fa fa-search" aria-hidden="true"></i></button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="fas fa-broom"></i></a>
            </form>
        </div>
    </div>

    {{-- Botón crear cliente --}}
    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>

    {{-- Tabla de clientes --}}
    @if($clientes->count())
        <table id="clientes-table" class="table table-bordered">
            <thead>
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
                                <img src="{{ asset('storage/' . $cliente->logo) }}" width="80">
                            @else
                                Sin logo
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="form-eliminar" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <a href="{{ route('clientes.contactos.index', [$cliente->id, $cliente->nombre_fantasia]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-id-badge"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar DataTable
        $('#clientes-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
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
