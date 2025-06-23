@extends('adminlte::page')
@section('title', 'PDF Cotización')

@section('content')
@php
    $subtotal = 0;
    $ivaTotal = 0;
    $totaldcto = 0;
    $total = 0;
@endphp
<body class="p-5">
    <input type="hidden" id="id_cotizacion" value="{{$cotizacion->id_cotizacion}}">
    <div class="d-flex justify-content-between align-items-center mb-4 py-4">
      <h1 class="h1 font-weight-bold">COTIZACIÓN : <span id="">{{$cotizacion->codigo_cotizacion}}</span></h1>
      <img src="{{ asset('logoPDF.png') }}" alt="Logo" style="width: 300px;">
    </div>

    <div class="mb-4">
      <p><strong>FECHA:</strong>{{$cotizacion->created_at->format('d F Y')}}</p>
      <p><strong>CLIENTE:  </strong>    <strong id="">{{$cotizacion->cliente->razon_social}}</strong></p>
      <p><strong>RUT: </strong><strong id="">{{$cotizacion->cliente->rut}}</strong></p>
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
      <tbody id="tablaCotizacion">
        @foreach($cotizacion->servicios as $servicio)
            <tr>
                <td>{{$servicio->nombre_servicio}}</td>
                <td>{{$servicio->pivot->cantidad}}</td>
                <td>${{$servicio->pivot->precio_unitario}}</td>
                <td>${{$servicio->pivot->cantidad * $servicio->pivot->precio_unitario}}</td>
                 @php
                    $subtotal += $servicio->pivot->cantidad * $servicio->pivot->precio_unitario;
                    $ivaTotal += ($servicio->pivot->cantidad * $servicio->pivot->precio_unitario) * 0.19;
                @endphp
            </tr>
        @endforeach
        @if($cotizacion->itemslibres!==null)         
            @foreach($cotizacion->itemslibres as $item_libre)
            <tr>
                <td>{{$item_libre->nombre}}</td>
                <td>{{$item_libre->cantidad}}</td>
                <td>${{$item_libre->precio}}</td>
                <td>${{$item_libre->cantidad * $item_libre->precio}}</td>
                @php
                    $subtotal += $item_libre->precio * $item_libre->cantidad;
                    $ivaTotal += ($item_libre->precio * $item_libre->cantidad) * 0.19;
                @endphp
            </tr>
            @endforeach
        @endif
      </tbody>
      @php
        $total = $subtotal + $ivaTotal;
        $totaldcto = $total - ($total * $cotizacion->descuento / 100);
      @endphp
      <tfoot>
            <tr style="background:#fffbe6">
              <td colspan="3" class="text-right font-weight-bold">IVA 19%</td>
              <td colspan="1" id="ivaTotal">{{number_format($ivaTotal,2)}}</td>
            </tr>
            <tr>
              <td colspan="3" class="text-right font-weight-bold">Subtotal:</td>
              <td colspan="1" id="subtotal">{{number_format($subtotal,2)}}</td>
            </tr>
            @if($cotizacion->descuento <= 0)
            <tr>
              <td colspan="3" class="text-right font-weight-bold">Total:</td>
              <td colspan="1" id="total">{{number_format($total,2)}}</td>
            </tr>
            @endif
            @if($cotizacion->descuento > 0)
                <tr>
                <td colspan="3" class="text-right font-weight-bold">Total sin descuento %:</td>
                <td colspan="1" id="total">{{number_format($total,2)}}</td>
              </tr>
              <tr>
                  <td colspan="3" class="text-right font-weight-bold">Descuento %:</td>
                  <td colspan="1" id="total_dcto">{{number_format($cotizacion->descuento,2)}}%</td>
              </tr>
            @endif
        </tfoot>
    </table>
    <form method="POST" action="{{ route('cotizaciones.generarPDFobservaciones', ['id' => $cotizacion->id_cotizacion]) }}" target="_blank">
        @csrf
        <div class="mt-5">
            <p><strong>OBSERVACIONES:</strong></p>
            <input type="text" class="form-control" name="observaciones" placeholder="Escriba aquí las observaciones" style="height: 100px;">
        </div>

        <p class="text-muted mt-4 small">* Cotización válida por 30 días *</p>
        <div class="mt-4 d-flex justify-content-end gap-3">
            <button type="sumbit" class="btn btn-secondary mr-2" id="btnVisualizarPDF">
                <i class="fas fa-eye"></i> Visualizar PDF
            </button>
            <button type="sumbit" name="accion" value="guardar" class="btn btn-primary" id="btnGuardarPDF">
                <i class="fas fa-download"></i> Guardar PDF
            </button>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary ml-2">Regresar</a>
        </div>
    </form>
</body>
@stop
