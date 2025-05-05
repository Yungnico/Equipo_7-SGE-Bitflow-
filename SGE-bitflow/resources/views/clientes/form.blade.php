@csrf
 
 <div class="mb-3">
     <label for="razon_social" class="form-label">Razón Social*</label>
     <input type="text" class="form-control" name="razon_social" value="{{ old('razon_social', $cliente->razon_social ?? '') }}" maxlength="100" required>
 </div>
 
 <div class="mb-3">
     <label for="rut" class="form-label">RUT*</label>
     <input type="text" class="form-control" name="rut" value="{{ old('rut', $cliente->rut ?? '') }}" required>
 </div>
 
 <div class="mb-3">
     <label for="nombre_fantasia" class="form-label">Nombre Fantasía</label>
     <input type="text" class="form-control" name="nombre_fantasia" value="{{ old('nombre_fantasia', $cliente->nombre_fantasia ?? '') }}" maxlength="100">
 </div>
 
 <div class="mb-3">
     <label for="giro" class="form-label">Giro</label>
     <input type="text" class="form-control" name="giro" value="{{ old('giro', $cliente->giro ?? '') }}" maxlength="100">
 </div>
 
 <div class="mb-3">
     <label for="direccion" class="form-label">Dirección</label>
     <input type="text" class="form-control" name="direccion" value="{{ old('direccion', $cliente->direccion ?? '') }}" maxlength="150">
 </div>

 <div class="mb-3">
     <label for="logo" class="form-label">Logo (JPG o PNG)</label>
     <input type="file" class="form-control" name="logo">
     @if(isset($cliente) && $cliente->logo)
         <p>Logo actual:</p>
         <img src="{{ asset('storage/' . $cliente->logo) }}" width="100">
     @endif
 </div>
 
 <button type="submit" class="btn btn-success">Guardar</button>
 <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>