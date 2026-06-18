@extends('hr.layouts.app')
@section('title', 'Review Leave Request')
@section('page_title', 'Leave Management')

@section('content')
@php
    $employee = $leave->employee;
    $initials = $employee
        ? strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1))
        : '?';
    $dayCount = (int) $leave->start_date->diffInDays($leave->end_date) + 1;
    $statusBadge = match ($leave->status) {
        'approved' => ['label' => 'Approved', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'rejected' => ['label' => 'Rejected', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
        default => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
    };
@endphp

<div class="leave-review-page max-w-4xl mx-auto overflow-visible">
    <a href="{{ route('hr.leaves.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-orange-600 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        Back to leave requests
    </a>

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800">Review leave request</h2>
            <p class="text-sm text-slate-500 mt-1">Approve, keep pending, or reject this time-off request.</p>
        </div>
        <span class="inline-flex items-center self-start px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusBadge['class'] }}">
            {{ $statusBadge['label'] }}
        </span>
    </div>

    {{-- Request summary (read-only) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-visible mb-5">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-100 to-amber-50 text-orange-700 font-bold flex items-center justify-center text-base shrink-0 border border-orange-100">
                {{ $initials }}
            </div>
            <div class="min-w-0 flex-1">
                @if($employee)
                    <h3 class="text-base font-bold text-slate-900">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <p class="text-sm text-slate-500">{{ $employee->employee_id }} · {{ $employee->department->name ?? 'Unassigned' }}</p>
                @else
                    <h3 class="text-base font-bold text-slate-700">Employee record unavailable</h3>
                    <p class="text-sm text-rose-600">Linked employee may have been removed.</p>
                @endif
            </div>
        </div>

        <div class="p-5 grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Type</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $leave->leaveType->name ?? '—' }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Duration</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $dayCount }} {{ $dayCount === 1 ? 'day' : 'days' }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">From</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $leave->start_date->format('j M Y') }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">To</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $leave->end_date->format('j M Y') }}</p>
            </div>
        </div>

        <div class="px-5 pb-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Reason</p>
            <p class="text-sm text-slate-700 leading-relaxed rounded-lg bg-slate-50 border border-slate-100 px-3 py-3 whitespace-pre-wrap">{{ $leave->reason }}</p>
        </div>
    </div>

    {{-- Decision (single card, no inner scroll / no sticky) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6 overflow-visible">
        <form id="leave-decision-form" action="{{ route('hr.leaves.update', $leave) }}" method="POST">
            @csrf
            @method('PUT')

            <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Official decision</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                @php
                    $current = old('status', $leave->status);
                    $decisions = [
                        'approved' => ['label' => 'Approve', 'variant' => 'approved'],
                        'pending' => ['label' => 'Pending', 'variant' => 'pending'],
                        'rejected' => ['label' => 'Reject', 'variant' => 'rejected'],
                    ];
                @endphp
                @foreach($decisions as $key => $opt)
                    <label class="panel-radio-card">
                        <input
                            type="radio"
                            name="status"
                            value="{{ $key }}"
                            class="panel-radio-card__input"
                            {{ $current === $key ? 'checked' : '' }}
                            required
                        >
                        <div class="panel-radio-card__face panel-radio-card__face--{{ $opt['variant'] }}">
                            {{ $opt['label'] }}
                        </div>
                    </label>
                @endforeach
            </div>
            @error('status')
                <p class="text-xs text-rose-600 font-semibold -mt-3 mb-4">{{ $message }}</p>
            @enderror

            <x-form.field
                label="HR remarks (optional)"
                name="admin_remarks"
                type="textarea"
                :value="old('admin_remarks', $leave->admin_remarks)"
                rows="3"
                placeholder="Optional feedback for the employee…"
            />
        </form>

        <x-panel.form-footer
            form-id="leave-decision-form"
            submit-label="Save decision"
            :cancel-href="route('hr.leaves.index')"
            :delete-action="route('hr.leaves.destroy', $leave)"
            delete-label="Delete record"
            delete-confirm="Permanently delete this leave request?"
            class="!pt-4 !mt-4"
        />
    </div>
</div>
@endsection
