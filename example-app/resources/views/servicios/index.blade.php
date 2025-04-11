<!DOCTYPE html>
<html>

<head>
    <title>Lista de Servicios</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Servicios Registrados</h1>
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearServicio">
                Agregar Servicio
            </button>

        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($servicios->isEmpty())
        <div class="alert alert-warning">No hay servicios registrados.</div>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Moneda</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->descripcion }}</td>
                        <td>${{ number_format($servicio->precio, 0, ',', '.') }}</td>
                        <td>{{ $servicio->moneda }}</td>
                        <td class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarServicio"
                                data-id="{{ $servicio->id }}"
                                data-nombre="{{ $servicio->nombre_servicio }}"
                                data-descripcion="{{ $servicio->descripcion }}"
                                data-precio="{{ $servicio->precio }}"
                                data-moneda="{{ $servicio->moneda }}">
                                Editar
                            </button>

                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Crear Servicio -->
    <div class="modal fade" id="modalCrearServicio" tabindex="-1" aria-labelledby="crearServicioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('servicios.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearServicioLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_servicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" name="precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Moneda</label>
                        <select name="moneda" class="form-select" required>
                            <option value="">Seleccione una moneda</option>
                            <option value="CLP">CLP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <!-- Agrega más monedas si necesitas -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Servicio -->
    <div class="modal fade" id="modalEditarServicio" tabindex="-1" aria-labelledby="editarServicioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formEditarServicio" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre_servicio" id="editar-nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="editar-descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Precio</label>
                        <input type="number" name="precio" id="editar-precio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Moneda</label>
                        <select name="moneda" id="editar-moneda" class="form-select" required>
                            <option value="CLP">CLP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <!-- Más si quieres -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editarModal = document.getElementById('modalEditarServicio');
            editarModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                // Obtiene datos del botón
                var id = button.getAttribute('data-id');
                var nombre = button.getAttribute('data-nombre');
                var descripcion = button.getAttribute('data-descripcion');
                var precio = button.getAttribute('data-precio');
                var moneda = button.getAttribute('data-moneda');

                // Rellena los campos del formulario
                document.getElementById('editar-id').value = id;
                document.getElementById('editar-nombre').value = nombre;
                document.getElementById('editar-descripcion').value = descripcion;
                document.getElementById('editar-precio').value = precio;
                document.getElementById('editar-moneda').value = moneda;

                // Actualiza la acción del formulario
                var form = document.getElementById('formEditarServicio');
                form.action = '/servicios/' + id; // Asegúrate de que coincida con tu ruta update
            });
        });
    </script>



</body>

</html>