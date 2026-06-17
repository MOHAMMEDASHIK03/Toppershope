@props([
    'departmentId' => null,
    'designationId' => null,
    'departmentName' => 'department_id',
    'designationName' => 'designation_id',
    'departments' => null,
    'wrapperClass' => 'contents',
    'labelClass' => 'block text-sm font-bold text-slate-700 mb-1.5',
    'required' => true,
])

@php
    use App\Models\HR\Department;

    $departments = $departments ?? Department::query()
        ->where('is_active', true)
        ->with(['designations' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
        ->orderBy('name')
        ->get();

    $departmentPayload = $departments->map(fn ($dept) => [
        'id' => $dept->id,
        'name' => $dept->name,
        'designations' => $dept->designations->map(fn ($des) => [
            'id' => $des->id,
            'name' => $des->name,
        ])->values()->all(),
    ])->values()->all();

    $fieldId = 'dept-desig-' . substr(md5(serialize([$departmentName, $designationName, $departmentId, $designationId])), 0, 8);
@endphp

<div
    id="{{ $fieldId }}"
    class="dept-designation-fields {{ $wrapperClass }}"
    data-dept-designation-fields
    data-initial-department="{{ $departmentId }}"
    data-initial-designation="{{ $designationId }}"
>
    <script type="application/json" class="dd-departments-json">@json($departmentPayload)</script>

    <div class="dd-field">
        <label class="{{ $labelClass }}" for="{{ $fieldId }}-department">
            Department @if($required)<span class="text-rose-500">*</span>@endif
        </label>
        <select
            id="{{ $fieldId }}-department"
            name="{{ $departmentName }}"
            @if($required) required @endif
            class="dd-department-select w-full"
        >
            <option value="">Select Department</option>
            @foreach($departmentPayload as $dept)
                <option value="{{ $dept['id'] }}" @selected((string) $departmentId === (string) $dept['id'])>{{ $dept['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="dd-field">
        <label class="{{ $labelClass }}" for="{{ $fieldId }}-designation">
            Designation @if($required)<span class="text-rose-500">*</span>@endif
        </label>
        <select
            id="{{ $fieldId }}-designation"
            name="{{ $designationName }}"
            @if($required) required @endif
            class="dd-designation-select w-full"
            @if(! $departmentId) disabled @endif
        >
            <option value="">Select Designation</option>
        </select>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    function parseDepartments(root) {
        const jsonEl = root.querySelector('.dd-departments-json');
        if (!jsonEl) {
            return [];
        }
        try {
            return JSON.parse(jsonEl.textContent || '[]');
        } catch (e) {
            console.error('Department/designation fields: invalid JSON', e);
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

    function setDesignationEnabled(desigSelect, enabled) {
        if (!desigSelect) {
            return;
        }
        if (enabled) {
            desigSelect.removeAttribute('disabled');
        } else {
            desigSelect.setAttribute('disabled', 'disabled');
        }
    }

    function fillDesignations(root, departmentId, selectedDesigId) {
        const desigSelect = root.querySelector('.dd-designation-select');
        if (!desigSelect) {
            return;
        }

        const departments = parseDepartments(root);
        const dept = departments.find(function (d) {
            return String(d.id) === String(departmentId);
        });
        const designations = dept && dept.designations ? dept.designations : [];

        desigSelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = designations.length ? 'Select Designation' : 'No designations for this department';
        desigSelect.appendChild(placeholder);

        designations.forEach(function (des) {
            const option = document.createElement('option');
            option.value = String(des.id);
            option.textContent = des.name;
            if (selectedDesigId && String(des.id) === String(selectedDesigId)) {
                option.selected = true;
            }
            desigSelect.appendChild(option);
        });

        setDesignationEnabled(desigSelect, Boolean(departmentId) && designations.length > 0);
        refreshThSelect(desigSelect);
    }

    function bootDeptDesignationFields(root) {
        if (!root || !root.isConnected || root.dataset.ddInitialized === '1') {
            return;
        }
        root.dataset.ddInitialized = '1';

        const deptSelect = root.querySelector('.dd-department-select');
        if (!deptSelect) {
            return;
        }

        const initialDepartment = root.dataset.initialDepartment || '';
        const initialDesignation = root.dataset.initialDesignation || '';

        if (!deptSelect.dataset.ddChangeBound) {
            deptSelect.dataset.ddChangeBound = '1';
            deptSelect.addEventListener('change', function () {
                fillDesignations(root, deptSelect.value, '');
                refreshThSelect(deptSelect);
            });
        }

        const startDepartment = deptSelect.value || initialDepartment;
        if (startDepartment && !deptSelect.value) {
            deptSelect.value = startDepartment;
        }
        fillDesignations(root, deptSelect.value || startDepartment, initialDesignation);

        initThSelectInRoot(root);
        refreshThSelect(deptSelect);
    }

    function scanDeptDesignationFields() {
        document.querySelectorAll('[data-dept-designation-fields]').forEach(bootDeptDesignationFields);
    }

    window.refreshDeptDesignationThSelect = function () {
        document.querySelectorAll('[data-dept-designation-fields]').forEach(function (root) {
            initThSelectInRoot(root);
            refreshThSelect(root.querySelector('.dd-department-select'));
            refreshThSelect(root.querySelector('.dd-designation-select'));
        });
    };

    window.initDeptDesignationFields = function () {
        document.querySelectorAll('[data-dept-designation-fields]').forEach(function (root) {
            delete root.dataset.ddInitialized;
        });
        scanDeptDesignationFields();
    };

    function runWhenReady() {
        scanDeptDesignationFields();
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
