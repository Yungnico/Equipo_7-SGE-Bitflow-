<h1>Editar Servicio</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('servicios.update', $servicio->id) }}">
    @csrf
    @method('PUT')

    <label>Nombre del servicio:</label>
    <input name="nombre_servicio" value="{{ old('nombre_servicio', $servicio->nombre_servicio) }}">
    @error('nombre_servicio') <p>{{ $message }}</p> @enderror

    <label>Descripción:</label>
    <input name="descripcion" value="{{ old('descripcion', $servicio->descripcion) }}">
    @error('descripcion') <p>{{ $message }}</p> @enderror

    <label>Precio:</label>
    <input name="precio" type="number" value="{{ old('precio', $servicio->precio) }}">
    @error('precio') <p>{{ $message }}</p> @enderror

    <label>Moneda:</label>
    <select name="moneda">
        @foreach(['UF', 'USD', 'CLP'] as $moneda)
            <option value="{{ $moneda }}" {{ $servicio->moneda == $moneda ? 'selected' : '' }}>
                {{ $moneda }}
            </option>
        @endforeach
    </select>
    @error('moneda') <p>{{ $message }}</p> @enderror

    <button type="submit">Actualizar</button>
</form>
