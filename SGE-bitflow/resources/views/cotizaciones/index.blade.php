@extends('adminlte::page')
@section('title', 'Cotizaciones')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('content')
<div id="composerContainer" class="position-relative"></div>
<div class="content py-5">
    <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Código Cotización</th>
                <th>Cliente </th>
                <th>Fecha</th>
                <th>Moneda</th>
                <th>total</th>
                <th>Estado</th>
                <th>Acciones</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->codigo_cotizacion }}</td>
                    <td>{{ $cotizacion->cliente->razon_social }}</td>
                    <td>{{ $cotizacion->fecha_cotizacion }}</td>
                    <td>{{ $cotizacion->moneda }}</td>
                    <td>{{$cotizacion->total_iva}}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $cotizacion->estado }}</span>
                            <a href="{{ route('cotizaciones.edit', $cotizacion->id_cotizacion) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('cotizaciones.prepararPDF', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <button class="btn btn-sm btn-primary" onclick="crearVentanaCorreo('{{ $cotizacion->codigo_cotizacion }}')">
                            <i class="fas fa-envelope"></i>
                        </button>
                        {{-- <a href="{{ route('cotizaciones.prepararEmail', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-envelope"></i>
                        </a> --}}
                        {{-- <a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $cotizacion->id_cotizacion }}').submit();">
                            <i class="fas fa-trash"></i>
                        </a> --}}
                        
                        <form id="delete-form-{{ $cotizacion->id_cotizacion }}" action="{{ route('cotizaciones.destroy', $cotizacion->id_cotizacion) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let cantidadVentanasCorreo = 0;

  function crearVentanaCorreo(codigoCotizacion) {
    const contenedor = document.getElementById('composerContainer');

    const ventana = document.createElement('div');
    ventana.className = 'border rounded shadow bg-white position-fixed d-flex flex-column';
    ventana.style.bottom = '20px';
    ventana.style.right = `${20 + (cantidadVentanasCorreo * 470)}px`;
    ventana.style.width = '450px';
    ventana.style.zIndex = 1050;
    ventana.setAttribute('data-indice', cantidadVentanasCorreo);

    ventana.innerHTML = `
      <div class="d-flex justify-content-between align-items-center bg-light border-bottom px-3 py-2">
        <span class="font-weight-bold">Cotización ${codigoCotizacion}</span>
        <div>
          <button class="btn btn-sm btn-light" onclick="minimizarVentanaCorreo(this)">
            <i class="fas fa-window-minimize"></i>
          </button>
          <button class="btn btn-sm btn-light" onclick="maximizarVentanaCorreo(this)">
            <i class="far fa-window-maximize"></i>
          </button>
          <button class="btn btn-sm btn-light" onclick="cerrarVentanaCorreo(this)">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="p-2 flex-fill overflow-auto">
        <div class="form-group mb-1">
          <input type="email" class="form-control form-control-sm" placeholder="Para">
        </div>
        <div class="form-group mb-1">
          <input type="email" class="form-control form-control-sm" placeholder="Cc">
        </div>
        <div class="form-group mb-1">
          <input type="email" class="form-control form-control-sm" placeholder="Cco">
        </div>
        <div class="form-group mb-1">
          <input type="text" class="form-control form-control-sm" placeholder="Asunto">
        </div>
        <div class="form-group mb-1">
          <textarea rows="6" class="form-control form-control-sm" placeholder="Mensaje"></textarea>
        </div>
      </div>
      <div class="border-top d-flex justify-content-between align-items-center px-3 py-2">
        <button class="btn btn-primary btn-sm">Enviar</button>
      </div>
    `;

    contenedor.appendChild(ventana);
    cantidadVentanasCorreo++;
  }

  function minimizarVentanaCorreo(boton) {
    const cabecera = boton.closest('div');
    const ventana = cabecera.parentElement;
    ventana.classList.toggle('minimizada');
    if (ventana.classList.contains('minimizada')) {
      ventana.style.height = '45px';
      ventana.querySelectorAll('.p-2, .border-top').forEach(el => el.classList.add('d-none'));
    } else {
      ventana.style.height = '';
      ventana.querySelectorAll('.p-2, .border-top').forEach(el => el.classList.remove('d-none'));
    }
  }

  function maximizarVentanaCorreo(boton) {
    const ventana = boton.closest('.position-fixed');
    if (ventana.classList.contains('maximizada')) {
      ventana.classList.remove('maximizada');
      ventana.style.width = '450px';
      ventana.style.height = '';
    } else {
      ventana.classList.add('maximizada');
      ventana.style.width = '90%';
      ventana.style.height = '90%';
    }
  }

  function cerrarVentanaCorreo(boton) {
    const ventana = boton.closest('.position-fixed');
    ventana.remove();
    reorganizarVentanasCorreo();
  }

  function reorganizarVentanasCorreo() {
    const ventanas = document.querySelectorAll('.position-fixed');
    cantidadVentanasCorreo = 0;
    ventanas.forEach((ventana) => {
      ventana.style.right = `${20 + (cantidadVentanasCorreo * 470)}px`;
      ventana.setAttribute('data-indice', cantidadVentanasCorreo);
      cantidadVentanasCorreo++;
    });
  }
</script>

@if(session('success'))
    <script>
        $(document).ready(function () {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                "language": {
                    responsive: true,
                    autoWidth: true,
                    url: '{{ asset("datatables/es-CL.json")}}'
                }
            });
        });
        
        $(window).on('resize', function() {
                table.columns.adjust().responsive.recalc();
            });
    </script>
@stop