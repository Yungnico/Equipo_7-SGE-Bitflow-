@extends('adminlte::page')

@section('content')
<div class="container">
    <h3>Agregar Paridad</h3>

    <form method="POST" action="{{ route('paridades.store') }}">
        @csrf

        <div class="form-group">
            <label for="moneda">Moneda</label>
            <input list="monedas" type="text" class="form-control" id="moneda" name="moneda" required placeholder="Ej: USD, EUR...">

            <datalist id="monedas">
                <option value="USD"> <!-- Dólar estadounidense -->
                <option value="EUR"> <!-- Euro -->
                <option value="CLP"> <!-- Peso chileno -->
                <option value="BRL"> <!-- Real brasileño -->
                <option value="ARS"> <!-- Peso argentino -->
                <option value="GBP"> <!-- Libra esterlina -->
                <option value="JPY"> <!-- Yen japonés -->
                <option value="CNY"> <!-- Yuan chino -->
                <option value="CAD"> <!-- Dólar canadiense -->
                <option value="MXN"> <!-- Peso mexicano -->
                <option value="CHF"> <!-- Franco suizo -->
                <option value="AUD"> <!-- Dólar australiano -->
                <option value="NZD"> <!-- Dólar neozelandés -->
            </datalist>

        </div>

        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="number" step="0.0001" class="form-control" id="valor" name="valor" required>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection

@section('js')
<script>
    const paridades = @json($paridades);
    const monedaInput = document.getElementById('moneda');
    const valorInput = document.getElementById('valor');
    const fechaInput = document.getElementById('fecha');

    document.addEventListener('DOMContentLoaded', () => {
        const hoy = new Date().toISOString().split('T')[0];
        fechaInput.value = hoy;
    });

    monedaInput.addEventListener('input', () => {
        const monedaIngresada = monedaInput.value.trim().toUpperCase();
        const coincidencias = paridades.filter(p => p.moneda.toUpperCase() === monedaIngresada);

        if (coincidencias.length > 0) {
            const paridadMasReciente = coincidencias.reduce((a, b) => new Date(a.fecha) > new Date(b.fecha) ? a : b);
            valorInput.value = paridadMasReciente.valor;
            fechaInput.value = new Date().toISOString().split('T')[0]; // siempre se pone la fecha actual
        } else {
            valorInput.value = '';
            fechaInput.value = new Date().toISOString().split('T')[0]; // nueva moneda: valor vacío, fecha actual
        }
    });
</script>
@endsection


