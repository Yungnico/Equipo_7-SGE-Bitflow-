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
    <!-- Aqui deberia ir un form pero no funciona !! <form id='cotizacionform'> -->
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
                            <div class="form-group col-md-6">
                                <label for="dcto">Descuento:</label>
                                <div class="input-group">
                                    <input type="number" id="dcto" name="dcto" class="form-control" min="0" max="100" step="0.01" placeholder="Ingrese el descuento (máx. 100%)">
                                    <div class="input-group-append">    
                                        <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aqui debe ir el descuento en % que se le va a aplicar a la cotización.">
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
                                    <select class="form-control" id="servicio" name="servicio" required>
                                        <option value="" >Seleccione un producto</option>
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
                                <input type="text" class="form-control" id="moneda" value="" disabled>
                            </div>  
                        </div>
                        <button type="submit" class="btn btn-success allign-center">Agregar Producto al Catálogo</button>
                    </form>
                </div>
            </div> 
        </div>
        <div class="card">
            <div class="card-header text-center" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#ItemsL" aria-expanded="false" aria-controls="collapseTwo">
                        <h4>ItemsLibres</h4> 
                    </button>
                    <span data-toggle="tooltip" data-placement="top" title="No es necesario agregar items libres" style="cursor: help;
                        color: #ff0000;">
                        *
                    </span>
                </h5>
            </div>
            <div id="ItemsL" class="collapse" aria-labelledby="headingTwo">
                <div class="card-body">
                    <!-- Formulario de producto -->
                    <form id="ItemsLform">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="itemL">Items Libres:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="itemsL" name="itemsL" value="" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aqui debes ingresar el nombre del item libre que deseas agregar a la cotización.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="descripcion">Descripción:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="descripcion_itemsL" name="descripcion" value="" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí debes ingresar la descripcion del servicio seleccionado.">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad_itemsL" value="1" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Precio Unitario:</label>
                                <input type="number" class="form-control" id="precio_itemsL" value="1500" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success allign-center">Agregar Producto al Catálogo</button>
                    </form>
                </div>
            </div> 
        </div>
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
                                <td colspan="4" class="text-right font-weight-bold">IVA 19%</td>
                                <td colspan="2" id="ivaTotal">$0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Subtotal:</td>
                                <td colspan="2" id="subtotal">$0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total sin descuento %:</td>
                                <td colspan="2" id="total">$0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total con descuento %:</td>
                                <td colspan="1" id="total_dcto">$0.00</td>
                                <td colspan="1" id="">
                                    <button type="submit" id="guardarCBtn" class="btn btn-primary" id="guardarCotizacion">Guardar Cotización</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <!-- Aqui deberia ir un form pero no funciona !! </form> -->
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
        const serviciosGuardados = [];
        const productosGuardados = [];
        const itemslibresGuardados = [];
        function actualizarTabla() {
            const tabla = document.getElementById('tablaProducto');
            tabla.innerHTML = '';
            let i = 0;
            let subtotal = 0;
            let ivaTotal = 0
            let totaldcto = 0;
            let total = 0;
            const dcto = document.getElementById('dcto').value;
            productosGuardados.forEach((p,indexp) => {
                const importe = p.cantidad * p.precioConvertido;
                const iva = importe * 0.19; // 19% de IVA
                const totalProducto = (importe + iva);
                const totalProductoDcto = (importe + iva) - (importe * dcto / 100);

                subtotal += importe;
                ivaTotal += iva;
                total += totalProducto;
                totaldcto += totalProductoDcto;	

                const fila = `<tr>
                                <td>${i + 1}</td>
                                <td>${p.cantidad}</td>
                                <td>${p.descripcion}</td>
                                <td>$${p.precioConvertido.toFixed(2)}</td>
                                <td>$${importe.toFixed(2)}</td>
                                <td>
                                <button class="btn btn-sm btn-danger eliminar-btn" data-index="${indexp}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;
                            //console.log('fila', i);

                tabla.insertAdjacentHTML('beforeend', fila);
                document.querySelectorAll('.eliminar-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const index = this.getAttribute('data-index');
                        productosGuardados.splice(index, 1);
                        //console.log('productosGuardados', productosGuardados);
                        if (productosGuardados.length == 0) {
                            document.getElementById('moneda_cotizacion').disabled = false;
                        }
                        actualizarTabla();
                    });
                });
                i++;
            });
            itemslibresGuardados.forEach((p,index) => {
                const importe_itemsL = p.cantidad_itemsL * p.precio_itemsL;
                const iva_itemsL = importe_itemsL * 0.19; // 19% de IVA
                const totalProducto_itemsL = importe_itemsL + iva_itemsL;
                const totalProducto_itemsLDcto = (importe_itemsL + iva_itemsL) - (importe_itemsL * dcto / 100);

                subtotal += importe_itemsL;
                ivaTotal += iva_itemsL;
                total += totalProducto_itemsL;
                totaldcto += totalProducto_itemsLDcto;

                const fila = `<tr>
                                <td>${i + 1}</td>
                                <td>${p.cantidad_itemsL}</td>
                                <td>${p.descripcion_itemsL}</td>
                                <td>$${p.precio_itemsL.toFixed(2)}</td>
                                <td>$${importe_itemsL.toFixed(2)}</td>
                                <td>
                                <button class="btn btn-sm btn-danger eliminar-btn" data-index="${index}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>`;
                            //console.log('fila', i);

                tabla.insertAdjacentHTML('beforeend', fila);
                document.querySelectorAll('.eliminar-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const index = this.getAttribute('data-index');
                        itemslibresGuardados.splice(index, 1);
                        //console.log('productosGuardados', productosGuardados);
                        if (itemslibresGuardados.length == 0) {
                            document.getElementById('moneda_cotizacion').disabled = false;
                        }
                        actualizarTabla();
                    });
                });
                i++;
            });
            // Totales
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('ivaTotal').textContent = `$${ivaTotal.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            document.getElementById('total_dcto').textContent = `$${totaldcto.toFixed(2)}`;
        }
        function eliminarProducto(index) {
            productosGuardados.splice(index, 1);
            actualizarTabla();
        }

        // ----------------------     PROVICIONAL     ----------------------
        function conversorMoneda(precio, moneda, moneda_cotizacion){
            let precioConvertido = 0;
            if(moneda == 'USD' && moneda_cotizacion == 'CLP'){
                precioConvertido = precio * 800; 
            } else if(moneda == 'CLP' && moneda_cotizacion == 'USD'){
                precioConvertido = precio / 800; 
            } else if(moneda == 'UF' && moneda_cotizacion == 'CLP'){
                precioConvertido = precio * 30000; 
            } else if(moneda == 'CLP' && moneda_cotizacion == 'UF'){
                precioConvertido = precio / 30000; 
            }
            return precioConvertido;
        }

        document.getElementById('ItemsLform').addEventListener('submit', function(e) {
            e.preventDefault();
            const itemLibre = document.getElementById('itemsL').value;
            const cantidad_itemsL = parseInt(document.getElementById('cantidad_itemsL').value);
            const precio_itemsL = parseFloat(document.getElementById('precio_itemsL').value);
            const descripcion_itemsL = document.getElementById('descripcion_itemsL').value;
            itemslibresGuardados.push({
                itemLibre,
                cantidad_itemsL,
                descripcion_itemsL,
                precio_itemsL,
                moneda
            });
            actualizarTabla();
            const form = document.getElementById('ItemsLform');
            if (form){
                form.reset();

            }else{
                console.log('No se encontro el formulario');
            }
        });
        // Agregar producto al catálogo
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
                        if(document.getElementById('moneda_cotizacion').disabled == true){
                            Swal.fire("Saved!", "", "success");
                            const precioConvertido = conversorMoneda(precio, moneda, moneda_cotizacion);
                            ///console.log('precio', precioConvertido);
                            productosGuardados.push({
                                servicio,
                                cantidad,
                                descripcion,
                                precioConvertido,
  
                            });
                            actualizarTabla();
                            document.getElementById('productoForm').reset();
                        }else{
                            Swal.fire({
                                title: "Una Vez agregado el producto no se puede cambiar la moneda de la cotización",
                                text: `¿Desea aceptar que la moneda de la cotizacion sea : ${moneda_cotizacion} ?`,
                                icon: "warning",
                                showDenyButton: true,
                                confirmButtonText: "Aceptar",
                                denyButtonText: "Cancelar",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire("Saved!", "", "success");
                                    document.getElementById('moneda_cotizacion').disabled = true;
                                    const precioConvertido = conversorMoneda(precio, moneda, moneda_cotizacion);
                                    ///console.log('precio', precioConvertido);
                                    productosGuardados.push({
                                        servicio,
                                        cantidad,
                                        descripcion,
                                        precioConvertido,
                                        moneda        
                                    });
                                    actualizarTabla();
                                    document.getElementById('productoForm').reset();
                                } else if (result.isDenied) {
                                    Swal.fire("No se guardo el producto", "", "info");
                                }
                            });
                        }
                    } else if (result.isDenied) {
                        Swal.fire("Debe cambiar la moneda de la cotizacion para poder agregar un servicio ", "", "info");
                    }
                });
            }else{
                if(document.getElementById('moneda_cotizacion').disabled == true){
                    Swal.fire("Saved!", "", "success");
                    const precioConvertido = precio;
                    ///console.log('precio', precioConvertido);
                    productosGuardados.push({
                        servicio,
                        cantidad,
                        descripcion,
                        precioConvertido,
                        moneda        
                    });
                    actualizarTabla();
                    document.getElementById('productoForm').reset();
                }else{
                    Swal.fire({
                        title: "Una Vez agregado el producto no se puede cambiar la moneda de la cotización",
                        text: `¿Desea aceptar que la moneda de la cotizacion sea : ${moneda_cotizacion} ?`,
                        icon: "warning",
                        showDenyButton: true,
                        confirmButtonText: "Aceptar",
                        denyButtonText: "Cancelar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire("Saved!", "", "success");
                            document.getElementById('moneda_cotizacion').disabled = true;
                            const precioConvertido = precio;
                            ///console.log('precio', precioConvertido);
                            productosGuardados.push({
                                servicio,
                                cantidad,
                                descripcion,
                                precioConvertido,
                                moneda        
                            });
                            actualizarTabla();
                            document.getElementById('productoForm').reset();
                        } else if (result.isDenied) {
                            Swal.fire("No se guardo el producto", "", "info");
                        }
                    });
                }
            }
        });


        // Obtener el RUT del cliente seleccionado
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



        // Obtener los contactos del cliente seleccionado
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
         // Cambiar el valor del campo de descripción y precio al seleccionar un producto
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



        //  ************************************************************************************************************
        //  ***      * * * *   *       *  * * * * *  * * * * *  * * *      * * * * * *                               ***
        //  ***      *         *       *  *       *  *       *  *     *    *         *                               ***
        //  ***      *         *       *  *       *  * * * * *  *       *  *         *                               ***
        //  ***      *   * *   *       *  * * * * *  * *        *       *  * * * * * *                               ***
        //  ***      *     *   *       *  *       *  *   *      *     *    *         *                               ***
        //  ***      * * * *   * * * * *  *       *  *      *   * * *      *         *                               ***
        //  ************************************************************************************************************

        document.getElementById('guardarCBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const cliente = document.getElementById('id_cliente').value;
            const moneda = document.getElementById('moneda_cotizacion').value;
            const vigencia = document.getElementById('fecha').value;
            const dcto = document.getElementById('dcto').value;
            const email = document.getElementById('Email').value;
            const telefono = document.getElementById('telefono').value;
            const formData = new FormData();
            formData.append('email', email);    
            formData.append('telefono', telefono);
            formData.append('id_cliente', cliente);
            formData.append('moneda', moneda);
            formData.append('fecha_cotizacion', vigencia);
            formData.append('estado','Borrador');
            formData.append('descuento', dcto);
            productosGuardados.forEach((s, index) => {
                formData.append(`servicios[${index}][servicio]`, s.servicio);   
                formData.append(`servicios[${index}][cantidad]`, s.cantidad);
            });
            itemslibresGuardados.forEach((s, i) => {
                formData.append(`items_libres[${i}][nombre]`, s.itemLibre);
                formData.append(`items_libres[${i}][precio]`, s.precio_itemsL);
                formData.append(`items_libres[${i}][cantidad]`, s.cantidad_itemsL);
            });
            $.ajax({
                url: '/cotizaciones',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cotización guardada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar la cotización',
                        text: xhr.responseText
                    });
                }
            });
        });
    });
    
</script>
@stop