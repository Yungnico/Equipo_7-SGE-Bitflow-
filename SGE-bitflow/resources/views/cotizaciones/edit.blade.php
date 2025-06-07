@extends('adminlte::page')

@section('content')
<div class="container pt-3">

    <div class="card">
        <div class="card-header pt-3">
            <h3 class="card-title">Editar Estado de Cotización</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cotizaciones.editarestado', $cotizacion->id_cotizacion) }}" enctype="multipart/form-data">
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
                        </select>
                    </div>
                </div>
    
                <!-- Archivos adicionales -->
                <div class="form-group d-none" id="archivos_adicionales">
                    <label for="archivo_cliente">Archivos del cliente (orden de compra o servicio):</label>
                    <input type="file" class="form-control" name="archivo_cliente[]" multiple>
                     <a href="{{ route('cotizacion.conciliar',$cotizacion->id_cotizacion) }}" class="mt-3 btn btn-success px-4 py-2 mb-3">
                        Conciliar Facturas
                    </a>
                    
                </div>
    
                <!-- Motivo de rechazo -->
                <div class="form-group d-none" id="motivo_rechazo">
                    <label for="motivo">Motivo del rechazo/anulación:</label>
                    <textarea name="motivo" class="form-control" rows="3"></textarea>
                </div>
    
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    function actualizarCamposAdicionales() {
        const estado = document.getElementById('estado').value;
        const archivos = document.getElementById('archivos_adicionales');
        const motivo = document.getElementById('motivo_rechazo');

        archivos.classList.add('d-none');
        motivo.classList.add('d-none');

        if (['Pagada', 'Aceptada'].includes(estado)) {
            archivos.classList.remove('d-none');
        }

        if (['Anulada', 'Rechazada'].includes(estado)) {
            motivo.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarCamposAdicionales();
        document.getElementById('estado').addEventListener('change', actualizarCamposAdicionales);
    });
</script>
@stop
