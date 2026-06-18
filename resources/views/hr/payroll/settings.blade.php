@extends('hr.layouts.app')
@section('title', 'Salary Settings')
@section('page_title', 'Payroll')

@push('styles')
<style>
    .payroll-structure-form .payroll-save-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        padding: 0.625rem 1.25rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        color: #ffffff !important;
        -webkit-text-fill-color: #ffffff !important;
        background-color: #7723D6 !important;
        background-image: none !important;
        border: 1px solid #6B21C8 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px rgb(249 115 22 / 0.25) !important;
        cursor: pointer !important;
        appearance: none !important;
    }
    .payroll-structure-form .payroll-save-btn:hover {
        background-color: #6B21C8 !important;
        color: #ffffff !important;
        -webkit-text-fill-color: #ffffff !important;
    }
    .payroll-structure-form .payroll-save-btn span,
    .payroll-structure-form .payroll-save-btn svg {
        color: #ffffff !important;
        fill: currentColor !important;
        -webkit-text-fill-color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div x-data="{ selectedEmployeeId: '{{ session('last_employee_id') ?? old('employee_id', '') }}' }">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 font-sans">Employee Salary Configuration</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Configure base salary, allowances and deductions for individual employees</p>
        </div>
    </div>

    @if($employees->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
            <p class="text-slate-400 font-medium">No active employees found. <a href="{{ route('hr.employees.create') }}" class="text-primary-700 underline font-bold">Add an employee first.</a></p>
        </div>
    @else
        {{-- Employee Selector Dropdown --}}
        <div class="mb-6 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm max-w-md">
            <label class="block text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Select Employee to Configure</label>
            <div class="relative">
                <select x-model="selectedEmployeeId" class="admin-input rounded-xl font-semibold appearance-none cursor-pointer">
                    <option value="">-- Choose an Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80a8,8,0,0,1,11.32-11.32L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"/></svg>
                </div>
            </div>
        </div>

        {{-- Configuration Forms Container --}}
        <div class="space-y-5">
            {{-- Empty State when no employee is selected --}}
            <div x-show="!selectedEmployeeId" x-transition.opacity class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center select-none">
                <div class="w-16 h-16 rounded-full bg-primary-50 border border-primary-100 flex items-center justify-center mx-auto mb-4 text-primary-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 256 256"><path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Zm112-32A112,112,0,1,1,128,16,112.12,112.12,0,0,1,240,128Zm-16,0a96,96,0,1,0-96,96A96.11,96.11,0,0,0,224,128Z"/></svg>
                </div>
                <h3 class="font-bold text-slate-700 text-base">No Employee Selected</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto font-medium">Please choose an active employee from the selector list above to customize their salary structure.</p>
            </div>

            @foreach($employees as $emp)
            @php $str = $structures[$emp->id] ?? new \App\Models\HR\SalaryStructure(); @endphp
            <div x-show="selectedEmployeeId == '{{ $emp->id }}'" x-transition class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <form action="{{ route('hr.payroll.settings.update') }}" method="POST" class="payroll-structure-form">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">

                    {{-- Card header --}}
                    <div class="px-5 sm:px-6 py-4 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white text-sm font-bold shrink-0 shadow-sm">
                                {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900 truncate">{{ $emp->first_name }} {{ $emp->last_name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 truncate">
                                    {{ $emp->employee_id }}
                                    @if($emp->designation?->name) • {{ $emp->designation->name }} @endif
                                    @if($emp->department?->name) • {{ $emp->department->name }} @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Fields --}}
                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">

                        {{-- Base Pay --}}
                        <div>
                            <label class="block text-xs font-black tracking-widest uppercase text-slate-400 mb-3 select-none">Base Pay</label>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Basic Salary (₹) <span class="text-primary-500">*</span></label>
                                <input type="number" name="basic_salary" value="{{ old('basic_salary', $str->basic_salary) }}" required min="0" step="0.01"
                                       class="admin-input font-semibold text-slate-900">
                            </div>
                        </div>

                        {{-- Allowances --}}
                        <div class="border-t lg:border-t-0 lg:border-l border-slate-100 lg:pl-8 pt-6 lg:pt-0">
                            <label class="block text-xs font-black tracking-widest uppercase text-primary-600 mb-3 select-none">Allowances (+)</label>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3 items-center">
                                    <label class="text-sm font-bold text-slate-600">HRA</label>
                                    <input type="number" name="hra" value="{{ old('hra', $str->hra) }}" min="0" step="0.01"
                                           class="admin-input font-semibold text-slate-900 bg-slate-50">
                                </div>
                                <div class="grid grid-cols-2 gap-3 items-center">
                                    <label class="text-sm font-bold text-slate-600">Other Allowances</label>
                                    <input type="number" name="other_allowances" value="{{ old('other_allowances', $str->other_allowances) }}" min="0" step="0.01"
                                           class="admin-input font-semibold text-slate-900 bg-slate-50">
                                </div>
                            </div>
                        </div>

                        {{-- Deductions --}}
                        <div class="border-t lg:border-t-0 lg:border-l border-slate-100 lg:pl-8 pt-6 lg:pt-0">
                            <label class="block text-xs font-black tracking-widest uppercase text-primary-500 mb-3 select-none">Deductions (-)</label>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3 items-center">
                                    <label class="text-sm font-bold text-slate-600">TDS / Tax</label>
                                    <input type="number" name="tax_deductions" value="{{ old('tax_deductions', $str->tax_deductions) }}" min="0" step="0.01"
                                           class="admin-input font-semibold text-slate-900 bg-slate-50">
                                </div>
                                <div class="grid grid-cols-2 gap-3 items-center">
                                    <label class="text-sm font-bold text-slate-600">Other Deductions</label>
                                    <input type="number" name="other_deductions" value="{{ old('other_deductions', $str->other_deductions) }}" min="0" step="0.01"
                                           class="admin-input font-semibold text-slate-900 bg-slate-50">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer save (mobile-friendly) --}}
                    <div class="px-5 sm:px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-end gap-3">
                        <p class="text-xs text-slate-500 sm:mr-auto">Changes apply to this employee&rsquo;s monthly payroll calculations.</p>
                        <button
                            type="submit"
                            class="payroll-save-btn w-full sm:w-auto"
                            style="background-color:#7723D6;color:#ffffff;border:1px solid #6B21C8;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M219.31,72,184,36.69A15.86,15.86,0,0,0,172.69,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V83.31A15.86,15.86,0,0,0,219.31,72ZM168,208H88V152h80Zm40,0H184V152a16,16,0,0,0-16-16H88a16,16,0,0,0-16,16v56H48V48H172.69L208,83.31Z"/></svg>
                            <span>Save structure</span>
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
