<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'backHref',
    'backLabel' => 'Back',
    'title',
    'subtitle' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Create',
    'cancelHref' => null,
    'maxWidth' => 'max-w-3xl',
    'layoutView' => null,
    'enctype' => null,
    'submitIcon' => 'ph-check-circle',
    'formId' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'backHref',
    'backLabel' => 'Back',
    'title',
    'subtitle' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Create',
    'cancelHref' => null,
    'maxWidth' => 'max-w-3xl',
    'layoutView' => null,
    'enctype' => null,
    'submitIcon' => 'ph-check-circle',
    'formId' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cancelHref = $cancelHref ?? $backHref;
    $httpMethod = strtoupper($method);
    $backHoverClass = 'hover:text-orange-600';
    $formId = $formId ?? 'panel-create-form-' . substr(md5($action), 0, 8);
?>

<div class="<?php echo e($maxWidth); ?>">
    <a href="<?php echo e($backHref); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 <?php echo e($backHoverClass); ?> transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        <?php echo e($backLabel); ?>

    </a>

    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-slate-800"><?php echo e($title); ?></h2>
        <?php if($subtitle): ?>
            <p class="text-sm text-slate-500 mt-1"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
        <form
            id="<?php echo e($formId); ?>"
            action="<?php echo e($action); ?>"
            method="POST"
            <?php if($enctype): ?> enctype="<?php echo e($enctype); ?>" <?php endif; ?>
            class="space-y-6"
        >
            <?php echo csrf_field(); ?>
            <?php if(!in_array($httpMethod, ['GET', 'POST'])): ?>
                <?php echo method_field($httpMethod); ?>
            <?php endif; ?>

            <?php echo e($slot); ?>

        </form>

        <?php if(isset($footer)): ?>
            <div class="pt-2 border-t border-slate-100 mt-6">
                <?php echo e($footer); ?>

            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginale8edac27d0e943cf5f9a98f036e28e27 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale8edac27d0e943cf5f9a98f036e28e27 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.form-footer','data' => ['formId' => $formId,'submitLabel' => $submitLabel,'cancelHref' => $cancelHref,'deleteAction' => $deleteAction,'deleteLabel' => $deleteLabel,'deleteConfirm' => $deleteConfirm,'class' => '!mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.form-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['form-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formId),'submit-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($submitLabel),'cancel-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cancelHref),'delete-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deleteAction),'delete-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deleteLabel),'delete-confirm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deleteConfirm),'class' => '!mt-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale8edac27d0e943cf5f9a98f036e28e27)): ?>
<?php $attributes = $__attributesOriginale8edac27d0e943cf5f9a98f036e28e27; ?>
<?php unset($__attributesOriginale8edac27d0e943cf5f9a98f036e28e27); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale8edac27d0e943cf5f9a98f036e28e27)): ?>
<?php $component = $__componentOriginale8edac27d0e943cf5f9a98f036e28e27; ?>
<?php unset($__componentOriginale8edac27d0e943cf5f9a98f036e28e27); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/create-form-layout.blade.php ENDPATH**/ ?>