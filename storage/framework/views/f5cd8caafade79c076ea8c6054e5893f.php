<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'categories' => null,
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
    'categories' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div>
    <?php if (isset($component)) { $__componentOriginal45920e144996b26f3340500ed9e02bd3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45920e144996b26f3340500ed9e02bd3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.field','data' => ['label' => 'Course title','name' => 'title','required' => true,'placeholder' => 'e.g. IIT JEE Complete Preparation']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Course title','name' => 'title','required' => true,'placeholder' => 'e.g. IIT JEE Complete Preparation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45920e144996b26f3340500ed9e02bd3)): ?>
<?php $attributes = $__attributesOriginal45920e144996b26f3340500ed9e02bd3; ?>
<?php unset($__attributesOriginal45920e144996b26f3340500ed9e02bd3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45920e144996b26f3340500ed9e02bd3)): ?>
<?php $component = $__componentOriginal45920e144996b26f3340500ed9e02bd3; ?>
<?php unset($__componentOriginal45920e144996b26f3340500ed9e02bd3); ?>
<?php endif; ?>
</div>
<div>
    <?php if (isset($component)) { $__componentOriginal45920e144996b26f3340500ed9e02bd3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45920e144996b26f3340500ed9e02bd3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.field','data' => ['label' => 'Description','name' => 'description','type' => 'textarea','required' => true,'rows' => '3','placeholder' => 'Brief scope of this master course...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Description','name' => 'description','type' => 'textarea','required' => true,'rows' => '3','placeholder' => 'Brief scope of this master course...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45920e144996b26f3340500ed9e02bd3)): ?>
<?php $attributes = $__attributesOriginal45920e144996b26f3340500ed9e02bd3; ?>
<?php unset($__attributesOriginal45920e144996b26f3340500ed9e02bd3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45920e144996b26f3340500ed9e02bd3)): ?>
<?php $component = $__componentOriginal45920e144996b26f3340500ed9e02bd3; ?>
<?php unset($__componentOriginal45920e144996b26f3340500ed9e02bd3); ?>
<?php endif; ?>
</div>
<div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 space-y-4 shadow-sm">
    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Classification</p>
    <?php if (isset($component)) { $__componentOriginalbf3030983295dc989f62e6c4f1719d92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbf3030983295dc989f62e6c4f1719d92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.category-fields','data' => ['categories' => $categories,'subcategoryRequired' => false,'layout' => 'stacked']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('category-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'subcategory-required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'layout' => 'stacked']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbf3030983295dc989f62e6c4f1719d92)): ?>
<?php $attributes = $__attributesOriginalbf3030983295dc989f62e6c4f1719d92; ?>
<?php unset($__attributesOriginalbf3030983295dc989f62e6c4f1719d92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbf3030983295dc989f62e6c4f1719d92)): ?>
<?php $component = $__componentOriginalbf3030983295dc989f62e6c4f1719d92; ?>
<?php unset($__componentOriginalbf3030983295dc989f62e6c4f1719d92); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/forms/master-course-fields.blade.php ENDPATH**/ ?>