<?php $__env->startSection('title', 'Create Master Course'); ?>
<?php $__env->startSection('page_title', 'Create Master Course'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal82ab54972644d6b4d7be47c924a4c8d3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82ab54972644d6b4d7be47c924a4c8d3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.create-form-layout','data' => ['backHref' => route('admin.academic.index'),'backLabel' => 'Back to courses & batches','title' => 'New master course','subtitle' => 'Add a master course to the academic registry. You can configure landing page content, faculty, and batches after saving.','action' => route('admin.courses.store'),'submitLabel' => 'Create course']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('create-form-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['back-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.academic.index')),'back-label' => 'Back to courses & batches','title' => 'New master course','subtitle' => 'Add a master course to the academic registry. You can configure landing page content, faculty, and batches after saving.','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.courses.store')),'submit-label' => 'Create course']); ?>
    <?php if (isset($component)) { $__componentOriginald2ce725820501b1c9aaa4360621f0805 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald2ce725820501b1c9aaa4360621f0805 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.master-course-fields','data' => ['categories' => $categories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.master-course-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald2ce725820501b1c9aaa4360621f0805)): ?>
<?php $attributes = $__attributesOriginald2ce725820501b1c9aaa4360621f0805; ?>
<?php unset($__attributesOriginald2ce725820501b1c9aaa4360621f0805); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald2ce725820501b1c9aaa4360621f0805)): ?>
<?php $component = $__componentOriginald2ce725820501b1c9aaa4360621f0805; ?>
<?php unset($__componentOriginald2ce725820501b1c9aaa4360621f0805); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82ab54972644d6b4d7be47c924a4c8d3)): ?>
<?php $attributes = $__attributesOriginal82ab54972644d6b4d7be47c924a4c8d3; ?>
<?php unset($__attributesOriginal82ab54972644d6b4d7be47c924a4c8d3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82ab54972644d6b4d7be47c924a4c8d3)): ?>
<?php $component = $__componentOriginal82ab54972644d6b4d7be47c924a4c8d3; ?>
<?php unset($__componentOriginal82ab54972644d6b4d7be47c924a4c8d3); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/admin/academic/courses/create.blade.php ENDPATH**/ ?>