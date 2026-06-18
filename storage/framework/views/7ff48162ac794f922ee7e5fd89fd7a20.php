<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator',
    'class' => '',
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
    'paginator',
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && \App\Support\ListingPagination::shouldShow($paginator)): ?>
    <?php
        $perPageOptions = \App\Support\ListingPagination::PER_PAGE_OPTIONS;
        $perPageFieldId = 'panel-per-page-' . $paginator->getPageName();
        $currentPerPage = (int) $paginator->perPage();
    ?>
    <div <?php echo e($attributes->merge(['class' => 'panel-pagination px-4 sm:px-5 py-4 border-t border-slate-100 bg-white ' . $class])); ?>>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">
                Showing
                <span class="font-semibold text-slate-800"><?php echo e($paginator->firstItem() ?? 0); ?></span>
                to
                <span class="font-semibold text-slate-800"><?php echo e($paginator->lastItem() ?? 0); ?></span>
                of
                <span class="font-semibold text-slate-800"><?php echo e($paginator->total()); ?></span>
                results
            </p>

            <div class="flex flex-wrap items-center gap-3 sm:justify-end">
                <div x-data="panelPerPagePicker()" class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Per page</span>

                    <div class="relative">
                        <button type="button"
                                x-ref="trigger"
                                @click="toggle()"
                                :aria-expanded="open"
                                aria-haspopup="listbox"
                                class="panel-pagination-per-page-trigger">
                            <span><?php echo e($currentPerPage); ?></span>
                            <i class="ph ph-caret-down text-base transition-transform duration-150"
                               :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open"
                             x-cloak
                             x-ref="menu"
                             @click.outside="close()"
                             :style="menuStyle"
                             class="panel-pagination-per-page-menu"
                             role="listbox">
                            <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(\App\Support\ListingPagination::perPageChangeUrl(request(), $size)); ?>"
                                   role="option"
                                   id="<?php echo e($perPageFieldId); ?>-<?php echo e($size); ?>"
                                   class="panel-pagination-per-page-option <?php echo e($currentPerPage === $size ? 'is-active' : ''); ?>"
                                   @click="close()">
                                    <?php echo e($size); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <?php echo e($paginator->withQueryString()->links('vendor.pagination.panel')); ?>

            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/pagination.blade.php ENDPATH**/ ?>