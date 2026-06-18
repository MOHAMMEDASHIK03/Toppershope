<?php if (isset($component)) { $__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.auth-page','data' => ['pageTitle' => 'HR Sign In','heading' => 'HR Access','subtitle' => 'Topper\'s Hope Human Resources','formAction' => route('hr.login'),'accent' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.auth-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['page-title' => 'HR Sign In','heading' => 'HR Access','subtitle' => 'Topper\'s Hope Human Resources','form-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('hr.login')),'accent' => 'indigo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b)): ?>
<?php $attributes = $__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b; ?>
<?php unset($__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b)): ?>
<?php $component = $__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b; ?>
<?php unset($__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b); ?>
<?php endif; ?>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/hr/auth/login.blade.php ENDPATH**/ ?>