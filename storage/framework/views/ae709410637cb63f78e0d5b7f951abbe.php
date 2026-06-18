<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
    'accent' => 'slate',
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
    'accent' => 'slate',
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
        'slate' => 'text-slate-900',
        'indigo' => 'text-indigo-600',
        'emerald' => 'text-emerald-600',
        'sky' => 'text-sky-600',
        'violet' => 'text-violet-600',
        'rose' => 'text-rose-600',
        'amber' => 'text-amber-600',
    ];
    $valueClass = $accentMap[$accent] ?? $accentMap['slate'];
?>

<div <?php echo e($attributes->merge(['class' => 'bg-white border border-slate-200 rounded-xl p-5 shadow-sm'])); ?>>
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide"><?php echo e($label); ?></p>
    <p class="text-3xl font-bold <?php echo e($valueClass); ?> mt-2 tabular-nums"><?php echo e($value); ?></p>
    <?php if($trend !== null): ?>
        <p class="text-xs font-semibold mt-2 flex items-center gap-1 <?php echo e($trendUp ? 'text-emerald-600' : 'text-rose-600'); ?>">
            <?php if($trendUp): ?>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,117.66a8,8,0,0,1-11.32,0L136,59.31V216a8,8,0,0,1-16,0V59.31L61.66,117.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0l72,72A8,8,0,0,1,205.66,117.66Z"/></svg>
            <?php else: ?>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,149.66l-72,72a8,8,0,0,1-11.32,0l-72-72a8,8,0,0,1,11.32-11.32L120,196.69V40a8,8,0,0,1,16,0V196.69l58.34-58.35a8,8,0,0,1,11.32,11.32Z"/></svg>
            <?php endif; ?>
            <?php echo e($trend); ?>

        </p>
    <?php elseif($hint): ?>
        <p class="text-xs text-slate-500 mt-2"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>