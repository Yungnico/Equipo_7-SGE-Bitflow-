<div class="mb-3">
    <label for="nombre_contacto" class="form-label">Nombre</label>
    <input type="text" name="nombre_contacto" class="form-control" value="{{ old('nombre_contacto', $contacto->nombre_contacto ?? '') }}">
</div>

<div class="mb-3">
    <label for="email_contacto" class="form-label">Correo</label>
    <input type="email" name="email_contacto" class="form-control" value="{{ old('email_contacto', $contacto->email_contacto ?? '') }}">
</div>

<div class="mb-3">
    <label for="telefono_contacto" class="form-label">Teléfono</label>
    <input type="text" name="telefono_contacto" class="form-control" value="{{ old('telefono_contacto', $contacto->telefono_contacto ?? '') }}">
</div>

<div class="mb-3">
    <label for="tipo_contacto" class="form-label">Tipo de Contacto</label>
    <select name="tipo_contacto" class="form-select">
        <option value="">Seleccione</option>
        <option value="Comercial" {{ old('tipo_contacto', $contacto->tipo_contacto ?? '') == 'Comercial' ? 'selected' : '' }}>Comercial</option>
        <option value="TI" {{ old('tipo_contacto', $contacto->tipo_contacto ?? '') == 'TI' ? 'selected' : '' }}>TI</option>
        <option value="Contable" {{ old('tipo_contacto', $contacto->tipo_contacto ?? '') == 'Contable' ? 'selected' : '' }}>Contable</option>
    </select>
</div>
