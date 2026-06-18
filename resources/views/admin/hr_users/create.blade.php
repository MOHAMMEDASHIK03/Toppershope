@extends('admin.layouts.app')
@section('title', isset($hrUser) ? 'Update HR User' : 'New HR User')
@section('page_title', isset($hrUser) ? 'Update HR User' : 'New HR User')

@section('content')
<x-create-form-layout
    :back-href="route('admin.hr-users.index')"
    back-label="Back to HR users"
    :title="isset($hrUser) ? 'Edit HR user' : 'New HR user'"
    subtitle="Configure login credentials for a Human Resources manager."
    :action="isset($hrUser) ? route('admin.hr-users.update', $hrUser) : route('admin.hr-users.store')"
    :method="isset($hrUser) ? 'PUT' : 'POST'"
    :submit-label="isset($hrUser) ? 'Save changes' : 'Create HR user'"
    max-width="max-w-2xl"
>
    <x-form.field label="Full name" name="name" :value="$hrUser->name ?? null" :required="true" placeholder="Representative name" />
    <x-form.field label="Email" name="email" type="email" :value="$hrUser->email ?? null" :required="true" placeholder="name@company.com" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
        <x-form.field
            label="Password"
            name="password"
            type="password"
            :required="!isset($hrUser)"
            :hint="isset($hrUser) ? 'Leave blank to keep the current password.' : null"
            placeholder="••••••••"
        />
        <x-form.field label="Confirm password" name="password_confirmation" type="password" :required="!isset($hrUser)" placeholder="••••••••" />
    </div>

    <div class="flex items-center gap-3 pt-2">
        <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-primary-700 focus:ring-primary-500" {{ old('is_active', $hrUser->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-slate-700">Account active (can sign in)</label>
    </div>
</x-create-form-layout>
@endsection
