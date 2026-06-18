<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination" class="panel-pagination-nav">
        <span class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
            <?php if($paginator->onFirstPage()): ?>
                <span class="inline-flex items-center px-2.5 py-2 text-sm text-slate-300 bg-white border-r border-slate-200 cursor-not-allowed" aria-disabled="true">
                    <i class="ph ph-caret-left text-base"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"
                   class="inline-flex items-center px-2.5 py-2 text-sm text-slate-600 bg-white border-r border-slate-200 hover:bg-slate-50 transition-colors"
                   aria-label="Previous page">
                    <i class="ph ph-caret-left text-base"></i>
                </a>
            <?php endif; ?>

            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <span class="inline-flex items-center px-3 py-2 text-sm text-slate-500 bg-white border-r border-slate-200"><?php echo e($element); ?></span>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span aria-current="page"
                                  class="inline-flex items-center px-3.5 py-2 text-sm font-semibold text-slate-800 bg-slate-100 border-r border-slate-200">
                                <?php echo e($page); ?>

                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-600 bg-white border-r border-slate-200 hover:bg-slate-50 transition-colors"
                               aria-label="Go to page <?php echo e($page); ?>">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"
                   class="inline-flex items-center px-2.5 py-2 text-sm text-slate-600 bg-white hover:bg-slate-50 transition-colors"
                   aria-label="Next page">
                    <i class="ph ph-caret-right text-base"></i>
                </a>
            <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-2 text-sm text-slate-300 bg-white cursor-not-allowed" aria-disabled="true">
                    <i class="ph ph-caret-right text-base"></i>
                </span>
            <?php endif; ?>
        </span>
    </nav>
<?php endif; ?>
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/vendor/pagination/panel.blade.php ENDPATH**/ ?>