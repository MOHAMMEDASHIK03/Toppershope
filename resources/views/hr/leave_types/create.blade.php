@extends('hr.layouts.app')
@section('title', isset($leaveType) ? 'Edit Leave Type' : 'Create Leave Type')
@section('page_title', 'Leave Management')

@section('content')
<x-create-form-layout
    :back-href="route('hr.leave-types.index')"
    back-label="Back to leave types"
    :title="isset($leaveType) ? 'Edit leave type' : 'Create leave type'"
    subtitle="Define annual allowance and rules for a leave policy."
    :action="isset($leaveType) ? route('hr.leave-types.update', $leaveType) : route('hr.leave-types.store')"
    :method="isset($leaveType) ? 'PUT' : 'POST'"
    :submit-label="isset($leaveType) ? 'Save policy' : 'Create policy'"
    max-width="max-w-2xl"
>
    <x-form.field label="Leave policy name" name="name" :value="$leaveType->name ?? null" :required="true" placeholder="e.g. Sick Leave, Annual Privilege Leave" />

    <div>
        <label for="days_allowed" class="block text-sm font-semibold text-slate-700 mb-1.5">Annual allowance <span class="text-red-500">*</span></label>
        <div class="relative w-full md:w-1/2">
            <input type="number" id="days_allowed" name="days_allowed" value="{{ old('days_allowed', $leaveType->days_allowed ?? 0) }}" required min="0" step="1" class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-16 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">days</span>
        </div>
        <p class="text-xs text-slate-500 mt-1">Default days awarded to an employee annually.</p>
        @error('days_allowed')<p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>@enderror
    </div>

    <x-form.field label="Policy details" name="description" type="textarea" :value="$leaveType->description ?? null" rows="3" placeholder="Explain any rules regarding this leave category..." />

    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="w-5 h-5 text-primary-700 border-slate-300 rounded focus:ring-primary-500" {{ old('is_active', $leaveType->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">Active policy</label>
    </div>
</x-create-form-layout>
@endsection
