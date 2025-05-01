@extends('adminlte::page')
@section('plugin.select2',true)
@section('content')
<div class="container">
    <h2>Nueva Cotización</h2>
    <form action="{{ route('cotizaciones.store') }}" method="POST">
        @csrf

        {{-- Datos generales --}}
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>
            <select name="id_cliente" id="id_cliente" class="form-control" required>
                {{-- @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach  CAMBIAR CUANDO jAVI SUVA SUS CAMBIOS --}}
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_cotizacion" class="form-label">Fecha de Cotización</label>
            <input type="date" name="fecha_cotizacion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="moneda" class="form-label">Moneda</label>
            <select name="moneda" class="form-control" required>
                <option value="CLP">CLP</option>
                <option value="USD">USD</option>
                <option value="UF">UF</option>
            </select>
        </div>

        {{-- Servicios --}}
        <h4>Servicios</h4>
        <div id="servicios-wrapper">
            <div class="row mb-2 servicio-item">
                <div class="col-md-5">
                    <select name="servicios[0][id]" class="form-control" required>
                        <option value="">Seleccione un servicio</option>
                        @foreach ($servicios as $servicio)
                            <option value="{{ $servicio->id }}">{{ $servicio->nombre_servicio }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="servicios[0][cantidad]" class="form-control" placeholder="Cantidad" min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="servicios[0][precio]" class="form-control" placeholder="Precio Unitario" step="0.01" value="{{ $servicio->precio }}" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-servicio">Eliminar</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-servicio">+ Agregar Servicio</button>

        {{-- Total y enviar --}}
        <div class="mb-3">
            <label for="descuento" class="form-label">Descuento</label>
            <input type="number" name="descuento" class="form-control" step="0.01">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cotización</button>
    </form>
</div>
@stop



@section('js')
<script>
    let index = 1;
    document.getElementById('add-servicio').addEventListener('click', function () {
        const wrapper = document.getElementById('servicios-wrapper');
        const original = wrapper.querySelector('.servicio-item');
        const clone = original.cloneNode(true);

        // Actualiza los nombres de los inputs
        clone.querySelectorAll('select, input').forEach(el => {
            const name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\d+/, index));
                el.value = (el.type === 'number') ? '' : el.value;
            }
        });

        wrapper.appendChild(clone);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-servicio')) {
            const item = e.target.closest('.servicio-item');
            const allItems = document.querySelectorAll('.servicio-item');
            if (allItems.length > 1) {
                item.remove();
            }
        }
    });
</script>
@stop
