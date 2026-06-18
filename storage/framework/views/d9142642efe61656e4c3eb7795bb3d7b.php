<?php if (isset($component)) { $__componentOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3eaab2aeffe361fa2cd836ca0c8c5e7b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.auth-page','data' => ['pageTitle' => 'Student Sign In','heading' => 'Student learning','subtitle' => 'Sign in to access your courses, test series, and doubt forum.','formAction' => route('login'),'buttonLabel' => 'Sign in','accent' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.auth-page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['page-title' => 'Student Sign In','heading' => 'Student learning','subtitle' => 'Sign in to access your courses, test series, and doubt forum.','form-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('login')),'button-label' => 'Sign in','accent' => 'orange']); ?>
     <?php $__env->slot('afterForm', null, []); ?> 
        <p class="text-center text-sm text-slate-500 mt-6 pt-6 border-t border-slate-100">
            New to Topper&rsquo;s Hope?
            <a href="<?php echo e(route('register')); ?>" class="font-semibold text-orange-600 hover:text-orange-700">Create an account</a>
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
<?php /**PATH D:\Lama Projects\Toppershope\laravel_core\resources\views/auth/login.blade.php ENDPATH**/ ?>