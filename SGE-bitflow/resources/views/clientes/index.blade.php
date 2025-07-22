@extends('adminlte::page')

@section('title', 'Clientes')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row align-items-center mb-3 pt-3">
        <div class="col-md-6 mb-2 mb-md-0 ">
            
        </div>
        <div class="col-md-6 text-md-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCliente">Crear Cliente</button>
            <a href="{{ route('clientes.exportar', array_merge(request()->all(), ['formato_exportacion' => 'pdf'])) }}" class="btn btn-success">Exportar a PDF</a>
        </div>
    </div>

    @if($clientes->count())
    <div class="card">
        <div class="card-body">

            <table id="clientes-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Razón social</th>
                        <th>RUT</th>
                        <th>Nombre fantasía</th>
                        <th>Giro</th>
                        <th>Dirección</th>
                        <th>Logo</th>
                        <th>Días Hábiles</th>
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
                        <td>{{ $cliente->plazo_pago_habil_dias }}</td>
                        <td class="text-nowrap text-center">
                            <button class="btn btn-sm btn-outline-primary btn-sm mb-1" data-toggle="modal" data-target="#modalEditarCliente{{$cliente->id}}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Modal Editar Cliente -->
                            <div class="modal fade" id="modalEditarCliente{{$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditarClienteLabel{{$cliente->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <form id="formEditarCliente{{$cliente->id}}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditarClienteLabel{{$cliente->id}}">Editar Cliente</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" name="id" value="{{$cliente->id}}">
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_razon_social{{$cliente->id}}">Razón Social*</label>
                                                        <input type="text" class="form-control" name="razon_social" id="editar_razon_social{{$cliente->id}}" value="{{$cliente->razon_social}}" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_rut{{$cliente->id}}">RUT*</label>
                                                        <input type="text" class="form-control" name="rut" id="editar_rut{{$cliente->id}}" value="{{$cliente->rut}}" required>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_nombre_fantasia{{$cliente->id}}">Nombre Fantasía</label>
                                                        <input type="text" class="form-control" name="nombre_fantasia" id="editar_nombre_fantasia{{$cliente->id}}" value="{{$cliente->nombre_fantasia}}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_giro{{$cliente->id}}">Giro</label>
                                                        <input type="text" class="form-control" name="giro" id="editar_giro{{$cliente->id}}" value="{{$cliente->giro}}">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_direccion{{$cliente->id}}">Dirección</label>
                                                        <input type="text" class="form-control" name="direccion" id="editar_direccion{{$cliente->id}}" value="{{$cliente->direccion}}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_logo{{$cliente->id}}">Logo (JPG o PNG)</label>
                                                        <input type="file" class="form-control" name="logo" id="editar_logo{{$cliente->id}}">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="editar_dias{{$cliente->id}}">Plazo de Pago (días hábiles)</label>
                                                        <input type="number" class="form-control" name="plazo_pago_habil_dias" id="editar_dias{{$cliente->id}}" value="{{$cliente->plazo_pago_habil_dias}}" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary btn-actualizar-cliente" data-id="{{$cliente->id}}">Actualizar</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Fin Modal Editar Cliente -->
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="form-eliminar d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm mb-1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <a href="{{ route('clientes.contactos.index', [$cliente->id, $cliente->nombre_fantasia]) }}" class="btn btn-outline-warning btn-sm mb-1">
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
<!-- Modal para Crear Cliente -->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Crear Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="razon_social">Razón Social*</label>
                            <input type="text" class="form-control" name="razon_social" value="{{ old('razon_social') }}" maxlength="100" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="rut">RUT*</label>
                            <input type="text" class="form-control" placeholder="11111111-1" name="rut" value="{{ old('rut') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre_fantasia">Nombre Fantasía</label>
                            <input type="text" class="form-control" name="nombre_fantasia" value="{{ old('nombre_fantasia') }}" maxlength="100">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="giro">Giro</label>
                            <input type="text" class="form-control" name="giro" value="{{ old('giro') }}" maxlength="100">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" name="direccion" value="{{ old('direccion') }}" maxlength="150">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="logo">Logo (JPG o PNG)</label>
                            <input type="file" class="form-control" name="logo">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="plazo_pago_habil_dias">Plazo de pago (días hábiles)</label>
                            <input type="number" class="form-control" name="plazo_pago_habil_dias" value="{{ old('plazo_pago_habil_dias', $cliente->plazo_pago_habil_dias ?? '') }}" min="0">
                        </div>
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
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DataTable
        $('#clientes-table').DataTable({
            responsive: true,
            language: {
                url: '{{ asset("datatables/es-CL.json")}}'
            }
        });

        // Confirmación SweetAlert para eliminar
        const forms = document.querySelectorAll('.form-eliminar');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
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
<script>
$(document).ready(function() {
    // AJAX para actualizar cliente
    $('form[id^="formEditarCliente"]').on('submit', function(e) {
        e.preventDefault();
        var id_cliente = $(this).find('input[name="id"]').val();
        var form = $(this);
        var id = form.find('.btn-actualizar-cliente').data('id');
        var formData = new FormData(this);
        formData.append('_method', 'PUT'); // Asegurar que se envíe el método PUT
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // Agregar CSRF token
        formData.append('id',id_cliente); // Asegurar que el ID se envíe correctamente
        $.ajax({
            url: '/clientes/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                Swal.fire('¡Actualizado!', 'El cliente fue actualizado correctamente.', 'success').then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = 'Error al actualizar el cliente.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire('Error', msg, 'error');
            }
        });
    });
});
</script>
@endsection