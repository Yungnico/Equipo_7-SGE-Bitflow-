@extends('adminlte::page')

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
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary ml-2"><i class="fas fa-broom"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <div class="col-md-6 mb-2 mb-md-0">
            <!--<a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a> -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCliente">Crear Cliente</button>

        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('clientes.exportar', array_merge(request()->all(), ['formato_exportacion' => 'pdf'])) }}" class="btn btn-success">Exportar a PDF</a>
        </div>
    </div>


    @if($clientes->count())
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="clientes-table" class="table table-bordered table-hover w-100">
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
                                        <button class="btn btn-sm btn-warning btn-sm mb-1" onclick="abrirModalEditar({{ $cliente }})">
                                            <i class="fas fa-edit" style="color:white"></i>
                                        </button>

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
        </div>
    @else
        <div class="alert alert-info">
            No se encontraron resultados.
        </div>
    @endif
</div>
@endsection

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
                        <input type="text" class="form-control" name="rut" value="{{ old('rut') }}" required>
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
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="formEditarCliente" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editar_razon_social">Razón Social*</label>
              <input type="text" class="form-control" name="razon_social" id="editar_razon_social" required>
            </div>
            <div class="form-group col-md-6">
              <label for="editar_rut">RUT*</label>
              <input type="text" class="form-control" name="rut" id="editar_rut" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editar_nombre_fantasia">Nombre Fantasía</label>
              <input type="text" class="form-control" name="nombre_fantasia" id="editar_nombre_fantasia">
            </div>
            <div class="form-group col-md-6">
              <label for="editar_giro">Giro</label>
              <input type="text" class="form-control" name="giro" id="editar_giro">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editar_direccion">Dirección</label>
              <input type="text" class="form-control" name="direccion" id="editar_direccion">
            </div>
            <div class="form-group col-md-6">
              <label for="editar_logo">Logo (JPG o PNG)</label>
              <input type="file" class="form-control" name="logo" id="editar_logo">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Actualizar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>



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
<script>
  function abrirModalEditar(cliente) {
    // Rellenar campos
    document.getElementById('editar_razon_social').value = cliente.razon_social;
    document.getElementById('editar_rut').value = cliente.rut;
    document.getElementById('editar_nombre_fantasia').value = cliente.nombre_fantasia ?? '';
    document.getElementById('editar_giro').value = cliente.giro ?? '';
    document.getElementById('editar_direccion').value = cliente.direccion ?? '';

    // Actualizar acción del formulario
    const form = document.getElementById('formEditarCliente');
    form.action = `/clientes/${cliente.id}`; // Asegúrate de que esta ruta coincida con la definida en tus routes

    // Mostrar modal
    $('#modalEditarCliente').modal('show');
  }
</script>

@endsection
