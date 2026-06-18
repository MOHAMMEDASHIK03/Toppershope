

<?php $__env->startSection('title', 'My Assigned Courses'); ?>
<?php $__env->startSection('page_title', 'My Assigned Courses'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Welcome, <?php echo e(Auth::user()->name); ?></h2>
        <p class="text-sm text-slate-500 mt-1">Select a course below to manage its content, students, and doubts.</p>
    </div>

    <?php if($courses->isEmpty()): ?>
        <?php if (isset($component)) { $__componentOriginal99089f8e2ef4184d7d35db81d60c6521 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.empty-state','data' => ['title' => 'No courses assigned','description' => 'You have not been assigned to any master courses yet. If you believe this is a mistake, please contact the administration.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No courses assigned','description' => 'You have not been assigned to any master courses yet. If you believe this is a mistake, please contact the administration.']); ?>
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
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-orange-200 transition-all">
                    <div class="relative">
                        <?php if (isset($component)) { $__componentOriginal72e9eaecc55ca07a7df6a38a8aa33c46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72e9eaecc55ca07a7df6a38a8aa33c46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.course.cover','data' => ['course' => $course,'height' => 'h-40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('course.cover'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['course' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($course),'height' => 'h-40']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72e9eaecc55ca07a7df6a38a8aa33c46)): ?>
<?php $attributes = $__attributesOriginal72e9eaecc55ca07a7df6a38a8aa33c46; ?>
<?php unset($__attributesOriginal72e9eaecc55ca07a7df6a38a8aa33c46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72e9eaecc55ca07a7df6a38a8aa33c46)): ?>
<?php $component = $__componentOriginal72e9eaecc55ca07a7df6a38a8aa33c46; ?>
<?php unset($__componentOriginal72e9eaecc55ca07a7df6a38a8aa33c46); ?>
<?php endif; ?>
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-white/95 backdrop-blur text-slate-700 text-[10px] font-semibold px-2 py-1 rounded-md uppercase tracking-wide shadow-sm border border-slate-100">
                                <?php echo e($course->category?->name ?? 'General'); ?>

                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-semibold text-slate-900 text-base leading-snug mb-3 group-hover:text-orange-600 transition-colors line-clamp-2">
                            <?php echo e($course->name); ?>

                        </h3>

                        <div class="mt-auto flex flex-wrap gap-2">
                            <span class="inline-flex items-center text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                                <?php echo e($course->batches_count); ?> <?php echo e(Str::plural('batch', $course->batches_count)); ?>

                            </span>
                            <?php if($course->is_published): ?>
                                <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-md">
                                    Published
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center text-xs font-semibold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-1 rounded-md">
                                    Draft
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/80">
                        <a href="<?php echo e(route('faculty.courses.curriculum', $course->id)); ?>" class="btn-primary w-full py-2.5 rounded-lg text-sm font-semibold justify-center">
                            Manage course
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/faculty/assigned_courses.blade.php ENDPATH**/ ?>