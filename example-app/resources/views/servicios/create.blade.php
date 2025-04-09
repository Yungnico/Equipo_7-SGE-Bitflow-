<h1>Registrar Servicio</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('servicios.store') }}">
    @csrf
    <label>Nombre del servicio:</label>
    <input name="nombre_servicio" value="{{ old('nombre_servicio') }}">
    @error('nombre_servicio') <p>{{ $message }}</p> @enderror

    <label>Descripción:</label>
    <input name="descripcion" value="{{ old('descripcion') }}">
    @error('descripcion') <p>{{ $message }}</p> @enderror

    <label>Precio:</label>
    <input name="precio" type="number" value="{{ old('precio') }}">
    @error('precio') <p>{{ $message }}</p> @enderror

    <label>Moneda:</label>
    <select name="moneda">
        <option value="UF">UF</option>
        <option value="USD">USD</option>
        <option value="CLP">CLP</option>
    </select>
    @error('moneda') <p>{{ $message }}</p> @enderror

    <button type="submit">Registrar</button>
    <a class="btn btn-sm btn-success" href={{ route('servicios.index') }}>View service</a>
</form>
