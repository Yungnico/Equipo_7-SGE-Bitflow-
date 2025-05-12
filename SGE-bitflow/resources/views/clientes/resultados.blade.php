@if(isset($mensaje))
    <p>{{ $mensaje }}</p>
@else
    <table>
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>RUT</th>
                <th>Nombre de Fantasía</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->razon_social }}</td>
                    <td>{{ $cliente->rut }}</td>
                    <td>{{ $cliente->nombre_fantasia }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif


