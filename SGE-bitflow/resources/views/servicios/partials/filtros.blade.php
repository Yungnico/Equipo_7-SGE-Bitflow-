<form method="GET" action="{{ route('servicios.index') }}" class="mb-4">
    <div class="container">
        <div class="row justify-content-center align-items-center g-2">
            <div class="col-md-6">
                <input type="text" name="nombre_servicio" class="form-control form-control-lg" placeholder="Buscar servicio..." value="{{ request('nombre_servicio') }}">
            </div>
            <div class="col-md-2">
                <select name="moneda" class="form-select text-center">
                    <option value="">Moneda</option>
                    <option value="UF" {{ request('moneda') == 'UF' ? 'selected' : '' }}>UF</option>
                    <option value="USD" {{ request('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="CLP" {{ request('moneda') == 'CLP' ? 'selected' : '' }}>CLP</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="categoria_id" class="form-select text-center">
                    <option value="">Categoría</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100 text-white" type="submit">Buscar</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('servicios.index') }}" class="btn btn-danger w-100 text-white">Reset</a>
            </div>
        </div>
    </div>
</form>