
<?php $__env->startSection('title', 'Academic Overview'); ?>
<?php $__env->startSection('page_title', 'Courses & Batches'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Academic offerings','subtitle' => 'Create and configure master courses and batches from the admin hub.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Academic offerings','subtitle' => 'Create and configure master courses and batches from the admin hub.']); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn-primary">
            <i class="ph ph-plus"></i> New master course
        </a>
     <?php $__env->endSlot(); ?>
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php if (isset($component)) { $__componentOriginalad5130b5347ab6ecc017d2f5a278b926 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad5130b5347ab6ecc017d2f5a278b926 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.card','data' => ['title' => 'Active courses','padding' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Active courses','padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
        <div class="panel-table-wrap">
            <table class="admin-table w-full text-left">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="font-semibold text-slate-800"><?php echo e($course->name); ?></span>
                                <?php if(!$course->is_published): ?>
                                    <span class="ml-2 px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-slate-600"><?php echo e($course->category?->name ?? '—'); ?></td>
                            <td class="text-right whitespace-nowrap">
                                <a href="<?php echo e(route('admin.courses.edit', $course->id)); ?>" class="text-orange-600 hover:text-indigo-800 font-semibold text-sm mr-2">Edit</a>
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold"><?php echo e($course->batches_count); ?> batches</span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="py-10 text-center">
                                <p class="text-slate-500 text-sm mb-4">No courses published yet.</p>
                                <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn-primary text-sm inline-flex">
                                    <i class="ph ph-plus"></i> Create first course
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
            <h3 class="font-semibold text-slate-900">Latest batches</h3>
            <span class="text-xs font-semibold uppercase text-slate-500">Top 10</span>
         <?php $__env->endSlot(); ?>
        <div class="panel-table-wrap">
            <table class="admin-table w-full text-left">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Status</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="font-semibold text-slate-800 block"><?php echo e($batch->name); ?></span>
                                <span class="text-xs text-slate-500"><?php echo e($batch->course?->name ?? 'Unlinked course'); ?></span>
                            </td>
                            <td>
                                <?php if($batch->is_upcoming): ?>
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-amber-50 text-amber-700">Upcoming</span>
                                <?php elseif($batch->status === 'filling_fast'): ?>
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-rose-50 text-rose-700">Filling fast</span>
                                <?php else: ?>
                                    <span class="badge-active">Active</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right font-semibold text-slate-800">₹<?php echo e(number_format($batch->price)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="text-center text-slate-500 py-8">No upcoming or active batches.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/admin/academic/index.blade.php ENDPATH**/ ?>