<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'categoryId' => null,
    'subcategoryId' => null,
    'categoryName' => 'category_id',
    'subcategoryName' => 'subcategory_id',
    'subcategoryRequired' => false,
    'showSubcategory' => true,
    'showLabels' => true,
    'layout' => 'grid',
    'categories' => null,
    'labelClass' => 'block text-sm font-semibold text-slate-700 mb-1.5',
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
    'categoryId' => null,
    'subcategoryId' => null,
    'categoryName' => 'category_id',
    'subcategoryName' => 'subcategory_id',
    'subcategoryRequired' => false,
    'showSubcategory' => true,
    'showLabels' => true,
    'layout' => 'grid',
    'categories' => null,
    'labelClass' => 'block text-sm font-semibold text-slate-700 mb-1.5',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use App\Services\CategoryService;

    $categoryPayload = isset($categories) && $categories
        ? $categories->map(fn ($cat) => [
            'id' => $cat->id,
            'name' => $cat->name,
            'slug' => $cat->slug,
            'subcategories' => $cat->activeSubcategories->map(fn ($sub) => [
                'id' => $sub->id,
                'name' => $sub->name,
                'slug' => $sub->slug,
            ])->values()->all(),
        ])->values()->all()
        : app(CategoryService::class)->apiPayload();

    $fieldId = 'category-fields-' . substr(md5(serialize([$categoryName, $subcategoryName, $categoryId, $subcategoryId])), 0, 8);
?>

<div
    id="<?php echo e($fieldId); ?>"
    class="category-fields-root <?php echo e($layout === 'stacked' ? 'space-y-4' : 'grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5'); ?>"
    data-category-fields
    data-initial-category="<?php echo e($categoryId); ?>"
    data-initial-subcategory="<?php echo e($subcategoryId); ?>"
>
    <script type="application/json" class="cf-categories-json"><?php echo json_encode($categoryPayload, 15, 512) ?></script>

    <div class="cf-field">
        <?php if($showLabels): ?>
        <label class="<?php echo e($labelClass); ?>" for="<?php echo e($fieldId); ?>-category">Category <span class="text-red-500">*</span></label>
        <?php endif; ?>
        <select
            id="<?php echo e($fieldId); ?>-category"
            name="<?php echo e($categoryName); ?>"
            required
            class="cf-category-select w-full"
        >
            <option value="">Select category</option>
            <?php $__currentLoopData = $categoryPayload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat['id']); ?>" <?php if((string) $categoryId === (string) $cat['id']): echo 'selected'; endif; ?>><?php echo e($cat['name']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <?php if($showSubcategory): ?>
    <div class="cf-field">
        <?php if($showLabels): ?>
        <label class="<?php echo e($labelClass); ?>" for="<?php echo e($fieldId); ?>-subcategory">
            Subcategory <?php if($subcategoryRequired): ?><span class="text-red-500">*</span><?php endif; ?>
        </label>
        <?php endif; ?>
        <select
            id="<?php echo e($fieldId); ?>-subcategory"
            name="<?php echo e($subcategoryName); ?>"
            <?php if($subcategoryRequired): ?> required <?php endif; ?>
            class="cf-subcategory-select w-full"
            <?php if(! $categoryId): ?> disabled <?php endif; ?>
        >
            <option value="">Select subcategory</option>
        </select>
    </div>
    <?php endif; ?>
</div>

