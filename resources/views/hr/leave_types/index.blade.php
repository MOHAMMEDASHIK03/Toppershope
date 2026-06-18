@extends('hr.layouts.app')
@section('title', 'Leave Types')
@section('page_title', 'Leave Management')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Leave Types</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Configure allowable leave categories</p>
    </div>
    <a href="{{ route('hr.leave-types.create') }}" class="px-4 py-2 btn-primary font-bold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
        Add Leave Type
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--master-5">
        <div class="panel-list__head">
            <span>Leave type</span>
            <span>Description</span>
            <span>Allowance</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($leaveTypes as $type)
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Leave type</span>
                    <p class="panel-list-row-title">{{ $type->name }}</p>
                </div>
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Description</span>
                    <p class="panel-list-row-muted">{{ $type->description ?: '—' }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Allowance</span>
                    <p class="text-sm font-bold text-orange-600">{{ $type->days_allowed }} <span class="text-slate-500 font-semibold">days / yr</span></p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Status</span>
                    <x-hr.status-badge :active="(bool) $type->is_active" />
                </div>
                <div class="lg:text-right">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.leave-types.edit', $type)" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No leave types configured.<br>
                <a href="{{ route('hr.leave-types.create') }}" class="text-orange-600 hover:underline mt-2 inline-block">Create the first one (e.g. Sick Leave, Casual Leave)</a>
            </div>
        @endforelse
    </div>
    <x-panel.pagination :paginator="$leaveTypes" />
</div>
@endsection
