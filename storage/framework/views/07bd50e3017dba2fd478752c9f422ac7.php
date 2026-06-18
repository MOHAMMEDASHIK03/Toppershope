<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'rows' => 3,
    'options' => [],
    'colSpan' => '',
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
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'rows' => 3,
    'options' => [],
    'colSpan' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all [&>option]:text-slate-900';
    $labelClass = 'block text-sm font-semibold text-slate-700 mb-1.5';
    $id = $attributes->get('id', $name);
?>

<div class="<?php echo e($colSpan); ?>">
    <label for="<?php echo e($id); ?>" class="<?php echo e($labelClass); ?>">
        <?php echo e($label); ?>

        <?php if($required): ?><span class="text-red-500">*</span><?php endif; ?>
    </label>

    <?php if($type === 'textarea'): ?>
        <textarea
            id="<?php echo e($id); ?>"
            name="<?php echo e($name); ?>"
            rows="<?php echo e($rows); ?>"
            <?php if($required): ?> required <?php endif; ?>
            placeholder="<?php echo e($placeholder); ?>"
            <?php echo e($attributes->merge(['class' => $inputClass . ' resize-none'])); ?>

        ><?php echo e(old($name, $value)); ?></textarea>
    <?php elseif($type === 'select'): ?>
        <select
            id="<?php echo e($id); ?>"
            name="<?php echo e($name); ?>"
            <?php if($required): ?> required <?php endif; ?>
            <?php echo e($attributes->merge(['class' => $inputClass])); ?>

        >
            <?php echo e($slot); ?>

        </select>
    <?php elseif(in_array($type, ['date', 'month', 'datetime-local'], true)): ?>
        <input
            id="<?php echo e($id); ?>"
            type="<?php echo e($type); ?>"
            name="<?php echo e($name); ?>"
            value="<?php echo e(old($name, $value)); ?>"
            <?php if($required): ?> required <?php endif; ?>
            placeholder="<?php echo e($placeholder); ?>"
            <?php echo e($attributes->merge(['class' => $inputClass . ' th-picker-source'])); ?>

        />
    <?php else: ?>
        <input
            id="<?php echo e($id); ?>"
            type="<?php echo e($type); ?>"
            name="<?php echo e($name); ?>"
            value="<?php echo e(old($name, $value)); ?>"
            <?php if($required): ?> required <?php endif; ?>
            placeholder="<?php echo e($placeholder); ?>"
            <?php echo e($attributes->merge(['class' => $inputClass])); ?>

        />
    <?php endif; ?>

    <?php if($hint): ?>
        <p class="text-xs text-slate-500 mt-1"><?php echo e($hint); ?></p>
    <?php endif; ?>

    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-600 font-semibold mt-1"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/form/field.blade.php ENDPATH**/ ?>