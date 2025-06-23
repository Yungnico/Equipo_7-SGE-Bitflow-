@extends('adminlte::page')
@section('title', 'Facturación')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2Plugin', true)
@section('content')
<body>
    @if(session('success'))
        <input type="hidden" id="successMessage" value="{{session('success')}}">
    @endif
    @if(session('error'))
        <input type="hidden" id="errorMessage" value="{{session('error')}}">
    @endif
    <div class="content pt-4">


        <div class="mb-3 text-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalfacturas">Importar Facturas</button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal_agregacionfacturas">Agregar Facturas</button>
        </div>
        <div class="card p-3 mt-4">
            <div class="content">
                <table id="myTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <th>Folio</th>
                        <th>Tipo DTE </th>
                        <th>Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Razón Social</th>
                        <th>Total Neto</th>
                        <th>Iva</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr>
                                <td>{{ $factura->folio }}</td>
                                <td>{{ $factura->tipo_dte }}</td>
                                <td>{{ $factura->fecha_emision }}</td>
                                <td>{{ $factura->rut_receptor }}</td>
                                <td>{{ $factura->razon_social_receptor }}</td>
                                <td>{{ number_format($factura->total_neto, 2, ',', '.') }}</td>
                                <td>{{ number_format($factura->iva, 2, ',', '.') }}</td>
                                <td>{{ number_format($factura->total, 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $factura->estado }}</span>
                                        <button 
                                            class="btn btn-sm btn-outline-primary" 
                                            data-toggle="modal" 
                                            data-target="#modalEstado{{ $factura->id }}">
                                            <i class="fas fa-edit"></i>
                                            </button>
                                    </div>
                                </td>
                               <div class="modal fade" id="modalEstado{{ $factura->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEstadoLabel{{ $factura->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('facturas.cambiarEstado', $factura->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEstadoLabel{{ $factura->id }}">
                                                    Cambiar estado de la factura #{{ $factura->folio }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                <label for="estado{{ $factura->id }}"><strong>Nuevo estado</strong></label>
                                                <select name="estado" id="estado{{ $factura->id }}" class="form-control" required>
                                                    <option value="">Seleccione un estado</option>
                                                    <option value="Pendiente" {{ $factura->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="Pagada" {{ $factura->estado == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                                                    <option value="Anulada" {{ $factura->estado == 'Anulada' ? 'selected' : '' }}>Anulada</option>
                                                </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="modalfacturas" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('facturacion.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Importar Facturas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div
                        id="dropzone"
                        class="border border-secondary rounded p-4 text-center bg-light"
                        style="cursor: pointer;"
                        onclick="document.getElementById('archivo').click();"
                        ondragover="event.preventDefault(); this.classList.add('border-primary');"
                        ondragleave="this.classList.remove('border-primary');"
                        ondrop="handleDrop(event);"
                        >
                        <div class="mb-2">
                            <i class="fas fa-upload fa-2x text-secondary"></i>
                        </div>
                        <p class="mb-0 text-muted">Arrastra tu archivo CSV aquí o haz clic para seleccionarlo</p>
                        <small id="file-name" class="form-text text-muted mt-2"></small>
                    </div>
                    <input type="file" name="archivo" id="archivo" class="d-none" accept=".csv" onchange="showFileName(this)" required>
                    <button type="submit" class="btn btn-primary mt-3">
                        Importar CSV 
                    </button>
                </div>
            </div>
        </div>
    </form>
  </div>
</div>


<div class="modal fade" id="modal_agregacionfacturas" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        {{-- <form action="{{ route('facturacion.store') }}" method="POST"> --}}
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Nueva Factura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="folio">Folio</label>
                            <input type="text" class="form-control" id="folio" name="folio" value="{{ $maxFolio }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_dte">Tipo DTE</label>
                            <select class="form-control" id="tipo_dte" name="tipo_dte" required>
                                <option value="33">Factura Electrónica</option>
                                <option value="52">Guía de Despacho Electrónica</option>
                                <option value="56">Nota de Débito Electrónica</option>
                                <option value="61">Nota de Crédito Electrónica</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fecha_emision">Fecha Emisión</label>
                            <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="rut_receptor">Rut Receptor:</label>
                            <select type="text" class="form-control" id="rut_receptor" name="rut_receptor" value="" required>
                                <option value="">Seleccione un Cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->rut }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="razon_social_receptor">Razón Social Receptor:</label>
                            <input type="text" class="form-control" id="razon_social_receptor" name="razon_social_receptor" value="" disabled>
                        </div>
                    </div>
                    <div class="card-body">

                        <form id="productoForm">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="servicio">Servicios:</label>
                                    <div class="input-group">
                                        <select class="form-control" id="servicio" name="servicio" required>
                                            <option value="" >Seleccione un Servicio</option>
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
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí se mostrara la descripción del servicio seleccionado.">
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
                                    <input type="hidden" class="form-control" id="moneda" value="" disabled>
    
                            </div>
                            <button type="submit" class="btn btn-success allign-center">Agregar Producto al Catálogo</button>
                        </form>
                    </div>

                    <div class="card-body">
                        <!-- Formulario de producto -->
                        <form id="ItemsLform">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="itemL">Ítems Libres:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="itemsL" name="itemsL" value="" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí debes ingresar el nombre del ítem libre que deseas agregar a la cotización.">
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
                                            <button class="btn btn-outline-secondary" type="button" data-toggle="tooltip" data-placement="top"  title="Aquí debes ingresar la descripción del servicio seleccionado.">
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
                    <div class="form-row">
                        <div class="card-body p-0">
                            <div class="table-responsive"> 
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
                                            <td colspan="5" class="text-right font-weight-bold">IVA 19%</td>
                                            <td id="ivaTotal">$0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right font-weight-bold">Subtotal:</td>
                                            <td id="subtotal">$0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right font-weight-bold">Total :</td>
                                            <td id="total">$0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right">
                                                <button type="submit" id="guardarCBtn" class="btn btn-primary">Guardar Factura</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </form> --}}
  </div>
</div>
@endsection
@section('css')
    {{-- flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop
@section('js')
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        
        $(document).ready(function () {
            // Inicializar flatpickr para los campos de fecha
            flatpickr("#fecha_emision", {
                dateFormat: "Y-m-d",
                allowInput: true,
                locale: {
                    firstDayOfWeek: 1 // Lunes como primer día de la semana
                }
            });            

            var successMessage = document.getElementById('successMessage');
            var errorMessage = document.getElementById('errorMessage');
            if (successMessage) {
                const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Exito!",
                        text: successMessage.value,
                    });
            } else if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage.value,
                    confirmButtonText: 'Aceptar'
                });
            }
            $('#myTable').DataTable({
                responsive: true,
                autoWidth: false,
                "language": {
                    url: '{{ asset("datatables/es-CL.json")}}'
                }
            });
            
            const serviciosGuardados = [];
            const productosGuardados = [];
            const itemslibresGuardados = [];
            function actualizarTabla() {
                const tabla = document.getElementById('tablaProducto');
                tabla.innerHTML = '';
                let i = 0;
                let subtotal = 0;
                let ivaTotal = 0
                let total = 0;
                productosGuardados.forEach((p,indexp) => {
                    const importe = p.cantidad * p.precioConvertido;
                    const iva = importe * 0.19; // 19% de IVA
                    const totalProducto = (importe + iva);
    
                    subtotal += importe;
                    ivaTotal += iva;
                    total += totalProducto;
    
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
    
                    subtotal += importe_itemsL;
                    ivaTotal += iva_itemsL;
                    total += totalProducto_itemsL;
    
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
                                console.log('fila', index);
    
                    tabla.insertAdjacentHTML('beforeend', fila);
                    document.querySelectorAll('.eliminar-btn').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const index_eliminar = this.getAttribute('data-index');
                            console.log('index_eliminar', index_eliminar);
                            console.log('eliminados', itemslibresGuardados.splice(0, 1));
                            itemslibresGuardados.splice(index_eliminar, 1);
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
            }
            function eliminarProducto(index) {
                productosGuardados.splice(index, 1);
                actualizarTabla();
            }
    
            // ----------------------     PROVICIONAL     ----------------------
            function conversorMoneda(precio, moneda, moneda_cotizacion){
                let precioConvertido = 0;
                let moneda_a_convertir = parseFloat(document.getElementById('moneda').value);
                if(moneda == 'USD' && moneda_cotizacion == 'CLP'){
                    precioConvertido = precio * moneda_a_convertir; 
                } else if(moneda == 'UF' && moneda_cotizacion == 'CLP'){
                    precioConvertido = precio * moneda_a_convertir; 
                } else {
                    precioConvertido = precio; // Si las monedas son iguales, no se realiza conversión
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
                const precioConvertido = conversorMoneda(precio, moneda, 'CLP');
                            ///console.log('precio', precioConvertido);
                            productosGuardados.push({
                                servicio,
                                cantidad,
                                descripcion,
                                precioConvertido,
                            });
                actualizarTabla();
                document.getElementById('productoForm').reset();
            });
            document.getElementById('guardarCBtn').addEventListener('click', function(e) {
                e.preventDefault();
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const folio = document.getElementById('folio').value;
                const tipo_dte = document.getElementById('tipo_dte').value;
                const fecha_emision = document.getElementById('fecha_emision').value;
                const rut_receptor = document.getElementById('rut_receptor').value;
                const razon_social_receptor = document.getElementById('razon_social_receptor').value;
                const productos = productosGuardados.map(p => ({
                    servicio: p.servicio,
                    cantidad: p.cantidad,
                    descripcion: p.descripcion,
                    precio: p.precioConvertido
                }));
                const itemsL = itemslibresGuardados.map(p => ({
                    itemsL: p.itemLibre,
                    cantidad_itemsL: p.cantidad_itemsL,
                    descripcion_itemsL: p.descripcion_itemsL,
                    precio_itemsL: p.precio_itemsL
                }));
                const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('$', '').replace('.', '').replace(',', '.'));
                const ivaTotal = parseFloat(document.getElementById('ivaTotal').textContent.replace('$', '').replace('.', '').replace(',', '.'));
                const total = parseFloat(document.getElementById('total').textContent.replace('$', '').replace('.', '').replace(',', '.'));
                const data = {
                    _token: token,
                    folio: folio,
                    tipo_dte: tipo_dte,
                    fecha_emision: fecha_emision,
                    rut_receptor: rut_receptor,
                    razon_social_receptor: razon_social_receptor,
                    productos: productos,
                    itemsL: itemsL,
                    total_neto: subtotal,
                    iva: ivaTotal,
                    total: total
                };
                $.ajax({
                    url: '/facturacion/crear',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Factura guardada correctamente.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo guardar la factura. Inténtalo de nuevo.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
        
        $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
        });

        $('#rut_receptor').on('change',function(){
            const clienteId = $(this).val();
            $.ajax({
                url: '/clientes/' + clienteId + '/info',
                type: 'GET',
                success: function(data) {
                    $('#razon_social_receptor').val(data.razon_social);
                },
                error: function(xhr) {
                    $('#razon_social_receptor').val('Seleccione un cliente');
                }
            });
        })
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
         $('#servicio').on('change', function() {
            const productoId = $(this).val();
            $.ajax({
                url: '/servicios/' + productoId + '/info',
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#descripcion').val(data.descripcion);
                    $('#precio').val(data.precio);
                    $('#moneda').val(data.moneda.valor);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });
    </script>
    <script>
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropzone').classList.remove('border-blue-500');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const input = document.getElementById('archivo');
            input.files = files;
            showFileName(input);
        }
    }

    function showFileName(input) {
        const fileNameDisplay = document.getElementById('file-name');
        if (input.files.length > 0) {
            fileNameDisplay.textContent = "Archivo seleccionado: " + input.files[0].name;
        } else {
            fileNameDisplay.textContent = "";
        }
    }
</script>
@endsection