@extends('hr.layouts.app')
@section('title', 'Designations')
@section('page_title', 'Departments & Designations')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Designations</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage job roles within departments</p>
    </div>
    <a href="{{ route('hr.designations.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
        Add Designation
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--master-5">
        <div class="panel-list__head">
            <span>Role</span>
            <span>Department</span>
            <span>Employees</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($designations as $desig)
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Role</span>
                    <p class="panel-list-row-title">{{ $desig->name }}</p>
                </div>
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Department</span>
                    <p class="panel-list-row-muted">{{ $desig->department?->name ?? 'Unassigned' }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Employees</span>
                    <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2.5 rounded-lg bg-orange-50 text-orange-700 font-bold text-xs">
                        {{ $desig->employees_count }}
                    </span>
                </div>
                <div>
                    <span class="panel-list__cell-label">Status</span>
                    <x-hr.status-badge :active="(bool) $desig->is_active" />
                </div>
                <div class="lg:text-right">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.designations.edit', $desig)" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No designations created yet.<br>
                <a href="{{ route('hr.designations.create') }}" class="text-orange-600 hover:underline mt-2 inline-block">Create your first designation</a>
            </div>
        @endforelse
    </div>
    <x-panel.pagination :paginator="$designations" />
</div>
@endsection
