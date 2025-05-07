<div class="card">
    <div class="card-header">
        <h3 class="card-title">Formulario de Cliente</h3>
    </div>
    <div class="card-body">
        @csrf

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="razon_social">Razón Social*</label>
                <input type="text" class="form-control" name="razon_social" value="{{ old('razon_social', $cliente->razon_social ?? '') }}" maxlength="100" required>
            </div>

            <div class="form-group col-md-6">
                <label for="rut">RUT*</label>
                <input type="text" class="form-control" name="rut" value="{{ old('rut', $cliente->rut ?? '') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre_fantasia">Nombre Fantasía</label>
                <input type="text" class="form-control" name="nombre_fantasia" value="{{ old('nombre_fantasia', $cliente->nombre_fantasia ?? '') }}" maxlength="100">
            </div>

            <div class="form-group col-md-6">
                <label for="giro">Giro</label>
                <input type="text" class="form-control" name="giro" value="{{ old('giro', $cliente->giro ?? '') }}" maxlength="100">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" name="direccion" value="{{ old('direccion', $cliente->direccion ?? '') }}" maxlength="150">
            </div>

            <div class="form-group col-md-6">
                <label for="logo">Logo (JPG o PNG)</label>
                <input type="file" class="form-control" name="logo">
                @if(isset($cliente) && $cliente->logo)
                    <p class="mt-2">Logo actual:</p>
                    <img src="{{ asset('storage/' . $cliente->logo) }}" width="100">
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>
