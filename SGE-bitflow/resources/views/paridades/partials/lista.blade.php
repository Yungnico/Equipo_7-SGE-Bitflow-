<table class="table table-striped">
    <thead>
        <tr>
            <th>Moneda</th>
            <th>Valor</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paridades as $paridad)
            <tr>
                <td>{{ $paridad->moneda }}</td>
                <td>{{ $paridad->valor }}</td>
                <td>{{ $paridad->fecha }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
