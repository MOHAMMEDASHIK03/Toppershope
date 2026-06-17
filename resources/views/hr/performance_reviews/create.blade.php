@extends('hr.layouts.app')
@section('title', isset($performanceReview) ? 'Review Details' : 'Submit Review')
@section('page_title', 'Performance')

@push('styles')
<style>
    .perf-section {
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
        padding: 1.25rem 1.5rem;
    }
    @media (min-width: 640px) {
        .perf-section { padding: 1.5rem 1.75rem; }
    }
    .perf-section__head {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .perf-section__icon {
        flex-shrink: 0;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ffedd5;
    }
    .perf-summary {
        display: grid;
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .perf-summary { grid-template-columns: repeat(3, 1fr); }
    }
    .perf-summary__item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
    }
    .perf-summary__label {
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }
    .perf-summary__value {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
    }
    .perf-period-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .perf-period-chip {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .perf-period-chip:hover {
        border-color: #fdba74;
        color: #ea580c;
        background: #fff7ed;
    }
    .perf-rating__track {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }
    .perf-rating__option {
        position: relative;
        cursor: pointer;
        text-align: center;
    }
    .perf-rating__option input[type="radio"] {
        position: absolute;
        inset: 0;
        z-index: 2;
        width: 100%;
        height: 100%;
        margin: 0;
        opacity: 0;
        cursor: pointer;
    }
    .perf-rating__num {
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        height: 3rem;
        font-size: 1.25rem;
        font-weight: 800;
        color: #94a3b8;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        transition: all 0.15s ease;
    }
    .perf-rating__option:hover .perf-rating__num {
        background: #fff7ed;
        border-color: #fed7aa;
        color: #ea580c;
    }
    .perf-rating__option input:checked + .perf-rating__num {
        color: #fff;
        background: #f97316;
        border-color: #ea580c;
        box-shadow: 0 4px 14px rgb(249 115 22 / 0.35);
    }
    .perf-status-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .perf-status-grid { grid-template-columns: repeat(3, 1fr); }
    }
    .perf-status-card {
        position: relative;
        cursor: pointer;
        display: block;
    }
    .perf-status-card__input {
        position: absolute;
        inset: 0;
        z-index: 2;
        width: 100%;
        height: 100%;
        margin: 0;
        opacity: 0;
        cursor: pointer;
    }
    .perf-status-card__body {
        position: relative;
        height: 100%;
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        border: 2px solid #e2e8f0;
        background: #fff;
        transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
        pointer-events: none;
    }
    .perf-status-card:hover .perf-status-card__body {
        border-color: #cbd5e1;
    }
    .perf-status-card__check {
        display: none;
        align-items: center;
        gap: 0.25rem;
        position: absolute;
        top: 0.625rem;
        right: 0.625rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .perf-status-card__input:checked + .perf-status-card__body .perf-status-card__check {
        display: inline-flex;
    }
    .perf-status-card__input:checked + .perf-status-card__body--draft {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 0 0 3px rgb(245 158 11 / 0.2);
    }
    .perf-status-card__input:checked + .perf-status-card__body--draft .perf-status-card__check {
        color: #b45309;
    }
    .perf-status-card__input:checked + .perf-status-card__body--published {
        border-color: #10b981;
        background: #ecfdf5;
        box-shadow: 0 0 0 3px rgb(16 185 129 / 0.2);
    }
    .perf-status-card__input:checked + .perf-status-card__body--published .perf-status-card__check {
        color: #047857;
    }
    .perf-status-card__input:checked + .perf-status-card__body--acknowledged {
        border-color: #f97316;
        background: #fff7ed;
        box-shadow: 0 0 0 3px rgb(249 115 22 / 0.2);
    }
    .perf-status-card__input:checked + .perf-status-card__body--acknowledged .perf-status-card__check {
        color: #c2410c;
    }
    .perf-rating__labels {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94a3b8;
    }
    .perf-rating__hint {
        flex: 1;
        text-align: center;
        text-transform: none;
        letter-spacing: 0;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #ea580c;
    }
</style>
@endpush

@section('content')
@php
    $isEdit = isset($performanceReview);
    $defaultPeriod = 'Q' . ceil(date('n') / 3) . ' ' . date('Y');
    $year = (int) date('Y');
    $quarter = (int) ceil(date('n') / 3);
    $periodPresets = [];
    for ($q = 1; $q <= 4; $q++) {
        $periodPresets[] = "Q{$q} {$year}";
    }
    if ($quarter > 1) {
        $periodPresets[] = 'Q' . ($quarter - 1) . ' ' . $year;
    }
    $periodPresets = array_unique($periodPresets);
@endphp

<x-create-form-layout
    :back-href="route('hr.performance-reviews.index')"
    back-label="Back to performance reviews"
    :title="$isEdit ? 'Edit performance review' : 'Create performance review'"
    subtitle="Structured evaluation linked to your KPI framework."
    :action="$isEdit ? route('hr.performance-reviews.update', $performanceReview) : route('hr.performance-reviews.store')"
    :method="$isEdit ? 'PUT' : 'POST'"
    :submit-label="$isEdit ? 'Save review' : 'Publish review'"
    max-width="max-w-4xl"
    :delete-action="$isEdit ? route('hr.performance-reviews.destroy', $performanceReview) : null"
    delete-label="Delete review"
    delete-confirm="Delete this performance review permanently?"
>
    <div class="space-y-6">
        {{-- Context --}}
        <div class="perf-section">
            <div class="perf-section__head">
                <div class="perf-section__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M128,20A108,108,0,1,0,236,128,108.12,108.12,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09,84.09,0,0,1,128,212ZM128,76a28,28,0,0,0-28,28,8,8,0,0,0,16,0,12,12,0,0,1,24,0,40,40,0,0,0-40,40,8,8,0,0,0,0,16,56,56,0,0,1,56-56A8,8,0,0,0,128,76Z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Review context</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Who is being evaluated, which KPI applies, and the time period.</p>
                </div>
            </div>

            @if(!$isEdit)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form.field label="Employee" name="employee_id" type="select" :required="true" class="md:col-span-2">
                        <option value="">Choose an employee…</option>
                        @foreach($employees as $emp)
                            @php
                                $role = $emp->designation_display ?? $emp->designation?->name;
                                $dept = $emp->department_display ?? $emp->department?->name;
                                $meta = collect([$emp->employee_id, $dept, $role])->filter()->implode(' · ');
                            @endphp
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}@if($meta) — {{ $meta }}@endif
                            </option>
                        @endforeach
                    </x-form.field>

                    <x-form.field label="Target KPI" name="kpi_id" type="select" :required="true">
                        <option value="">Select KPI framework…</option>
                        @foreach($kpis as $kpi)
                            <option value="{{ $kpi->id }}" {{ old('kpi_id') == $kpi->id ? 'selected' : '' }}>{{ $kpi->title }}</option>
                        @endforeach
                    </x-form.field>

                    <div>
                        <x-form.field
                            label="Evaluation period"
                            name="review_period"
                            :value="old('review_period', $defaultPeriod)"
                            :required="true"
                            placeholder="e.g. Q2 2026"
                            id="review_period"
                        />
                    </div>
                </div>
            @else
                <div class="perf-summary mb-5">
                    <div class="perf-summary__item">
                        <p class="perf-summary__label">Employee</p>
                        <p class="perf-summary__value">{{ $performanceReview->employee->first_name }} {{ $performanceReview->employee->last_name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $performanceReview->employee->employee_id }}</p>
                    </div>
                    <div class="perf-summary__item">
                        <p class="perf-summary__label">Period</p>
                        <p class="perf-summary__value">{{ $performanceReview->review_period }}</p>
                    </div>
                    <div class="perf-summary__item">
                        <p class="perf-summary__label">KPI framework</p>
                        <p class="perf-summary__value">{{ $performanceReview->kpi->title ?? '—' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold text-slate-700 mb-2">Record visibility</p>
                    <x-performance.status-picker :value="$performanceReview->status" />
                </div>
            @endif
        </div>

        {{-- Evaluation --}}
        <div class="perf-section">
            <div class="perf-section__head">
                <div class="perf-section__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm40-68a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,148Zm-8-40a8,8,0,0,1-8,8H104a8,8,0,0,1,0-16h48A8,8,0,0,1,160,108Z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Score & feedback</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Rate performance and document manager observations.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Performance rating <span class="text-red-500">*</span>
                    </label>
                    <x-performance.rating-scale
                        :value="old('rating', $performanceReview->rating ?? 4)"
                    />
                    @error('rating')
                        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <x-form.field
                    label="Manager feedback & justification"
                    name="manager_feedback"
                    type="textarea"
                    :value="old('manager_feedback', $performanceReview->manager_feedback ?? '')"
                    :required="true"
                    rows="5"
                    placeholder="Summarize achievements, measurable outcomes, and areas for development…"
                    hint="Be specific and reference KPI criteria where possible."
                />

                @if($isEdit)
                    <x-form.field
                        label="Employee comments"
                        name="employee_feedback"
                        type="textarea"
                        :value="old('employee_feedback', $performanceReview->employee_feedback)"
                        rows="3"
                        placeholder="Optional notes from the employee's self-review or acknowledgment…"
                    />
                @endif
            </div>
        </div>
    </div>
</x-create-form-layout>

@push('scripts')
<script>
    document.querySelectorAll('[data-period-presets]').forEach((wrap) => {
        const input = document.getElementById('review_period');
        if (!input) return;
        wrap.querySelectorAll('[data-period-value]').forEach((btn) => {
            btn.addEventListener('click', () => {
                input.value = btn.dataset.periodValue;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                wrap.querySelectorAll('.perf-period-chip').forEach((c) => c.classList.remove('is-active'));
                btn.classList.add('is-active');
            });
        });
    });
</script>
<style>
    .perf-period-chip.is-active {
        color: #fff;
        background: #f97316;
        border-color: #f97316;
    }
</style>
@endpush
@endsection
