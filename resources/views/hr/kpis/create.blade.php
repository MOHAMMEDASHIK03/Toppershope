@extends('hr.layouts.app')
@section('title', isset($kpi) ? 'Edit KPI' : 'Create KPI')
@section('page_title', 'Performance')

@section('content')
@php
    $departments = \App\Models\HR\Department::orderBy('name')->get();
    $designations = \App\Models\HR\Designation::orderBy('name')->get();
@endphp

<x-create-form-layout
    :back-href="route('hr.kpis.index')"
    back-label="Back to KPIs"
    :title="isset($kpi) ? 'Edit KPI' : 'Create KPI'"
    subtitle="Set measurable values for evaluating performance."
    :action="isset($kpi) ? route('hr.kpis.update', $kpi) : route('hr.kpis.store')"
    :method="isset($kpi) ? 'PUT' : 'POST'"
    :submit-label="isset($kpi) ? 'Save changes' : 'Create KPI'"
    max-width="max-w-2xl"
    :delete-action="isset($kpi) ? route('hr.kpis.destroy', $kpi) : null"
    delete-label="Delete indicator"
    delete-confirm="Delete this KPI? All linked performance reviews will also be removed."
>
    <x-form.field label="Indicator title" name="title" :value="$kpi->title ?? null" :required="true" placeholder="e.g. Sales Target Achievement" />
    <x-form.field label="Description & evaluation criteria" name="description" type="textarea" :value="$kpi->description ?? null" :required="true" rows="4" placeholder="Detail how this indicator is measured..." />

    <div class="pt-4 border-t border-slate-100 space-y-4">
        <div>
            <p class="text-xs font-bold text-primary-700 uppercase tracking-wide">Target scope (optional)</p>
            <p class="text-xs text-slate-500 mt-1">Leave blank to apply company-wide.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-form.field label="Target department" name="department_id" type="select">
                <option value="">All departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $kpi->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </x-form.field>

            <x-form.field label="Target designation" name="designation_id" type="select">
                <option value="">All designations</option>
                @foreach($designations as $desig)
                    <option value="{{ $desig->id }}" {{ old('designation_id', $kpi->designation_id ?? '') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                @endforeach
            </x-form.field>
        </div>
    </div>
</x-create-form-layout>
@endsection
