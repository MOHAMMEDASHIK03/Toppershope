<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'formId' => null,
    'submitLabel' => 'Save',
    'cancelHref' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
    'stacked' => false,
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
    'formId' => null,
    'submitLabel' => 'Save',
    'cancelHref' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
    'stacked' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $stacked = filter_var($stacked, FILTER_VALIDATE_BOOLEAN);
?>

<div <?php echo e($attributes->merge(['class' => 'flex flex-wrap items-center ' . ($stacked ? 'flex-col' : 'justify-between') . ' gap-3 pt-6 mt-6 border-t border-slate-100'])); ?>>
    <div class="flex flex-wrap items-center gap-3 <?php echo e($stacked ? 'w-full flex-col' : ''); ?>">
        <button
            type="submit"
            <?php if($formId): ?> form="<?php echo e($formId); ?>" <?php endif; ?>
            class="btn-primary px-5 py-2.5 rounded-xl font-bold text-sm focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 <?php echo e($stacked ? 'w-full' : ''); ?>"
        >
            <?php echo e($submitLabel); ?>

        </button>
        <?php if($cancelHref): ?>
            <a
                href="<?php echo e($cancelHref); ?>"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-center <?php echo e($stacked ? 'w-full' : ''); ?>"
            >
                Cancel
            </a>
        <?php endif; ?>
    </div>

    <?php if($deleteAction): ?>
        <form
            action="<?php echo e($deleteAction); ?>"
            method="POST"
            class="<?php echo e($stacked ? 'w-full' : 'inline-flex'); ?>"
            data-confirm="<?php echo e($deleteConfirm); ?>"
        >
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button
                type="submit"
                class="btn-danger px-5 py-2.5 rounded-xl font-bold text-sm focus:ring-2 focus:ring-offset-2 focus:ring-red-500 <?php echo e($stacked ? 'w-full' : ''); ?>"
            >
                <?php echo e($deleteLabel); ?>

            </button>
        </form>
    <?php endif; ?>
</div>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/form-footer.blade.php ENDPATH**/ ?>