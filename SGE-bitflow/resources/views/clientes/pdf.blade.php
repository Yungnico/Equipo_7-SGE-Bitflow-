<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Clientes</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Listado de Clientes</h2>

    @if($clientes->isEmpty())
        <p>No hay clientes registrados.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Razón Social</th>
                    <th>RUT</th>
                    <th>Nombre Fantasía</th>
                    <th>Giro</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->razon_social }}</td>
                        <td>{{ $cliente->rut }}</td>
                        <td>{{ $cliente->nombre_fantasia }}</td>
                        <td>{{ $cliente->giro }}</td>
                        <td>{{ $cliente->direccion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>



