
<?php $__env->startSection('title', 'HR Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256"><path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Employees</p>
            <p class="text-2xl font-black text-slate-800"><?php echo e($totalEmployees); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM48,48H72v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48Zm160,160H48V96H208V208Z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Leave Requests</p>
            <p class="text-2xl font-black text-slate-800"><?php echo e($pendingLeaves); ?></p>
            <p class="text-[11px] text-amber-500 font-bold">Pending approval</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256"><path d="M208,40H160V32a16,16,0,0,0-16-16H112A16,16,0,0,0,96,32v8H48A16,16,0,0,0,32,56V96a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V56A16,16,0,0,0,208,40ZM112,32h32v8H112ZM144,144a16,16,0,0,1-32,0V128H48v80a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V128H144Z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Open Positions</p>
            <p class="text-2xl font-black text-slate-800"><?php echo e($openJobPostings); ?></p>
            <p class="text-[11px] text-emerald-600 font-bold">Active job postings</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48H32A16,16,0,0,0,16,64V192a16,16,0,0,0,16,16H224a16,16,0,0,0,16-16V64A16,16,0,0,0,224,48ZM32,64H224v16H32Zm0,128V96H224v96Z"/></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">This Month Payroll</p>
            <p class="text-2xl font-black text-slate-800">₹<?php echo e(number_format($thisMonthPayroll)); ?></p>
        </div>
    </div>

</div>


<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="<?php echo e(route('hr.employees.create')); ?>" class="flex flex-col items-center gap-2 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:border-indigo-200 hover:bg-orange-50/50 transition-all group">
        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M256,136a8,8,0,0,1-8,8H136v112a8,8,0,0,1-16,0V144H8a8,8,0,0,1,0-16H120V16a8,8,0,0,1,16,0V128H248A8,8,0,0,1,256,136Z"/></svg>
        </div>
        <span class="text-xs font-bold text-slate-600 text-center">Add Employee</span>
    </a>
    <a href="<?php echo e(route('hr.payroll.create')); ?>" class="flex flex-col items-center gap-2 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:border-emerald-200 hover:bg-emerald-50/50 transition-all group">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48H32A16,16,0,0,0,16,64V192a16,16,0,0,0,16,16H224a16,16,0,0,0,16-16V64A16,16,0,0,0,224,48ZM32,64H224v32H32Zm0,128V112H224v80Z"/></svg>
        </div>
        <span class="text-xs font-bold text-slate-600 text-center">Generate Payslip</span>
    </a>
    <a href="<?php echo e(route('hr.leaves.index')); ?>" class="flex flex-col items-center gap-2 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:border-amber-200 hover:bg-amber-50/50 transition-all group">
        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32Z"/></svg>
        </div>
        <span class="text-xs font-bold text-slate-600 text-center">Manage Leaves</span>
    </a>
    <a href="<?php echo e(route('hr.attendance.index')); ?>" class="flex flex-col items-center gap-2 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:border-blue-200 hover:bg-orange-50/50 transition-all group">
        <div class="w-10 h-10 rounded-xl bg-blue-100 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h48A8,8,0,0,1,192,128Z"/></svg>
        </div>
        <span class="text-xs font-bold text-slate-600 text-center">Attendance</span>
    </a>
</div>


<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
    <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-indigo-500 mx-auto mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40ZM40,56H216V88H40Zm0,144V104H216v96H40Z"/></svg>
    </div>
    <h3 class="text-base font-bold text-slate-900 mb-1">Human Resources Control Center</h3>
    <p class="text-sm font-medium text-slate-400 max-w-sm mx-auto">Manage employees, process payroll, approve leaves, and track performance from one place.</p>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('hr.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/hr/dashboard/index.blade.php ENDPATH**/ ?>