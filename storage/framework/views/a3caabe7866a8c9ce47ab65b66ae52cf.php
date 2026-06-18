

<?php $__env->startPush('panel-extra-styles'); ?>
    <?php echo $__env->make('components.panel.faculty-compat-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('components.panel.nav.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel.shell', [
    'panelKey' => 'faculty',
    'consoleTitle' => Auth::user()->isFacultyHead() ? 'Faculty Head Panel' : 'Faculty Portal',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => null,
    'userRole' => Auth::user()->isFacultyHead() ? 'Faculty Head' : 'Faculty',
    'logoutRoute' => 'faculty.logout',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/layouts/faculty.blade.php ENDPATH**/ ?>