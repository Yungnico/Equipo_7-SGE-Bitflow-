<form method="GET" action="{{ route('clientes.buscar') }}">
    <label>Razón Social:</label>
    <input type="text" name="razon_social">

    <label>RUT:</label>
    <input type="text" name="rut" placeholder="12.345.678-9">

    <label>Nombre de Fantasía:</label>
    <input type="text" name="nombre_fantasia">

    <button type="submit">Buscar</button>
</form>

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

