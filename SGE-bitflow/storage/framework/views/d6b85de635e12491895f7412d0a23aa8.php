

<?php $__env->startSection('content'); ?>
<div class="m-6">
    <h1 class="mb-4">Gestión de clientes</h1>

    
    
    <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary">+ Nuevo cliente</a>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-800 text-center text-sm md:text-base tracking-wide">
                        <th class="px-4 py-2">Razón Social</th>
                        <th class="px-4 py-2">RUT</th>
                        <th class="px-4 py-2">Nombre Fantasía</th>
                        <th class="px-4 py-2">Giro</th>
                        <th class="px-4 py-2">Dirección</th>
                        <th class="px-4 py-2">Logo</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($cliente->razon_social); ?></td>
                            <td><?php echo e($cliente->rut); ?></td>
                            <td><?php echo e($cliente->nombre_fantasia ?? '-'); ?></td>
                            <td><?php echo e($cliente->giro ?? '-'); ?></td>
                            <td><?php echo e($cliente->direccion ?? '-'); ?></td>
                            <td>
                                <?php if($cliente->logo): ?>
                                    <img src="<?php echo e(asset('storage/' . $cliente->logo)); ?>" alt="Logo" width="50">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-warning btn-sm">Editar</a>

                                <form action="<?php echo e(route('clientes.destroy', $cliente)); ?>" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center" >No hay clientes registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\javie\Desktop\xampp\htdocs\xampp\Equipo_7-SGE-Bitflow-\SGE-bitflow\resources\views/clientes/index.blade.php ENDPATH**/ ?>