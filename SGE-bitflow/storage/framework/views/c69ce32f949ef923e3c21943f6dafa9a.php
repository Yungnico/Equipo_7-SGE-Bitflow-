<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>Welcome to this beautiful admin panel.</p>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Clientes</h5>
            <p class="card-text">Ingresar, editar o eliminar clientes registrados en el sistema.</p>
            <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-primary">Ir a Gestión de clientes</a>
        </div>
    </div>


    <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\javie\Desktop\xampp\htdocs\xampp\Equipo_7-SGE-Bitflow-\SGE-bitflow\resources\views/dashboard.blade.php ENDPATH**/ ?>