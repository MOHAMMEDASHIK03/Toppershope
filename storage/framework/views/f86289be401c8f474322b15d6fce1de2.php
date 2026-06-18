

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('components.panel.nav.hr', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel.shell', [
    'panelKey' => 'hr',
    'consoleTitle' => 'HR Panel',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => 'hr',
    'userRole' => 'HR Department',
    'logoutRoute' => 'hr.logout',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/hr/layouts/app.blade.php ENDPATH**/ ?>