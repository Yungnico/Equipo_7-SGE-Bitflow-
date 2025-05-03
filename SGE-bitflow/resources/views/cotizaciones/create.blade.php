@extends('adminlte::page')
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2Plugin', true)
@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div id="accordion" class="pt-3">
    <div class="card">
        <div class="card-header text-center" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <h4>Datos Clientes</h4>
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
            <div class="card"></div>
            <div class="card-body">
                <form>
                    <div class="form-row">
                        <!-- Cliente -->
                        <div class="form-group col-md-6">
                            <label for="id_cliente">Cliente:</label>
                            <div class="input-group">
                                <select class="form-control" id="id_cliente" name="cliente">
                                    <option>- Selecciona un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top" title="Aquí debes Seleccionar el cliente al que se le va a realizar la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                                <div class="input-group-append">
                                    <a href="{{ route('clientes.create') }}"   class="btn btn-outline-primary" type="button">Agregar Cliente Nuevo</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- No. Cotización -->
                        <div class="form-group col-md-6">
                            <label for="cotizacion">No. Cotización:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="cotizacion" name="cotizacion" value="{{ $ultimoId }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"  data-toggle="tooltip" data-placement="top" title="Aquí se muestra el número de cotización generado automáticamente por el sistema.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <!-- RUT -->
                        <div class="form-group col-md-6">
                            <label for="rut">Rut - Cliente:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="rut" name="rut" value="">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top" title="Aquí se muestra el RUT del cliente seleccionado, en caso de error, cambiar">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- EMAIL -->
                        <div class="form-group col-md-6">
                            <label for="Email">Email - Cliente:</label>
                            <div class="input-group">
                                <select class="form-control" id="Email" name="Email">
                                    <option value="">Seleccione un Email</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"  data-toggle="tooltip" data-placement="top" title="Aquí debes seleccionar el email del cliente al que se le va a enviar la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- Telefono -->
                        <div class="form-group col-md-6">
                            <label for="telefono">Telefono - Cliente:</label>
                            <div class="input-group">
                                <select class="form-control" id="telefono" name="telefono">
                                    <option value="">Seleccione un telefono</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top" title="Aquí debes seleccionar el telefono del cliente al que se le va a enviar la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Moneda -->
                        <div class="form-group col-md-6">
                            <label for="moneda">Moneda:</label>
                            <div class="input-group">
                                <select class="form-control" id="moneda_cotizacion" name="moneda">
                                    <option value="">Seleccione una moneda</option>
                                    <option value="USD">USD - EEUU (Dolares)</option>
                                    <option value="CLP">CLP - Chile (Pesos Chilenos)</option>
                                    <option value="UF">UF - Chile (Unidad de Fomento)</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top" title="Aquí debes seleccionar la moneda en la que se va a realizar la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- Vigencia -->
                        <div class="form-group col-md-6">
                            <label for="vigencia">Fecha de Vigencia:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="fecha" name="vigencia">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí debes seleccionar la fecha de vigencia de la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkboxes -->
                    <div class="form-group">
                        <label>Aplica General:</label>
                        <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input" type="checkbox" id="iva" value="1">
                            <label class="form-check-label" for="iva">Calcular IVA: *</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
    <div class="card">
        <div class="card-header text-center" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4>Servicios</h4>
                </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
            <div class="card-body">
                <!-- Formulario de producto -->
                <form id="productoForm">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="servicio">Servicios:</label>
                            <div class="input-group">
                                <select class="form-control" id="servicio" name="servicio">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->id }}">{{$servicio->id}} - {{$servicio->nombre_servicio }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí debes seleccionar el servicio que deseas agregar a la cotización.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="descripcion">Descripción:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="descripcion" name="descripcion" value="" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí se mostrara la descripcion del servicio seleccionado.">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" value="1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Precio Unitario:</label>
                            <input type="number" class="form-control" id="precio" value="1500" required>
                        </div>
                        <input type="hidden" class="form-control" id="moneda" value="">
                    </div>
                    <button type="submit" class="btn btn-success allign-center">Agregar Producto al Catálogo</button>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseTwo">
                    <h4>Items Libres</h4>
                </button>
            </h5>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo">
            <div class="card-body">
                <!-- Formulario de producto -->
                <form id="productoForm">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Servicios:</label>
                            <select class="form-control" id="producto">
                                <option value="">Seleccione un producto</option>
                                @foreach ($servicios as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->nombre_servicio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Clave Producto:</label>
                            <input type="text" class="form-control" id="claveProducto" value="" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" value="1" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Precio Unitario:</label>
                            <input type="number" class="form-control" id="precio" value="1500" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Moneda:</label>
                            <input type="text" class="form-control" id="moneda">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success allign-center">Agregar Producto al Catálogo</button>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="card mt-4">
        <div class="card-header"><h3 class="card-title">Productos Agregados</h3></div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>No.</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Precio Unitario</th>
                            <th>Importe</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProducto"></tbody>
                    <tfoot>
                        <tr style="background:#fffbe6">
                            <td colspan="4" class="text-right font-weight-bold">IVA 16%</td>
                            <td colspan="2" id="ivaTotal">$0.00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold">Subtotal:</td>
                            <td colspan="2" id="subtotal">$0.00</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold">Total:</td>
                            <td colspan="2" id="total">$0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
    {{-- flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#fecha", {
        dateFormat: "Y-m-d"
    });
    $(document).ready(function() {
        const productosGuardados = [];
        const itemslibresGuardados = [];
        function actualizarTabla() {
            const tabla = document.getElementById('tablaProducto');
            tabla.innerHTML = '';

            let subtotal = 0;
            let ivaTotal = 0;
            let total = 0;

            productosGuardados.forEach((p, i) => {
                const importe = p.cantidad * p.precio;
                const iva = importe * 0.16;
                const totalProducto = importe + iva;

                subtotal += importe;
                ivaTotal += iva;
                total += totalProducto;

                const fila = `<tr>
                                <td>${i + 1}</td>
                                <td>${p.cantidad}</td>
                                <td>${p.descripcion}</td>
                                <td>$${p.precio.toFixed(2)}</td>
                                <td>$${importe.toFixed(2)}</td>
                                <td>
                                <button class="btn btn-sm btn-danger eliminar-btn" data-index="${i}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;

                tabla.insertAdjacentHTML('beforeend', fila);
                document.querySelectorAll('.eliminar-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const index = this.getAttribute('data-index');
                        productosGuardados.splice(index, 1);
                        console.log('productosGuardados', productosGuardados);
                        actualizarTabla();
                    });
                });
            });

            // Totales
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('iva').textContent = `$${ivaTotal.toFixed(2)}`;
            document.getElementById('ivaTotal').textContent = `$${ivaTotal.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }
        function eliminarProducto(index) {
            productosGuardados.splice(index, 1);
            actualizarTabla();
        }

        document.getElementById('productoForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const servicio = document.getElementById('servicio').value;
            const cantidad = parseInt(document.getElementById('cantidad').value);
            const precio = parseFloat(document.getElementById('precio').value);
            const moneda = document.getElementById('moneda').value;
            const descripcion = document.getElementById('descripcion').value;

            const moneda_cotizacion = document.getElementById('moneda_cotizacion').value;
            if(moneda != moneda_cotizacion){
                Swal.fire({
                    title: "La moneda de la cotización no coincide con la del producto",
                    text: `¿Desea hacer la transofomación de la moneda?,de moneda ${moneda} a ${moneda_cotizacion}`,
                    icon: "warning",
                    showDenyButton: true,
                    confirmButtonText: "Aceptar",
                    denyButtonText: "Cancelar",
                    }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        Swal.fire("Saved!", "", "success");
                    } else if (result.isDenied) {
                        Swal.fire("Changes are not saved", "", "info");
                    }
                });
            }
            productosGuardados.push({
            servicio,
            cantidad,
            descripcion,
            precio,
            moneda
            });

            actualizarTabla();
            document.getElementById('productoForm').reset();
        });
        $('#id_cliente').on('change',function(){
            const clienteId = $(this).val();
            $.ajax({
                url: '/clientes/' + clienteId + '/info',
                type: 'GET',
                success: function(data) {
                    $('#rut').val(data.rut);
                },
                error: function(xhr) {
                    $('#rut').val('Seleccione un cliente');
                }
            });
        })
        $('#id_cliente').on('change',function(){
            const clienteId = $(this).val();
            let options = '<option value="">Seleccione un Email</option>';
            let option2s = '<option value="">Seleccione un Telefono</option>';
            $.ajax({
                url: '/clientes/' + clienteId + '/contactos/info',
                type: 'GET',
                success: function(data) {
                    data.forEach(contacto => {
                        options += `<option value="${contacto.email_contacto}">${contacto.email_contacto} - ${contacto.tipo_contacto}</option>`; 
                        option2s += `<option value="${contacto.telefono_contacto}">${contacto.telefono_contacto} - ${contacto.tipo_contacto}</option>`;             
                    });
                    $('#Email').html(options);
                    $('#telefono').html(option2s);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        })
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

    });
    $('#servicio').on('change', function() {
        const productoId = $(this).val();
        $.ajax({
            url: '/servicios/' + productoId + '/info',
            type: 'GET',
            success: function(data) {
                $('#descripcion').val(data.descripcion);
                $('#precio').val(data.precio);
                $('#moneda').val(data.moneda);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    });
</script>
@stop

@section('css')

@endsection
