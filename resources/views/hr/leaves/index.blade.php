@extends('hr.layouts.app')
@section('title', 'Leave Requests')
@section('page_title', 'Leave Management')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Leave Requests</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Review and manage employee time off</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('hr.leaves.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
            Record Leave
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="panel-list panel-list--leaves">
        <div class="panel-list__head">
            <span>Employee</span>
            <span>Type</span>
            <span>Duration</span>
            <span>Status</span>
            <span>Recorded by</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($leaves as $leave)
            @php
                $employee = $leave->employee;
                $initials = $employee
                    ? strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1))
                    : '?';
                $days = (int) $leave->start_date->diffInDays($leave->end_date) + 1;
            @endphp
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Employee</span>
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="panel-list-avatar">{{ $initials }}</div>
                        <div class="min-w-0">
                            @if($employee)
                                <p class="font-bold text-slate-800 text-sm truncate">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="text-[11px] text-slate-500 font-medium truncate">{{ $employee->employee_id }}</p>
                            @else
                                <p class="font-bold text-slate-500 text-sm">Unknown employee</p>
                                <p class="text-[11px] text-rose-500 font-medium">Record missing</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="min-w-0">
                    <span class="panel-list__cell-label">Type</span>
                    <p class="font-bold text-slate-700 text-sm">{{ $leave->leaveType->name ?? '—' }}</p>
                </div>

                <div class="min-w-0">
                    <span class="panel-list__cell-label">Duration</span>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ $leave->start_date->format('j M') }} – {{ $leave->end_date->format('j M Y') }}
                    </p>
                    <p class="text-xs text-slate-500 font-medium">{{ $days }} {{ $days === 1 ? 'day' : 'days' }}</p>
                </div>

                <div>
                    <span class="panel-list__cell-label">Status</span>
                    @if($leave->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                        </span>
                    @elseif($leave->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                        </span>
                    @endif
                </div>

                <div class="min-w-0">
                    <span class="panel-list__cell-label">Recorded by</span>
                    <p class="text-xs font-medium text-slate-500 truncate">{{ $leave->approver?->name ?? 'HR Admin' }}</p>
                </div>

                <div class="lg:text-right">
                    <span class="panel-list__cell-label">Actions</span>
                    <a href="{{ route('hr.leaves.edit', $leave) }}" class="inline-flex items-center justify-center lg:justify-end px-3 py-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors font-bold text-xs sm:text-sm border border-orange-200 bg-white w-full lg:w-auto">
                        Review
                    </a>
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No leave requests found.
            </div>
        @endforelse
    </div>

    <x-panel.pagination :paginator="$leaves" />
</div>
@endsection
