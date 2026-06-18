
<?php $__env->startSection('title', 'Audit Logs'); ?>
<?php $__env->startSection('page_title', 'Audit Logs'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Ecosystem activity monitor','subtitle' => 'Critical actions across HR, Ads, Admission, and Admin panels.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ecosystem activity monitor','subtitle' => 'Critical actions across HR, Ads, Admission, and Admin panels.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $attributes = $__attributesOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__attributesOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $component = $__componentOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__componentOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['padding' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h3 class="font-semibold text-slate-900">Cross-panel security logs</h3>
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search action or description…"
                class="flex-1 sm:w-64 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
            <button type="submit" class="btn-primary text-sm py-2">Search</button>
        </form>
     <?php $__env->endSlot(); ?>

    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User / panel</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="whitespace-nowrap">
                            <span class="font-medium text-slate-800"><?php echo e($log->created_at->format('Y-m-d H:i')); ?></span>
                            <span class="block text-xs text-slate-500"><?php echo e($log->created_at->diffForHumans()); ?></span>
                        </td>
                        <td>
                            <?php if($log->user): ?>
                                <span class="font-semibold text-slate-800 block"><?php echo e($log->user->name ?? ($log->user->first_name . ' ' . $log->user->last_name)); ?></span>
                                <span class="text-xs text-orange-600 font-medium"><?php echo e(str_replace('App\\Models\\', '', $log->user_type)); ?><?php if($log->user_id): ?> #<?php echo e($log->user_id); ?><?php endif; ?></span>
                            <?php else: ?>
                                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded">System</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="inline-block px-2.5 py-1 bg-orange-50 text-indigo-700 border border-indigo-100 text-xs font-semibold uppercase rounded-lg">
                                <?php echo e(str_replace('_', ' ', $log->action)); ?>

                            </span>
                        </td>
                        <td class="min-w-[240px]">
                            <p class="text-slate-600"><?php echo e($log->description ?: 'No additional details.'); ?></p>
                            <p class="text-[10px] text-slate-400 mt-1">IP: <?php echo e($log->ip_address ?? 'N/A'); ?></p>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4">
                            <?php if (isset($component)) { $__componentOriginal99089f8e2ef4184d7d35db81d60c6521 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.empty-state','data' => ['title' => 'No activity logs','description' => 'No matching events in the monitoring system.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No activity logs','description' => 'No matching events in the monitoring system.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $attributes = $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $component = $__componentOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($component)) { $__componentOriginalcc86882631e63db27cd32df722dc48be = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc86882631e63db27cd32df722dc48be = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.pagination','data' => ['paginator' => $logs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc86882631e63db27cd32df722dc48be)): ?>
<?php $attributes = $__attributesOriginalcc86882631e63db27cd32df722dc48be; ?>
<?php unset($__attributesOriginalcc86882631e63db27cd32df722dc48be); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc86882631e63db27cd32df722dc48be)): ?>
<?php $component = $__componentOriginalcc86882631e63db27cd32df722dc48be; ?>
<?php unset($__componentOriginalcc86882631e63db27cd32df722dc48be); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $attributes = $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__attributesOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926)): ?>
<?php $component = $__componentOriginalad5130b5347ab6ecc017d2f5a278b926; ?>
<?php unset($__componentOriginalad5130b5347ab6ecc017d2f5a278b926); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/admin/audit-logs/index.blade.php ENDPATH**/ ?>