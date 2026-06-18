@extends('hr.layouts.app')
@section('title', 'Record Leave')
@section('page_title', 'Leave Management')

@section('content')
<x-create-form-layout
    :back-href="route('hr.leaves.index')"
    back-label="Back to leave requests"
    title="Submit leave request"
    subtitle="Record time off for an employee."
    :action="route('hr.leaves.store')"
    submit-label="Submit request"
>
    <div>
        <label for="employee_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Employee <span class="text-red-500">*</span></label>
        <select id="employee_id" name="employee_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <option value="">Select an employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                </option>
            @endforeach
        </select>
        @error('employee_id')<p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Leave type <span class="text-red-500">*</span></label>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($leaveTypes as $type)
                <label class="cursor-pointer">
                    <input type="radio" name="leave_type_id" value="{{ $type->id }}" class="peer sr-only" {{ old('leave_type_id') == $type->id ? 'checked' : '' }} required>
                    <div class="p-4 rounded-xl border-2 peer-checked:border-primary-600 peer-checked:bg-primary-50 border-slate-200 bg-white transition-all text-left group hover:border-primary-200">
                        <span class="block text-sm font-bold group-hover:text-primary-700 peer-checked:text-primary-700 text-slate-700 mb-1">{{ $type->name }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $type->days_allowed }} days allowed</span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('leave_type_id')<p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>@enderror
    </div>

    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-5"
        id="leave-date-fields"
        data-leave-limits='@json($leaveTypes->pluck("days_allowed", "id"))'
    >
        <x-form.field label="Start date" name="start_date" type="date" :value="old('start_date')" :required="true" />
        <div>
            <x-form.field label="End date" name="end_date" type="date" :value="old('end_date')" :required="true" />
            <p id="leave-duration-hint" class="text-xs text-slate-500 mt-1 hidden" aria-live="polite"></p>
        </div>
    </div>

    <x-form.field label="Reason / justification" name="reason" type="textarea" :value="old('reason')" :required="true" rows="4" placeholder="Please provide details regarding this absence request..." />
</x-create-form-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrap = document.getElementById('leave-date-fields');
    if (!wrap) return;

    const limits = JSON.parse(wrap.dataset.leaveLimits || '{}');
    const startInput = wrap.querySelector('[name="start_date"]');
    const endInput = wrap.querySelector('[name="end_date"]');
    const hint = document.getElementById('leave-duration-hint');
    const typeInputs = document.querySelectorAll('[name="leave_type_id"]');

    const daySpan = (n) => (n === 1 ? '1 day is' : `${n} days are`);

    const updateHint = () => {
        const typeId = document.querySelector('[name="leave_type_id"]:checked')?.value;
        const allowed = typeId ? parseInt(limits[typeId], 10) : NaN;
        const start = startInput?.value;
        const end = endInput?.value;

        if (!hint || !typeId || !start || !end || Number.isNaN(allowed)) {
            if (hint) {
                hint.classList.add('hidden');
                hint.textContent = '';
            }
            return;
        }

        const startD = new Date(start + 'T00:00:00');
        const endD = new Date(end + 'T00:00:00');
        if (endD < startD) {
            hint.classList.add('hidden');
            return;
        }

        const requested = Math.round((endD - startD) / 86400000) + 1;
        if (requested > allowed) {
            hint.textContent = `Only ${daySpan(allowed)} allowed for this leave type. You selected ${requested} day(s).`;
            hint.className = 'text-xs text-primary-600 font-semibold mt-1';
        } else {
            hint.textContent = `${requested} of ${allowed} allowed day(s) selected.`;
            hint.className = 'text-xs text-slate-500 mt-1';
        }
        hint.classList.remove('hidden');
    };

    [startInput, endInput, ...typeInputs].forEach((el) => {
        el?.addEventListener('change', updateHint);
    });
    updateHint();
});
</script>
@endpush
@endsection