<?php if (! $__env->hasRenderedOnce('9c34a6d3-140e-4171-8765-765c1aa82651')): $__env->markAsRenderedOnce('9c34a6d3-140e-4171-8765-765c1aa82651'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    function parseCategories(root) {
        const jsonEl = root.querySelector('.cf-categories-json');
        if (!jsonEl) {
            return [];
        }
        try {
            return JSON.parse(jsonEl.textContent || '[]');
        } catch (e) {
            console.error('Category fields: invalid JSON', e);
            return [];
        }
    }

    function refreshThSelect(select) {
        if (!select) {
            return;
        }
        document.dispatchEvent(new CustomEvent('th-select:refresh', { detail: { select: select } }));
    }

    function initThSelectInRoot(root) {
        document.dispatchEvent(new CustomEvent('th-select:scan', { detail: { root: root } }));
    }

    function setSubcategoryEnabled(subSelect, enabled) {
        if (!subSelect) {
            return;
        }
        if (enabled) {
            subSelect.removeAttribute('disabled');
        } else {
            subSelect.setAttribute('disabled', 'disabled');
        }
    }

    function fillSubcategories(root, categoryId, selectedSubId) {
        const subSelect = root.querySelector('.cf-subcategory-select');
        if (!subSelect) {
            return;
        }

        const categories = parseCategories(root);
        const cat = categories.find(function (c) {
            return String(c.id) === String(categoryId);
        });
        const subs = cat && cat.subcategories ? cat.subcategories : [];

        subSelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = subs.length ? 'Select subcategory' : 'No subcategories for this category';
        subSelect.appendChild(placeholder);

        subs.forEach(function (sub) {
            const option = document.createElement('option');
            option.value = String(sub.id);
            option.textContent = sub.name;
            if (selectedSubId && String(sub.id) === String(selectedSubId)) {
                option.selected = true;
            }
            subSelect.appendChild(option);
        });

        setSubcategoryEnabled(subSelect, Boolean(categoryId) && subs.length > 0);
        refreshThSelect(subSelect);
    }

    function bootCategoryFields(root) {
        if (!root || !root.isConnected || root.dataset.cfInitialized === '1') {
            return;
        }
        root.dataset.cfInitialized = '1';

        const catSelect = root.querySelector('.cf-category-select');
        if (!catSelect) {
            return;
        }

        const initialCategory = root.dataset.initialCategory || '';
        const initialSubcategory = root.dataset.initialSubcategory || '';

        if (!catSelect.dataset.cfChangeBound) {
            catSelect.dataset.cfChangeBound = '1';
            catSelect.addEventListener('change', function () {
                fillSubcategories(root, catSelect.value, '');
                refreshThSelect(catSelect);
            });
        }

        const courseSelect = document.getElementById('course_id');
        if (courseSelect && !courseSelect.dataset.cfCourseBound) {
            courseSelect.dataset.cfCourseBound = '1';
            courseSelect.addEventListener('change', function () {
                const option = courseSelect.options[courseSelect.selectedIndex];
                if (!option) {
                    return;
                }
                const catId = option.getAttribute('data-category-id');
                const subId = option.getAttribute('data-subcategory-id');
                if (catId) {
                    catSelect.value = catId;
                    fillSubcategories(root, catId, subId || '');
                    refreshThSelect(catSelect);
                }
            });
        }

        const startCategory = catSelect.value || initialCategory;
        if (startCategory && !catSelect.value) {
            catSelect.value = startCategory;
        }
        fillSubcategories(root, catSelect.value || startCategory, initialSubcategory);

        initThSelectInRoot(root);
        refreshThSelect(catSelect);
    }

    function scanCategoryFields() {
        document.querySelectorAll('[data-category-fields]').forEach(bootCategoryFields);
    }

    window.refreshCategoryFieldsThSelect = function () {
        document.querySelectorAll('[data-category-fields]').forEach(function (root) {
            initThSelectInRoot(root);
            refreshThSelect(root.querySelector('.cf-category-select'));
            refreshThSelect(root.querySelector('.cf-subcategory-select'));
        });
    };

    window.initCategoryFields = function () {
        document.querySelectorAll('[data-category-fields]').forEach(function (root) {
            delete root.dataset.cfInitialized;
        });
        scanCategoryFields();
    };

    function runWhenReady() {
        scanCategoryFields();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runWhenReady);
    } else {
        runWhenReady();
    }

    document.addEventListener('alpine:initialized', runWhenReady);
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/category-fields.blade.php ENDPATH**/ ?>