<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización PDF</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 14px;
        }

        .cotizacion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .titulo-cotizacion {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .logo-cotizacion {
            max-width: 200px;
            height: auto;
            position: absolute; /* O fixed si quieres que permanezca fija al hacer scroll */
            top: 10px; /* Ajusta la distancia desde la parte superior */
            right: 10px; 
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table thead th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 10px;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .thead-light th {
            background-color: #f8f9fa;
        }

        tfoot tr td {
            background-color: #fafafa;
        }

        .text-muted {
            color: #6c757d;
        }

        .small {
            font-size: 12px;
        }

        .form-control {
            width: 100%;
            border: 1px solid #aaa;
            border-radius: 4px;
            resize: none;
        }
    </style>
</head>
<body class="p-5">
    <div class="cotizacion-header">
        <img src="assets/logoPDF.png" alt="Logo" class="logo-cotizacion">
        <h1 class="titulo-cotizacion">COTIZACIÓN : {{ $cotizacion->codigo_cotizacion }}</h1>
    </div>

    <div class="mb-4">
        <p><strong>FECHA:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_cotizacion)->format('d/m/Y') }}</p>
        <p><strong>CLIENTE:</strong> {{ ucfirst(strtolower($cotizacion->cliente->razon_social)) }}</p>
        <p><strong>RUT:</strong> {{ $cotizacion->cliente->rut }}</p>
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
        <tbody>
            @php
                $subtotal = 0;
                $ivaTotal = 0;
                $total = 0;
                $totaldcto = 0;
            @endphp

            @foreach($cotizacion->servicios as $servicio)
                @php
                    $importe = $servicio->pivot->cantidad * $servicio->precio;
                    $iva = $importe * 0.19;
                    $totalItem = $importe + $iva;
                    $totalItemDcto = $totalItem - ($importe * $cotizacion->descuento / 100);

                    $subtotal += $importe;
                    $ivaTotal += $iva;
                    $total += $totalItem;
                    $totaldcto += $totalItemDcto;
                @endphp
                <tr>
                    <td>{{ $servicio->nombre_servicio }}</td>
                    <td>{{ $servicio->pivot->cantidad }}</td>
                    <td>${{ number_format($servicio->precio, 2) }}</td>
                    <td>${{ number_format($importe, 2) }}</td>
                </tr>
            @endforeach

            @foreach($cotizacion->itemsLibres as $item)
                @php
                    $importe = $item->precio * $item->cantidad;
                    $iva = $importe * 0.19;
                    $totalItem = $importe + $iva;
                    $totalItemDcto = $totalItem - ($importe * $cotizacion->descuento / 100);

                    $subtotal += $importe;
                    $ivaTotal += $iva;
                    $total += $totalItem;
                    $totaldcto += $totalItemDcto;
                @endphp
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>${{ number_format($item->precio, 2) }}</td>
                    <td>${{ number_format($importe, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#fffbe6">
                <td colspan="3" class="text-right font-weight-bold">IVA 19%</td>
                <td>${{ number_format($ivaTotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right font-weight-bold">Subtotal:</td>
                <td>${{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right font-weight-bold">Total sin descuento:</td>
                <td>${{ number_format($total, 2) }}</td>
            </tr>
            @if ($cotizacion->descuento > 0)
            <tr>
                <td colspan="3" class="text-right font-weight-bold">Total con descuento:</td>
                <td>${{ number_format($totaldcto, 2) }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    <div class="mt-5">
        <p><strong>OBSERVACIONES:</strong></p>
        <p class="border p-3" style="min-height: 100px;"></p>
    </div>
</body>
</html>
