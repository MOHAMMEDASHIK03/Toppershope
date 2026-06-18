<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'padding' => true,
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
    'title' => null,
    'padding' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white dark:bg-[#1E1E24] border border-gray-200 dark:border-[#2D2D35] rounded-xl shadow-sm overflow-hidden'])); ?>>
    <?php if($title || isset($header)): ?>
        <div class="px-5 py-4 border-b border-gray-100 dark:border-[#2D2D35] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <?php if($title): ?>
                <h3 class="font-semibold text-gray-900 dark:text-white"><?php echo e($title); ?></h3>
            <?php endif; ?>
            <?php if(isset($header)): ?>
                <?php echo e($header); ?>

            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([$padding ? 'p-5' : '']); ?>">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/admin/card.blade.php ENDPATH**/ ?>