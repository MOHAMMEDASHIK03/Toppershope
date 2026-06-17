@extends('hr.layouts.app')
@section('title', isset($department) ? 'Edit Department' : 'Create Department')
@section('page_title', 'Departments & Designations')

@section('content')
<x-create-form-layout
    :back-href="route('hr.departments.index')"
    back-label="Back to departments"
    :title="isset($department) ? 'Edit department' : 'Create department'"
    subtitle="Organize your team structure with departments."
    :action="isset($department) ? route('hr.departments.update', $department) : route('hr.departments.store')"
    :method="isset($department) ? 'PUT' : 'POST'"
    :submit-label="isset($department) ? 'Save changes' : 'Create department'"
>
    <x-form.field label="Department name" name="name" :value="$department->name ?? null" :required="true" placeholder="e.g. Engineering" />
    <x-form.field label="Description" name="description" type="textarea" :value="$department->description ?? null" rows="3" placeholder="Brief description of this department's function" />

    <div class="flex items-center gap-3">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="w-5 h-5 text-orange-600 border-slate-300 rounded focus:ring-orange-500" {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">Active department</label>
    </div>
</x-create-form-layout>
@endsection
