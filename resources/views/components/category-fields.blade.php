@props([
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
])

@php
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
@endphp

<div
    id="{{ $fieldId }}"
    class="category-fields-root {{ $layout === 'stacked' ? 'space-y-4' : 'grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5' }}"
    data-category-fields
    data-initial-category="{{ $categoryId }}"
    data-initial-subcategory="{{ $subcategoryId }}"
>
    <script type="application/json" class="cf-categories-json">@json($categoryPayload)</script>

    <div class="cf-field">
        @if($showLabels)
        <label class="{{ $labelClass }}" for="{{ $fieldId }}-category">Category <span class="text-red-500">*</span></label>
        @endif
        <select
            id="{{ $fieldId }}-category"
            name="{{ $categoryName }}"
            required
            class="cf-category-select w-full"
        >
            <option value="">Select category</option>
            @foreach($categoryPayload as $cat)
                <option value="{{ $cat['id'] }}" @selected((string) $categoryId === (string) $cat['id'])>{{ $cat['name'] }}</option>
            @endforeach
        </select>
    </div>

    @if($showSubcategory)
    <div class="cf-field">
        @if($showLabels)
        <label class="{{ $labelClass }}" for="{{ $fieldId }}-subcategory">
            Subcategory @if($subcategoryRequired)<span class="text-red-500">*</span>@endif
        </label>
        @endif
        <select
            id="{{ $fieldId }}-subcategory"
            name="{{ $subcategoryName }}"
            @if($subcategoryRequired) required @endif
            class="cf-subcategory-select w-full"
            @if(! $categoryId) disabled @endif
        >
            <option value="">Select subcategory</option>
        </select>
    </div>
    @endif
</div>

@once
@push('scripts')
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
@endpush
@endonce
