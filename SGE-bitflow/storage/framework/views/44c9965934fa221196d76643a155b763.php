
<?php echo csrf_field(); ?>

<div class="mb-3">
    <label for="razon_social" class="form-label">Razón Social*</label>
    <input type="text" class="form-control" name="razon_social" value="<?php echo e(old('razon_social', $cliente->razon_social ?? '')); ?>" maxlength="100" required>
</div>

<div class="mb-3">
    <label for="rut" class="form-label">RUT*</label>
    <input type="text" class="form-control" name="rut" value="<?php echo e(old('rut', $cliente->rut ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label for="nombre_fantasia" class="form-label">Nombre Fantasía</label>
    <input type="text" class="form-control" name="nombre_fantasia" value="<?php echo e(old('nombre_fantasia', $cliente->nombre_fantasia ?? '')); ?>" maxlength="100">
</div>

<div class="mb-3">
    <label for="giro" class="form-label">Giro</label>
    <input type="text" class="form-control" name="giro" value="<?php echo e(old('giro', $cliente->giro ?? '')); ?>" maxlength="100">
</div>

<div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" class="form-control" name="direccion" value="<?php echo e(old('direccion', $cliente->direccion ?? '')); ?>" maxlength="150">
</div>

<div class="mb-3">
    <label for="logo" class="form-label">Logo (JPG o PNG)</label>
    <input type="file" class="form-control " name="logo">
    <?php if(isset($cliente) && $cliente->logo): ?>
        <p>Logo actual:</p>
        <img src="<?php echo e(asset('storage/' . $cliente->logo)); ?>" width="100">
    <?php endif; ?>
</div>


<?php /**PATH C:\Users\javie\Desktop\xampp\htdocs\xampp\Equipo_7-SGE-Bitflow-\SGE-bitflow\resources\views/clientes/form.blade.php ENDPATH**/ ?>