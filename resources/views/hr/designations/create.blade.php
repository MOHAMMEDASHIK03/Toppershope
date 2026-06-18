@extends('hr.layouts.app')
@section('title', isset($designation) ? 'Edit Designation' : 'Create Designation')
@section('page_title', 'Departments & Designations')

@section('content')
<x-create-form-layout
    :back-href="route('hr.designations.index')"
    back-label="Back to designations"
    :title="isset($designation) ? 'Edit designation' : 'Create designation'"
    subtitle="Define job titles and link them to a department."
    :action="isset($designation) ? route('hr.designations.update', $designation) : route('hr.designations.store')"
    :method="isset($designation) ? 'PUT' : 'POST'"
    :submit-label="isset($designation) ? 'Save changes' : 'Create designation'"
>
    <x-form.field label="Designation title" name="name" :value="$designation->name ?? null" :required="true" placeholder="e.g. Senior Developer" />

    <div>
        <label for="department_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Department <span class="text-red-500">*</span></label>
        <select id="department_id" name="department_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            <option value="">Select a department</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $designation->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
        @error('department_id')<p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>@enderror
    </div>

    <x-form.field label="Description" name="description" type="textarea" :value="$designation->description ?? null" rows="3" placeholder="Brief description of this role's responsibilities" />

    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="w-5 h-5 text-orange-600 border-slate-300 rounded focus:ring-orange-500" {{ old('is_active', $designation->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">Active designation</label>
    </div>
</x-create-form-layout>
@endsection
