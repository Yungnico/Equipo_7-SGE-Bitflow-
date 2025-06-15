@extends('adminlte::page')

@section('title', 'Paridades')

@section('content_header')
    <h1>Paridades</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Moneda</button>
        <a href="{{ route('paridades.actualizar') }}" class="btn btn-warning">Actualizar Valores</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <x-adminlte-alert theme="success">{{ session('success') }}</x-adminlte-alert>
        @endif

        @if (session('warning'))
            <x-adminlte-alert theme="warning">{{ session('warning') }}</x-adminlte-alert>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Moneda</th>
                    <th>Valor</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paridades as $paridad)
                <tr>
                    <td>{{ strtoupper($paridad->moneda) }}</td>
                    <td>{{ number_format($paridad->valor, 2, ',', '.') }}</td>
                    <td>{{ $paridad->fecha }}</td>
                    <td>
                        <button class="btn btn-sm btn-success editar-btn" 
                        data-id="{{ $paridad->id }}" 
                        data-moneda="{{ strtoupper($paridad->moneda) }}" 
                        data-valor="{{ $paridad->valor }}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalEditar">
                        Editar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('paridades.store') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Seleccionar Monedas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            @php
                $monedasDisponibles = ['dolar' => 'Dólar', 'uf' => 'UF', 'euro' => 'Euro', 'utm' => 'UTM'];
            @endphp

            @foreach($monedasDisponibles as $codigo => $nombre)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="{{ $codigo }}" name="monedas[]" id="moneda_{{ $codigo }}">
              <label class="form-check-label" for="moneda_{{ $codigo }}">{{ $nombre }}</label>
            </div>
            @endforeach

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="formEditar">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Paridad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="monedaEditar" class="form-label">Moneda</label>
              <input type="text" class="form-control" id="monedaEditar" disabled>
            </div>
            <div class="mb-3">
              <label for="valorEditar" class="form-label">Nuevo Valor</label>
              <input type="number" step="0.0001" class="form-control" id="valorEditar" name="valor" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </div>
    </form>
  </div>
</div>

@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editarButtons = document.querySelectorAll('.editar-btn');
        const formEditar = document.getElementById('formEditar');
        const monedaEditar = document.getElementById('monedaEditar');
        const valorEditar = document.getElementById('valorEditar');

        editarButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const moneda = this.dataset.moneda;
                const valor = this.dataset.valor;

                monedaEditar.value = moneda;
                valorEditar.value = valor;

                formEditar.action = `/paridades/${id}/update`;
            });
        });
    });
</script>
@stop
