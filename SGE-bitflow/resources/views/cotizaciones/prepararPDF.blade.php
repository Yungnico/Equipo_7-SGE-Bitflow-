@extends('adminlte::page')

@section('content')

<body class="p-5">
    <input type="hidden" id="id_cotizacion" value="{{$id}}">
    <div class="d-flex justify-content-between align-items-center mb-4 py-4">
      <h1 class="h1 font-weight-bold">COTIZACIÓN : <span id="codigo_cotizacion"></span></h1>
      <img src="{{ asset('logoPDF.png') }}" alt="Logo" style="width: 300px;">
    </div>

    <div class="mb-4">
      <p><strong>FECHA:</strong> 02/05/2025</p>
      <p><strong>CLIENTE:  </strong>    <strong id="razon_social"></strong></p>
      <p><strong>RUT: </strong><strong id="rut"></strong></p>
    </div>

    <table class="table table-bordered text-center">
      <thead class="thead-light">
          <tr>
              <th>PRODUCTO</th>
              <th>CANTIDAD</th>
              <th>PRECIO</th>
              <th>TOTAL</th>
          </tr>
      </thead>
      <tbody id="tablaCotizacion"></tbody>
      <tfoot>
          <tr style="background:#fffbe6">
              <td colspan="3" class="text-right font-weight-bold">IVA 19%</td>
              <td colspan="1" id="ivaTotal">$0.00</td>
          </tr>
          <tr>
              <td colspan="3" class="text-right font-weight-bold">Subtotal:</td>
              <td colspan="1" id="subtotal">$0.00</td>
          </tr>
          <tr>
              <td colspan="3" class="text-right font-weight-bold">Total sin descuento %:</td>
              <td colspan="1" id="total">$0.00</td>
          </tr>
          <tr>
              <td colspan="3" class="text-right font-weight-bold" id="nombre_dcto">Total con descuento %:</td>
              <td colspan="1" id="total_dcto">$0.00</td>
          </tr>
        </tfoot>
    </table>

    <div class="mt-5">
        <p><strong>OBSERVACIONES:</strong></p>
        <input type="text" class="form-control" placeholder="Escriba aquí las observaciones" style="height: 100px;">
    </div>

    <p class="text-muted mt-4 small">* Cotización válida por 30 días *</p>
    <div class="mt-4 d-flex justify-content-end gap-3">
        <button class="btn btn-secondary mr-2" id="btnVisualizarPDF">
            <i class="fas fa-eye"></i> Visualizar PDF
        </button>
        <button class="btn btn-primary" id="btnGuardarPDF">
            <i class="fas fa-download"></i> Guardar PDF
        </button>
    </div>
</body>
@stop

@section('js')
<script>
    const productosGuardados = [];
    $(document).ready(function () {
        function capitalizarPrimeraLetra(texto) {
          return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
        }


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

        const tabla = document.getElementById('tablaCotizacion');
        const idCotizacion = $('#id_cotizacion').val();
        let subtotal = 0;
        let ivaTotal = 0
        let totaldcto = 0;
        let total = 0;
        $.ajax({
            url: '/cotizaciones/' + idCotizacion + '/info',
            type: 'GET',
            success: function (data) {
                console.log(data);
                $('#codigo_cotizacion').text(data.codigo_cotizacion),
                $('#razon_social').text(capitalizarPrimeraLetra(data.cliente.razon_social)),
                $('#rut').text(data.cliente.rut);
                data.servicios.forEach(e => {
                if(e.moneda != data.moneda){
                    e.precio = conversorMoneda(e.precio, e.moneda, data.moneda);
                }else{
                    e.precio = e.precio;
                }
                const importe = e.pivot.cantidad * e.precio;
                const iva = importe * 0.19; // 19% de IVA
                const totalProducto = (importe + iva);
                const totalProductoDcto = (importe + iva) - (importe * data.descuento / 100);
                const fila = `<tr>
                    <td>${e.nombre_servicio}</td>
                    <td>${e.pivot.cantidad}</td>
                    <td>$${e.precio}</td>
                    <td>$${importe.toFixed(2)}</td>       
                  </tr>`;
                productosGuardados.push({
                    nombre: e.nombre_servicio,
                    cantidad: e.pivot.cantidad,
                    precio: e.precio,
                    total: importe.toFixed(2),
                });
                tabla.insertAdjacentHTML('beforeend', fila);
                subtotal += importe;
                ivaTotal += iva;
                total += totalProducto;
                totaldcto += totalProductoDcto;

                });
                data.items_libres.forEach(e => {
                    const importe = e.cantidad * e.precio;
                    const iva = importe * 0.19; // 19% de IVA
                    const totalProducto = (importe + iva);
                    const totalProductoDcto = (importe + iva) - (importe * data.descuento / 100);
                    const fila = `<tr>
                        <td>${e.nombre}</td>
                        <td>${e.cantidad}</td>
                        <td>$${e.precio}</td>
                        <td>$${importe.toFixed(2)}</td>       
                      </tr>`;
                    tabla.insertAdjacentHTML('beforeend', fila);
                    productosGuardados.push({
                        nombre: e.nombre,
                        cantidad: e.cantidad,
                        precio: e.precio,
                        total: importe.toFixed(2),
                    });
                    subtotal += importe;
                    ivaTotal += iva;
                    total += totalProducto;
                    totaldcto += totalProductoDcto;
                });
                document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById('ivaTotal').textContent = `$${ivaTotal.toFixed(2)}`;
                document.getElementById('total').textContent = `$${total.toFixed(2)}`;
                if (parseFloat(data.descuento) > 0) {
                    document.getElementById('nombre_dcto').textContent = 'Total con descuento:';
                    document.getElementById('total_dcto').textContent = `$${totaldcto.toFixed(2)}`;
                } else {
                    document.getElementById('nombre_dcto').textContent = '';
                    document.getElementById('total_dcto').textContent = '';
                }
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
        $('#btnVisualizarPDF').on('click', function () {
            window.open(`/cotizaciones/${idCotizacion}/pdf`, '_blank');
        });

        $('#btnGuardarPDF').on('click', function () {
            const link = document.createElement('a');
            link.href = `/cotizaciones/${idCotizacion}/pdf?download=1`;
            link.download = `cotizacion_${idCotizacion}.pdf`;
            link.click();
        });

    });
</script>
@stop