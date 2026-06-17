@extends('hr.layouts.app')
@section('title', 'Generate Payroll')
@section('page_title', 'Payroll')

@section('content')
<x-create-form-layout
    :back-href="route('hr.payroll.index')"
    back-label="Back to payroll"
    title="Generate payroll"
    subtitle="Process salary for a specific employee and period."
    :action="route('hr.payroll.store')"
    submit-label="Generate payslip"
    max-width="max-w-xl"
>
    <div>
        <label for="employee_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Employee <span class="text-red-500">*</span></label>
        <select id="employee_id" name="employee_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <option value="">Choose an employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                </option>
            @endforeach
        </select>
        <p class="text-xs text-slate-500 mt-1">Only employees with a configured salary structure are listed.</p>
        @error('employee_id')<p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="monthPicker" class="block text-sm font-semibold text-slate-700 mb-1.5">Payroll period <span class="text-red-500">*</span></label>
        <input type="month" id="monthPicker" onchange="syncMonthYear(this)"
               value="{{ \Carbon\Carbon::createFromFormat('m-Y', $defaultMonthYear)->format('Y-m') }}"
               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
        <input type="hidden" name="month_year" id="monthYear" value="{{ $defaultMonthYear }}">
    </div>

    <div class="bg-orange-50 border border-indigo-100 rounded-xl p-4 text-sm text-indigo-800">
        Ensure the employee has a salary structure in <a href="{{ route('hr.payroll.settings') }}" class="font-semibold underline">Payroll settings</a> before generating.
    </div>
</x-create-form-layout>

<script>
function syncMonthYear(el) {
    const [year, month] = el.value.split('-');
    document.getElementById('monthYear').value = month + '-' + year;
}
</script>
@endsection
