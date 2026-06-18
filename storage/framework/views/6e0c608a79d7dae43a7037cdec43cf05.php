<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Overview</p>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>
<a href="<?php echo e(route('admin.audit-logs.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.audit-logs.*') ? 'active' : ''); ?>" title="Audit Logs">
    <i class="ph ph-list-bullets"></i>
    <span class="sidebar-link-text truncate">Audit Logs</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Academic</p>
<a href="<?php echo e(route('admin.academic.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.academic.index') ? 'active' : ''); ?>" title="Academic overview">
    <i class="ph ph-chart-pie"></i>
    <span class="sidebar-link-text truncate">Academic Overview</span>
</a>
<a href="<?php echo e(route('admin.courses.create')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.courses.*') ? 'active' : ''); ?>" title="Manage courses">
    <i class="ph ph-graduation-cap"></i>
    <span class="sidebar-link-text truncate">Courses & Batches</span>
</a>
<a href="<?php echo e(route('admin.categories.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>" title="Categories">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Categories</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">People</p>
<a href="<?php echo e(route('admin.hr-users.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.hr-users.*') ? 'active' : ''); ?>" title="HR Users">
    <i class="ph ph-users"></i>
    <span class="sidebar-link-text truncate">HR Users</span>
</a>
<a href="<?php echo e(route('admin.staff.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.staff.*') ? 'active' : ''); ?>" title="Staff Registry">
    <i class="ph ph-identification-badge"></i>
    <span class="sidebar-link-text truncate">Staff Registry</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Infrastructure</p>
<a href="<?php echo e(route('admin.branches.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.branches.*') ? 'active' : ''); ?>" title="Branches">
    <i class="ph ph-buildings"></i>
    <span class="sidebar-link-text truncate">Branches</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Security</p>
<a href="<?php echo e(route('admin.access-control.password-reset')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.access-control.password-reset') ? 'active' : ''); ?>" title="Password Reset">
    <i class="ph ph-key"></i>
    <span class="sidebar-link-text truncate">Password Reset</span>
</a>
<a href="<?php echo e(route('admin.access-control.sessions')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.access-control.sessions') ? 'active' : ''); ?>" title="Active Sessions">
    <i class="ph ph-desktop"></i>
    <span class="sidebar-link-text truncate">Active Sessions</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Panels</p>
<a href="<?php echo e(route('admin.impersonate', 'faculty')); ?>" target="_blank" class="sidebar-link" title="Faculty">
    <i class="ph ph-chalkboard-teacher"></i>
    <span class="sidebar-link-text truncate">Faculty</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<a href="<?php echo e(route('admin.impersonate', 'admission')); ?>" target="_blank" class="sidebar-link" title="Admission">
    <i class="ph ph-phone"></i>
    <span class="sidebar-link-text truncate">Admission</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<a href="<?php echo e(route('admin.impersonate', 'ads')); ?>" target="_blank" class="sidebar-link" title="Ads">
    <i class="ph ph-megaphone"></i>
    <span class="sidebar-link-text truncate">Ads</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<a href="<?php echo e(route('admin.impersonate', 'hr')); ?>" target="_blank" class="sidebar-link" title="HR Panel">
    <i class="ph ph-briefcase"></i>
    <span class="sidebar-link-text truncate">HR Panel</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/nav/admin.blade.php ENDPATH**/ ?>