<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Learn</p>
<a href="<?php echo e(route('student.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>
<a href="<?php echo e(route('student.catalog')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.catalog*') ? 'active' : ''); ?>" title="Browse courses">
    <i class="ph ph-magnifying-glass"></i>
    <span class="sidebar-link-text truncate">Browse Courses</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Study</p>
<a href="<?php echo e(route('student.my-courses')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.my-courses*') ? 'active' : ''); ?>" title="My courses">
    <i class="ph ph-graduation-cap"></i>
    <span class="sidebar-link-text truncate">My Courses</span>
</a>
<a href="<?php echo e(route('student.quizzes')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.quizzes*') ? 'active' : ''); ?>" title="Test series">
    <i class="ph ph-exam"></i>
    <span class="sidebar-link-text truncate">Test Series</span>
</a>
<a href="<?php echo e(route('student.doubts')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.doubts*') ? 'active' : ''); ?>" title="Doubt forum">
    <i class="ph ph-chats-circle"></i>
    <span class="sidebar-link-text truncate">Doubt Forum</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Account</p>
<a href="<?php echo e(route('student.profile')); ?>" class="sidebar-link <?php echo e(request()->routeIs('student.profile') ? 'active' : ''); ?>" title="Profile">
    <i class="ph ph-user"></i>
    <span class="sidebar-link-text truncate">Profile</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/nav/student.blade.php ENDPATH**/ ?>