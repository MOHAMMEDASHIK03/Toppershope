<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Overview</p>
<a href="<?php echo e(route('hr.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.dashboard') ? 'active' : ''); ?>" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Staff</p>
<a href="<?php echo e(route('hr.employees.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.employees.*') ? 'active' : ''); ?>" title="Employees">
    <i class="ph ph-users"></i>
    <span class="sidebar-link-text truncate">Employees</span>
</a>
<a href="<?php echo e(route('hr.departments.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.departments.*') ? 'active' : ''); ?>" title="Departments">
    <i class="ph ph-buildings"></i>
    <span class="sidebar-link-text truncate">Departments</span>
</a>
<a href="<?php echo e(route('hr.designations.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.designations.*') ? 'active' : ''); ?>" title="Designations">
    <i class="ph ph-identification-badge"></i>
    <span class="sidebar-link-text truncate">Designations</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Time & Attendance</p>
<a href="<?php echo e(route('hr.attendance.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.attendance.*') ? 'active' : ''); ?>" title="Attendance">
    <i class="ph ph-clock"></i>
    <span class="sidebar-link-text truncate">Attendance</span>
</a>
<a href="<?php echo e(route('hr.leaves.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.leaves.*') ? 'active' : ''); ?>" title="Leave requests">
    <i class="ph ph-calendar-x"></i>
    <span class="sidebar-link-text truncate">Leave Requests</span>
</a>
<a href="<?php echo e(route('hr.leave-types.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.leave-types.*') ? 'active' : ''); ?>" title="Leave policies">
    <i class="ph ph-calendar-check"></i>
    <span class="sidebar-link-text truncate">Leave Policies</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Payroll</p>
<a href="<?php echo e(route('hr.payroll.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.payroll.*') && ! request()->routeIs('hr.payroll.settings*') ? 'active' : ''); ?>" title="Payroll runs">
    <i class="ph ph-wallet"></i>
    <span class="sidebar-link-text truncate">Payroll Runs</span>
</a>
<a href="<?php echo e(route('hr.payroll.settings')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.payroll.settings*') ? 'active' : ''); ?>" title="Payroll settings">
    <i class="ph ph-gear"></i>
    <span class="sidebar-link-text truncate">Payroll Settings</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Performance</p>
<a href="<?php echo e(route('hr.kpis.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.kpis.*') ? 'active' : ''); ?>" title="KPI Framework">
    <i class="ph ph-chart-bar"></i>
    <span class="sidebar-link-text truncate">KPI Framework</span>
</a>
<a href="<?php echo e(route('hr.performance-reviews.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.performance-reviews.*') ? 'active' : ''); ?>" title="Reviews">
    <i class="ph ph-clipboard-text"></i>
    <span class="sidebar-link-text truncate">My Reviews</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Recruitment</p>
<a href="<?php echo e(route('hr.job-postings.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.job-postings.*') ? 'active' : ''); ?>" title="Careers">
    <i class="ph ph-briefcase"></i>
    <span class="sidebar-link-text truncate">Careers Hub</span>
</a>
<a href="<?php echo e(route('hr.job-applications.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.job-applications.*') || request()->routeIs('hr.interviews.*') ? 'active' : ''); ?>" title="Applicants">
    <i class="ph ph-user-list"></i>
    <span class="sidebar-link-text truncate">Applicants</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Company</p>
<a href="<?php echo e(route('hr.employee-documents.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.employee-documents.*') ? 'active' : ''); ?>" title="Documents">
    <i class="ph ph-files"></i>
    <span class="sidebar-link-text truncate">Documents</span>
</a>
<a href="<?php echo e(route('hr.announcements.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('hr.announcements.*') ? 'active' : ''); ?>" title="Announcements">
    <i class="ph ph-megaphone"></i>
    <span class="sidebar-link-text truncate">Announcements</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="<?php echo e(url('/')); ?>" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/nav/hr.blade.php ENDPATH**/ ?>