@extends('hr.layouts.app')
@section('title', 'Upload Document')
@section('page_title', 'Company')

@section('content')
@php
    $categories = [
        'identity' => 'Identity proof (Aadhar / PAN)',
        'contract' => 'Employment contract',
        'education' => 'Educational certificates',
        'experience' => 'Experience letters',
        'financial' => 'Financial / bank details',
        'other' => 'Other documents',
    ];
@endphp

@if ($errors->any())
    <div class="max-w-2xl mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
        <p class="font-bold mb-2">Please fix the following:</p>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-create-form-layout
    :back-href="route('hr.employee-documents.index')"
    back-label="Back to documents vault"
    title="Upload document"
    subtitle="Attach a secure file to an employee record (PDF, JPG, or PNG — max 5MB)."
    :action="route('hr.employee-documents.store')"
    submit-label="Encrypt & save"
    max-width="max-w-2xl"
    enctype="multipart/form-data"
>
    <div class="space-y-6">
        <x-form.field label="Link to employee" name="employee_id" type="select" :required="true">
            <option value="">Select an employee…</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                </option>
            @endforeach
        </x-form.field>

        <x-form.field
            label="Document name"
            name="document_name"
            :value="old('document_name')"
            :required="true"
            placeholder="e.g. Identity proof, relieving letter"
        />

        <x-form.field label="Category" name="document_type" type="select" :required="true">
            <option value="">Select category…</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" {{ old('document_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </x-form.field>

        <div>
            <label for="document_file" class="block text-sm font-semibold text-slate-700 mb-1.5">
                File upload <span class="text-red-500">*</span>
            </label>
            <p class="text-xs text-slate-500 mb-2">PDF, JPG, or PNG — maximum 5MB</p>
            <div class="relative rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 hover:border-primary-400 hover:bg-primary-50/40 transition-colors p-6 text-center">
                <input
                    type="file"
                    id="document_file"
                    name="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer"
                    onchange="document.getElementById('file-name-hint').textContent = this.files[0]?.name || 'No file chosen'"
                >
                <div class="pointer-events-none">
                    <div class="mx-auto w-12 h-12 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216ZM133.66,122.34A8,8,0,0,1,136,128v40a8,8,0,0,1-16,0V139.31l-18.34,18.35a8,8,0,0,1-11.32-11.32l32-32A8,8,0,0,1,133.66,122.34Z"/></svg>
                    </div>
                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white border border-slate-200 text-sm font-semibold text-primary-700">
                        Choose file
                    </span>
                    <p id="file-name-hint" class="mt-3 text-xs font-medium text-slate-500">No file chosen</p>
                </div>
            </div>
            @error('file')
                <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</x-create-form-layout>

@push('scripts')
<script>
    document.getElementById('document_file')?.addEventListener('change', function () {
        const hint = document.getElementById('file-name-hint');
        if (hint) hint.textContent = this.files[0]?.name || 'No file chosen';
    });
</script>
@endpush
@endsection
