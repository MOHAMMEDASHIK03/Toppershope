

<?php $__env->startSection('sidebar-nav'); ?>
    <?php echo $__env->make('components.panel.nav.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel.shell', [
    'panelKey' => 'student',
    'consoleTitle' => "Topper's Hope",
    'consoleSubtitle' => 'Student Learning',
    'guard' => null,
    'userRole' => ucfirst(auth()->user()->target_exam ?? 'Student'),
    'logoutRoute' => 'logout',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/student/layouts/app.blade.php ENDPATH**/ ?>