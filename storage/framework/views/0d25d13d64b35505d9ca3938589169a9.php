

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('components.panel.nav.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel.shell', [
    'panelKey' => 'admin',
    'consoleTitle' => "Topper's Hope",
    'consoleSubtitle' => 'Admin Console',
    'guard' => 'admin',
    'userRole' => 'Super Admin',
    'logoutRoute' => 'admin.logout',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>