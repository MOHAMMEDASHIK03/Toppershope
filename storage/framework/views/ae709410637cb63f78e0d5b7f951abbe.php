<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
    'accent' => 'primary',
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
    'value',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
    'accent' => 'primary',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $accentMap = [
        'primary' => 'text-primary-600 dark:text-primary-400',
        'slate' => 'text-gray-900 dark:text-white',
        'p50' => 'text-primary-500',
        'p60' => 'text-primary-600',
        'p70' => 'text-primary-700',
        'p80' => 'text-primary-800',
        /* legacy aliases */
        'indigo' => 'text-primary-600',
        'emerald' => 'text-primary-500',
        'sky' => 'text-primary-400',
        'violet' => 'text-primary-700',
        'rose' => 'text-primary-800',
        'amber' => 'text-primary-600',
    ];
    $valueClass = match ($accent) {
        'primary', 'indigo', 'amber' => 'text-primary-600 dark:text-primary-400',
        'slate' => 'text-gray-900 dark:text-white',
        'emerald' => 'text-emerald-600 dark:text-emerald-400',
        'sky' => 'text-sky-600 dark:text-sky-400',
        'violet' => 'text-primary-700 dark:text-primary-300',
        'rose' => 'text-rose-600 dark:text-rose-400',
        default => $accentMap[$accent] ?? 'text-primary-600 dark:text-primary-400',
    };
?>

<div <?php echo e($attributes->merge(['class' => 'bg-white dark:bg-[#18181c] border border-gray-200 dark:border-[#2a2a32] rounded-xl p-5 shadow-sm dark:shadow-none'])); ?>>
    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide"><?php echo e($label); ?></p>
    <p class="text-3xl font-bold <?php echo e($valueClass); ?> mt-2 tabular-nums"><?php echo e($value); ?></p>
    <?php if($trend !== null): ?>
        <p class="text-xs font-semibold mt-2 flex items-center gap-1 <?php echo e($trendUp ? 'text-green-600 dark:text-emerald-400' : 'text-red-600 dark:text-rose-400'); ?>">
            <?php if($trendUp): ?>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,117.66a8,8,0,0,1-11.32,0L136,59.31V216a8,8,0,0,1-16,0V59.31L61.66,117.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0l72,72A8,8,0,0,1,205.66,117.66Z"/></svg>
            <?php else: ?>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,149.66l-72,72a8,8,0,0,1-11.32,0l-72-72a8,8,0,0,1,11.32-11.32L120,196.69V40a8,8,0,0,1,16,0V196.69l58.34-58.35a8,8,0,0,1,11.32,11.32Z"/></svg>
            <?php endif; ?>
            <?php echo e($trend); ?>

        </p>
    <?php elseif($hint): ?>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>