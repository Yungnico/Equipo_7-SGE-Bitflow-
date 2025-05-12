@extends('adminlte::page')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <h2 class="mb-3">Contactos de {{ $cliente->nombre_fantasia }}</h2>

    <a href="{{ route('clientes.contactos.create', $cliente->id) }}" class="btn btn-primary mb-3">Agregar contacto</a>

    @if ($cliente->contactos->isEmpty())
        <div class="alert alert-info">No hay contactos registrados.</div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="contactos-table" class="table table-bordered table-hover w-100">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cliente->contactos as $contacto)
                                <tr>
                                    <td>{{ $contacto->nombre_contacto }}</td>
                                    <td>{{ $contacto->email_contacto }}</td>
                                    <td>{{ $contacto->telefono_contacto }}</td>
                                    <td>{{ $contacto->tipo_contacto }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('contactos.edit', $contacto->id) }}" class="btn btn-warning btn-sm mb-1">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clientes.contactos.destroy', [$cliente->id, $contacto->id]) }}" method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('js')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables (jQuery + DataTables) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#contactos-table').DataTable({
            language: {
                url: '{{ asset("datatables/es-CL.json")}}'
            }
        });

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
