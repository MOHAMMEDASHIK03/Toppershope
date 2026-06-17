@extends('hr.layouts.app')
@section('title', isset($announcement) ? 'Edit Announcement' : 'New Announcement')
@section('page_title', 'Company')

@section('content')
<x-create-form-layout
    :back-href="route('hr.announcements.index')"
    back-label="Back to announcements"
    :title="isset($announcement) ? 'Edit announcement' : 'New announcement'"
    subtitle="Share important updates with the team."
    :action="isset($announcement) ? route('hr.announcements.update', $announcement) : route('hr.announcements.store')"
    :method="isset($announcement) ? 'PUT' : 'POST'"
    :submit-label="isset($announcement) ? 'Save updates' : 'Publish announcement'"
    max-width="max-w-4xl"
    :delete-action="isset($announcement) ? route('hr.announcements.destroy', $announcement) : null"
    delete-label="Delete announcement"
    delete-confirm="Delete this announcement permanently?"
>
    <x-form.field label="Announcement title" name="title" :value="$announcement->title ?? null" :required="true" placeholder="e.g. Update to Leave Policy 2026" />
    <x-form.field label="Message content" name="content" type="textarea" :value="$announcement->content ?? null" :required="true" rows="8" placeholder="Write your announcement here..." />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
        <div>
            <label for="department_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Target audience</label>
            <select id="department_id" name="department_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <option value="">Company wide (all employees)</option>
                @foreach(\App\Models\HR\Department::all() as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $announcement->department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }} only</option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1.5">Company wide = every employee. Department = that team only (when employee feeds are enabled).</p>
        </div>
        <div>
            <label for="is_active" class="block text-sm font-semibold text-slate-700 mb-1.5">Visibility status</label>
            @php
                $isLive = old('is_active', isset($announcement) ? ($announcement->is_active ? '1' : '0') : '1');
            @endphp
            <select id="is_active" name="is_active" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <option value="1" {{ (string) $isLive === '1' ? 'selected' : '' }}>Live — visible to the target audience</option>
                <option value="0" {{ (string) $isLive === '0' ? 'selected' : '' }}>Archived — hidden from employees (HR can still see it)</option>
            </select>
            <p class="text-xs text-slate-500 mt-1.5">Archived does not delete the post; it only hides it from staff-facing views.</p>
        </div>
    </div>
</x-create-form-layout>
@endsection
