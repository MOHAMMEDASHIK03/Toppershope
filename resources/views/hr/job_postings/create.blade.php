@extends('hr.layouts.app')
@section('title', isset($jobPosting) ? 'Edit Posting' : 'Create Job Posting')
@section('page_title', 'Recruitment')

@section('content')
@php
    $isEdit = isset($jobPosting);
    $departments = \App\Models\HR\Department::orderBy('name')->get();
@endphp

@if ($errors->any())
    <div class="max-w-4xl mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
        <p class="font-bold mb-2">Please fix the following:</p>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-create-form-layout
    :back-href="route('hr.job-postings.index')"
    back-label="Back to job postings"
    :title="$isEdit ? 'Edit job posting' : 'Create job posting'"
    subtitle="Publish a vacancy to the careers portal."
    :action="$isEdit ? route('hr.job-postings.update', $jobPosting) : route('hr.job-postings.store')"
    :method="$isEdit ? 'PUT' : 'POST'"
    :submit-label="$isEdit ? 'Save posting' : 'Publish job'"
    max-width="max-w-4xl"
    :delete-action="$isEdit ? route('hr.job-postings.destroy', $jobPosting) : null"
    delete-label="Delete job posting"
    delete-confirm="Delete this job posting? Applications linked to it may also be removed."
>
    <div class="space-y-8">
        <div>
            <h3 class="text-xs font-bold text-orange-600 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Basic details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.field
                    label="Job title"
                    name="title"
                    :value="$jobPosting->title ?? null"
                    :required="true"
                    placeholder="e.g. Senior Backend Engineer"
                    class="md:col-span-2"
                />

                <x-form.field label="Department" name="department_id" type="select" :required="true">
                    <option value="">Select department…</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $jobPosting->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </x-form.field>

                <x-form.field label="Employment type" name="employment_type" type="select" :required="true">
                    <option value="full-time" {{ old('employment_type', $jobPosting->employment_type ?? '') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ old('employment_type', $jobPosting->employment_type ?? '') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ old('employment_type', $jobPosting->employment_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ old('employment_type', $jobPosting->employment_type ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
                </x-form.field>

                <x-form.field
                    label="Location"
                    name="location"
                    :value="old('location', $jobPosting->location ?? 'Head Office')"
                    :required="true"
                    placeholder="e.g. Remote, Chennai HQ"
                />

                <x-form.field
                    label="Salary range"
                    name="salary_range"
                    :value="$jobPosting->salary_range ?? null"
                    placeholder="e.g. ₹50,000 – ₹80,000 / month"
                    hint="Optional — shown on the careers page if provided."
                />
            </div>
        </div>

        <div>
            <h3 class="text-xs font-bold text-orange-600 uppercase tracking-wide mb-4 pb-2 border-b border-slate-100">Role information</h3>
            <div class="space-y-5">
                <x-form.field
                    label="Job description"
                    name="description"
                    type="textarea"
                    :value="$jobPosting->description ?? null"
                    :required="true"
                    rows="6"
                    placeholder="Detail responsibilities and day-to-day tasks…"
                />

                <x-form.field
                    label="Requirements & qualifications"
                    name="requirements"
                    type="textarea"
                    :value="$jobPosting->requirements ?? null"
                    rows="5"
                    placeholder="Skills, experience, and education…"
                />

                <div>
                    <p class="block text-sm font-semibold text-slate-700 mb-2">Posting status</p>
                    @php $status = old('status', $jobPosting->status ?? 'open'); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" role="radiogroup" aria-label="Posting status">
                        <label class="panel-radio-card">
                            <input type="radio" name="status" value="open" class="panel-radio-card__input" {{ $status === 'open' ? 'checked' : '' }}>
                            <span class="panel-radio-card__face panel-radio-card__face--open">Open</span>
                        </label>
                        <label class="panel-radio-card">
                            <input type="radio" name="status" value="draft" class="panel-radio-card__input" {{ $status === 'draft' ? 'checked' : '' }}>
                            <span class="panel-radio-card__face panel-radio-card__face--draft">Draft</span>
                        </label>
                        <label class="panel-radio-card">
                            <input type="radio" name="status" value="closed" class="panel-radio-card__input" {{ $status === 'closed' ? 'checked' : '' }}>
                            <span class="panel-radio-card__face panel-radio-card__face--closed">Closed</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-create-form-layout>
@endsection
