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
                <th>Total</th>
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
                            <a href="{{ route('cotizaciones.edit', $cotizacion->id_cotizacion) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('cotizaciones.prepararPDF', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a class="btn btn-sm btn-outline-info"  onclick="crearVentanaCorreo(  '{{ $cotizacion->codigo_cotizacion }}',  '{{ $cotizacion->id_cotizacion }}',  '{{ $cotizacion->email }}',  '{{ csrf_token() }}',  '{{ route('cotizaciones.enviar', $cotizacion->id_cotizacion) }}')">
                                <i class="fas fa-envelope"></i>
                        </a>
                        {{-- <a href="{{ route('cotizaciones.prepararEmail', ['id' => $cotizacion->id_cotizacion]) }}" class="btn btn-sm btn-info">
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
  @if(session('success'))
  <script>
      Swal.fire({
          icon: 'success',
          title: '¡Correo enviado!',
          text: '{{ session("success") }}',
          timer: 3000,
          showConfirmButton: false
      });
  </script>
  @endif
@stop
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let cantidadVentanasCorreo = 0;

  function crearVentanaCorreo(codigoCotizacion, idCotizacion, correoDestino, csrfToken, actionUrl) {
    const contenedor = document.getElementById('composerContainer');

    const ventana = document.createElement('div');
    ventana.className = 'border rounded shadow bg-white position-fixed d-flex flex-column';
    ventana.style.bottom = '20px';
    ventana.style.right = `${20 + (cantidadVentanasCorreo * 470)}px`;
    ventana.style.width = '450px';
    ventana.style.zIndex = 1050;
    ventana.setAttribute('data-indice', cantidadVentanasCorreo);

    ventana.innerHTML = `
      <div class="contenedor-enviar border-top d-flex justify-content-between align-items-center px-3 py-2 cursor-pointer" onclick="alternarVentanaCorreo(this)">
        <span class="font-weight-bold">Cotización ${codigoCotizacion}</span>
        <button class="btn btn-sm btn-light" onclick="cerrarVentanaCorreo(event, this)">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form onsubmit="enviarCorreoAjax(event, this)" action="${actionUrl}" method="POST">
        <input type="hidden" name="_token" value="${csrfToken}">
        <div class="p-2 flex-fill overflow-auto">
          <div class="form-group mb-1">
            <input type="email" class="form-control form-control-sm" placeholder="Para" name="correo_destino" value="${correoDestino}" required>
          </div>
          <div class="form-group mb-1">
            <input type="text" class="form-control form-control-sm" placeholder="CC" name="copia" required>
          </div>
          <div class="form-group mb-1">
            <input type="text" class="form-control form-control-sm" placeholder="CCO" name="copia_oculta" required>
          </div>
          <div class="form-group mb-1">
            <input type="text" class="form-control form-control-sm" placeholder="Asunto" name="asunto" required>
          </div>
          <div class="form-group mb-1">
            <textarea class="form-control form-control-sm" name="mensaje" rows="6" placeholder="Escribe tu mensaje..." required></textarea>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="adjuntarPdf_${idCotizacion}" name="adjuntarPdf">
            <label class="form-check-label" for="adjuntarPdf_${idCotizacion}">
              ¿Desea agregar en el email el PDF de la cotización?
            </label>
          </div>
        </div>
        <div class="border-top d-flex justify-content-between align-items-center px-3 py-2">
          <button type="submit" id="enviar_btn" class="btn btn-primary btn-sm">Enviar</button>
        </div>
      </form>
    `;

    contenedor.appendChild(ventana);
    cantidadVentanasCorreo++;
  }
  function enviarCorreoAjax(event, form) {
    event.preventDefault(); // Evita recargar la página

    const formData = new FormData(form);
    const actionUrl = form.action;

    // Desactiva el botón de enviar temporalmente
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Enviando...';

    fetch(actionUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': formData.get('_token'),
        'Accept': 'application/json'
      },
      body: formData
    })
      .then(async (response) => {
        if (!response.ok) {
          const errorData = await response.json();
          throw errorData;
        }
        return response.json();
      })
      .then((data) => {
        Swal.fire({
          icon: 'success',
          title: '¡Correo enviado!',
          text: data.message || 'La cotización fue enviada correctamente.',
          timer: 3000,
          showConfirmButton: false
        });

        // Opcional: actualizar el estado de la fila a "Enviada"
        const idCotizacion = data.id;
        const fila = document.querySelector(`a[onclick*="'${idCotizacion}'"]`)?.closest('tr');
        const celdaEstado = fila?.querySelector('td:nth-child(6) span');
        if (celdaEstado) {
          celdaEstado.textContent = 'Enviada';
        }

        // Cierra la ventana del formulario de correo
        cerrarVentanaCorreo(null, form.closest('.position-fixed'));
      })
      .catch(error => {
        let mensaje = 'Ocurrió un error al enviar el correo.';
        if (error?.errors) {
          mensaje = Object.values(error.errors).flat().join('\n');
        } else if (error?.message) {
          mensaje = error.message;
        }

        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: mensaje
        });
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerText = 'Enviar';
      });
  }

  function alternarVentanaCorreo(elemento) {
    const ventana = elemento.closest('.position-fixed');
    const botonEnviar = ventana.querySelector('#enviar_btn');

    if (ventana.classList.contains('minimizada')) {
      // Maximizar
      ventana.classList.remove('minimizada');
      ventana.style.height = '';
      ventana.querySelectorAll('.p-2, .form-group').forEach(el => el.classList.remove('d-none'));
      botonEnviar.classList.remove('d-none');
    } else {
      // Minimizar
      ventana.classList.add('minimizada');
      ventana.style.height = '45px';
      ventana.querySelectorAll('.p-2, .form-group').forEach(el => el.classList.add('d-none'));
      botonEnviar.classList.add('d-none');
    }
  }



  function cerrarVentanaCorreo(event, boton) {
    if (event) event.stopPropagation(); // Evita error si event es null

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
@section('css')
    <style>
        .contenedor-enviar {
            cursor: pointer;
        }
        .contenedor-enviar:hover {
            background-color: #f8f9fa;
        }
        .position-fixed {
            transition: right 0.3s ease;
        }
        .minimizada {
            height: 45px;
        }
    </style>
@endsection