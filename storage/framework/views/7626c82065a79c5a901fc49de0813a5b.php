<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Overview</p>
<a href="<?php echo e(route('faculty.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.dashboard') ? 'active' : ''); ?>" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>

<?php if(Auth::user()->isFacultyHead()): ?>
    <p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Administration</p>
    <a href="<?php echo e(route('faculty.head.courses.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.head.courses.*') ? 'active' : ''); ?>" title="Master courses">
        <i class="ph ph-books"></i>
        <span class="sidebar-link-text truncate">Master Courses</span>
    </a>
    <a href="<?php echo e(route('faculty.head.batches.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.head.batches.index') || request()->routeIs('faculty.head.batches.edit') || request()->routeIs('faculty.head.batches.store') || request()->routeIs('faculty.head.batches.update') || request()->routeIs('faculty.head.batches.destroy') ? 'active' : ''); ?>" title="Batches">
        <i class="ph ph-users-three"></i>
        <span class="sidebar-link-text truncate">Batches</span>
    </a>
    <a href="<?php echo e(route('faculty.head.faculties.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.head.faculties.index') || request()->routeIs('faculty.head.faculties.unassign') ? 'active' : ''); ?>" title="Faculties">
        <i class="ph ph-chalkboard-teacher"></i>
        <span class="sidebar-link-text truncate">Faculties</span>
    </a>
    <a href="<?php echo e(route('faculty.head.students.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.head.students.*') ? 'active' : ''); ?>" title="Students">
        <i class="ph ph-student"></i>
        <span class="sidebar-link-text truncate">Students</span>
    </a>
    <p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">My teaching</p>
<?php else: ?>
    <p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Teaching</p>
<?php endif; ?>

<a href="<?php echo e(route('faculty.my-courses')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.my-courses') || request()->routeIs('faculty.courses.*') ? 'active' : ''); ?>" title="My assigned courses">
    <i class="ph ph-chalkboard"></i>
    <span class="sidebar-link-text truncate">My Assigned Courses</span>
</a>
<a href="<?php echo e(route('faculty.meetings.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('faculty.meetings.*') ? 'active' : ''); ?>" title="Meetings">
    <i class="ph ph-video-camera"></i>
    <span class="sidebar-link-text truncate">Meetings</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/nav/faculty.blade.php ENDPATH**/ ?>