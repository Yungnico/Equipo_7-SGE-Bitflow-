@extends('adminlte::page')

@section('title', 'Registrar contacto')

@section('content_header')
    <h1>Registrar contacto de cliente</h1>
@stop

@section('content')
    

    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.contactos.store', $cliente->id) }}" method="POST">

                @csrf

                {{-- Nombre del contacto --}}
                <div class="form-group">
                    <label for="nombre_contacto">Nombre del contacto</label>
                    <input type="text" name="nombre_contacto" id="nombre_contacto"
                        class="form-control @error('nombre_contacto') is-invalid @enderror"
                        maxlength="50" value="{{ old('nombre_contacto') }}">
                    @error('nombre_contacto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email_contacto">Email</label>
                    <input type="email" name="email_contacto" id="email_contacto"
                        class="form-control @error('email_contacto') is-invalid @enderror"
                        value="{{ old('email_contacto') }}">
                    @error('email_contacto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror


                </div>

                {{-- Teléfono --}}
                <div class="form-group">
                    <label for="telefono_contacto">Teléfono</label>
                    <input type="text" name="telefono_contacto" id="telefono_contacto"
                        class="form-control @error('telefono_contacto') is-invalid @enderror"
                        maxlength="15" value="{{ old('telefono_contacto') }}">
                    @error('telefono_contacto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Tipo de contacto --}}
                <div class="form-group">
                    <label for="tipo_contacto">Tipo de contacto</label>
                    <select name="tipo_contacto" id="tipo_contacto"
                        class="form-control @error('tipo_contacto') is-invalid @enderror">
                        <option value="">Seleccione una opción</option>
                        <option value="Comercial" {{ old('tipo_contacto') == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                        <option value="TI" {{ old('tipo_contacto') == 'TI' ? 'selected' : '' }}>TI</option>
                        <option value="Contable" {{ old('tipo_contacto') == 'Contable' ? 'selected' : '' }}>Contable</option>
                    </select>
                    @error('tipo_contacto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Botón --}}
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Guardar contacto</button>
                    <a href="{{ route('clientes.contactos.index', $cliente->id ) }}" class="btn btn-secondary ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    
    {{-- SweetAlert si falta seleccionar tipo_contacto --}}
    @if ($errors->has('tipo_contacto'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Campo obligatorio',
                text: '{{ $errors->first('tipo_contacto') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->has('tipo_contacto'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Campo obligatorio',
                text: '{{ $errors->first('tipo_contacto') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection


