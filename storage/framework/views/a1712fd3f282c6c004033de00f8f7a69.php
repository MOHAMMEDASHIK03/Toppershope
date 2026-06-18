
<?php $__env->startSection('title', 'Master Course Configurator: ' . $course->name); ?>
<?php $__env->startSection('page_title', 'Master Course Configurator'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginalcb2e6e824df7f8cfbcfd6063059b7660 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb2e6e824df7f8cfbcfd6063059b7660 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.academic.mega-course-configurator','data' => ['course' => $course,'categories' => $categories,'backHref' => route('faculty.head.courses.index'),'backLabel' => 'Back to courses','formAction' => route('faculty.head.courses.update', $course->id),'batchStoreUrl' => route('faculty.head.batches.store', $course->id),'batchUpdateUrlPrefix' => url('/faculty/head/courses/' . $course->id . '/batches'),'extendedBatches' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('academic.mega-course-configurator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['course' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($course),'categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'back-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('faculty.head.courses.index')),'back-label' => 'Back to courses','form-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('faculty.head.courses.update', $course->id)),'batch-store-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('faculty.head.batches.store', $course->id)),'batch-update-url-prefix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(url('/faculty/head/courses/' . $course->id . '/batches')),'extended-batches' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb2e6e824df7f8cfbcfd6063059b7660)): ?>
<?php $attributes = $__attributesOriginalcb2e6e824df7f8cfbcfd6063059b7660; ?>
<?php unset($__attributesOriginalcb2e6e824df7f8cfbcfd6063059b7660); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb2e6e824df7f8cfbcfd6063059b7660)): ?>
<?php $component = $__componentOriginalcb2e6e824df7f8cfbcfd6063059b7660; ?>
<?php unset($__componentOriginalcb2e6e824df7f8cfbcfd6063059b7660); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/faculty/admin/courses/edit.blade.php ENDPATH**/ ?>