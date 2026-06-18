<?php if (isset($component)) { $__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.auth-page','data' => ['pageTitle' => 'Admin Sign In','heading' => 'Admin Console','subtitle' => 'Sign in with your super admin credentials.','formAction' => route('admin.login'),'accent' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.auth-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['page-title' => 'Admin Sign In','heading' => 'Admin Console','subtitle' => 'Sign in with your super admin credentials.','form-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.login')),'accent' => 'orange']); ?>
     <?php $__env->slot('afterForm', null, []); ?> 
        <p class="text-center mt-5 text-sm text-slate-500">
            <a href="<?php echo e(route('admin.password.forgot')); ?>" class="font-semibold text-orange-600 hover:text-orange-700">Forgot password?</a>
        </p>
     <?php $__env->endSlot(); ?>
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
<?php /**PATH D:\Lama Projects\Toppershope\laravel_core\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>