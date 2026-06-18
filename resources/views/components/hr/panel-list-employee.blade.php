@props([
    'employee',
])

@php
    $initials = $employee
        ? strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name ?? '', 0, 1))
        : '?';
@endphp

<div class="flex items-center gap-2.5 panel-list-employee-block min-w-0">
    <div class="panel-list-avatar shrink-0">{{ $initials }}</div>
    <div class="min-w-0 flex-1">
        @if($employee)
            <p class="panel-emp-name" title="{{ $employee->first_name }} {{ $employee->last_name }}">
                {{ $employee->first_name }} {{ $employee->last_name }}
            </p>
            <div class="panel-emp-meta">
                <span class="panel-emp-id">{{ $employee->employee_id }}</span>
            </div>
        @else
            <p class="panel-emp-name text-slate-500">Unknown</p>
        @endif
    </div>
</div>
