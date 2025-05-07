@extends('adminlte::page')

@section('content')
<div class="card-body">
    <form method="POST" action="{{route('cotizaciones.editarestado',$cotizacion->id_cotizacion)}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-row">
            <!-- Código cotización -->
            <div class="form-group col-md-6">
                <label for="codigo_cotizacion">Código Cotización:</label>
                <input type="text" class="form-control" id="codigo_cotizacion" name="codigo_cotizacion" value="{{ $cotizacion->codigo_cotizacion }}" readonly>
            </div>

            <!-- Estado -->
            <div class="form-group col-md-6">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="{{ $cotizacion->estado }}">{{ $cotizacion->estado }}</option>
                    <option value="Aceptada">Aceptada</option>
                    <option value="Pagada">Pagada</option>
                    <option value="Anulada">Anulada</option>
                    <option value="Rechazada">Rechazada</option>
                    <!-- Agrega otros estados si corresponde -->
                </select>
            </div>
        </div>

        <!-- Carga de archivos adicionales -->
        <div class="form-group d-none" id="archivos_adicionales">
            <label for="archivo_cliente">Archivos del cliente (orden de compra o servicio):</label>
            <input type="file" class="form-control" name="archivo_cliente[]" multiple>
        </div>

        <!-- Motivo de rechazo -->
        <div class="form-group d-none" id="motivo_rechazo">
            <label for="motivo">Motivo del rechazo/anulación:</label>
            <textarea name="motivo" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@stop

@section('js')
<script>
    function actualizarCamposAdicionales() {
        const estado = document.getElementById('estado').value;
        const archivos = document.getElementById('archivos_adicionales');
        const facturas = document.getElementById('asignar_facturas');
        const motivo = document.getElementById('motivo_rechazo');

        archivos.classList.add('d-none');
        motivo.classList.add('d-none');

        if (['Pagada', 'Aceptada'].includes(estado)) {
            archivos.classList.remove('d-none');
        }

        if (estado === 'Anulada' || estado === 'Rechazada') {
            motivo.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarCamposAdicionales();
        document.getElementById('estado').addEventListener('change', actualizarCamposAdicionales);
    });
</script>
@stop
