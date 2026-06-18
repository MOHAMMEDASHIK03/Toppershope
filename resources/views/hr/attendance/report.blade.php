@extends('hr.layouts.app')
@section('title', 'Attendance Report')
@section('page_title', 'Attendance')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('hr.attendance.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $employee->first_name }} {{ $employee->last_name }}'s Attendance</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">{{ $employee->employee_id }} • {{ $employee->department->name ?? 'Unassigned' }}</p>
        </div>
    </div>
    
    <div class="flex items-center gap-3">
        <form action="{{ route('hr.attendance.report', $employee->id) }}" method="GET" class="flex gap-2">
            <input type="month" name="month" value="{{ $month }}" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-600/20 focus:border-primary-600 outline-none font-medium text-slate-700 text-sm shadow-sm" onchange="this.form.submit()">
        </form>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="px-4 py-2.5 bg-primary-50 text-emerald-700 border border-emerald-200 font-bold rounded-xl text-sm shadow-sm hover:bg-emerald-100 transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-42.34-77.66a8,8,0,0,1-11.32,11.32L136,139.31V184a8,8,0,0,1-16,0V139.31l-10.34,10.35a8,8,0,0,1-11.32-11.32l24-24a8,8,0,0,1,11.32,0Z"/></svg>
            Export CSV
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    @php
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        $halfDay = $attendances->where('status', 'half_day')->count();
        $onLeave = $attendances->where('status', 'on_leave')->count();
    @endphp
    
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Present</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $present }} <span class="text-sm text-slate-400 font-medium">days</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm45.66,85.66-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35a8,8,0,0,1,11.32,11.32Z"/></svg>
        </div>
    </div>
    
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Absent</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $absent }} <span class="text-sm text-slate-400 font-medium">days</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-rose-50 text-primary-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm37.66,130.34a8,8,0,0,1-11.32,11.32L128,139.31l-26.34,26.35a8,8,0,0,1-11.32-11.32L116.69,128,90.34,101.66a8,8,0,0,1,11.32-11.32L128,116.69l26.34-26.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/></svg>
        </div>
    </div>
    
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Late</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $late }} <span class="text-sm text-slate-400 font-medium">days</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h48A8,8,0,0,1,192,128Z"/></svg>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-slate-500 mb-1">Leave / Half-Day</p>
            <h3 class="text-2xl font-black text-slate-800">{{ $onLeave + $halfDay }} <span class="text-sm text-slate-400 font-medium">days</span></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-50 text-primary-700 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88v64a8,8,0,0,1-16,0V88a8,8,0,0,1,16,0ZM96,152a8,8,0,0,1-16,0V88a8,8,0,0,1,16,0Z"/></svg>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="panel-table w-full text-left text-sm">
        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
            <tr>
                <th class="px-6 py-4">Date</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Check In</th>
                <th class="px-6 py-4">Check Out</th>
                <th class="px-6 py-4">Notes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($attendances as $record)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 font-bold text-slate-700">
                    {{ $record->date->format('M d, Y') }}
                    <span class="text-xs text-slate-400 font-medium ml-2">{{ $record->date->format('l') }}</span>
                </td>
                <td class="px-6 py-4">
                    @if($record->status === 'present')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-primary-50 text-emerald-700 font-bold text-xs uppercase tracking-wider">Present</span>
                    @elseif($record->status === 'absent')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-rose-50 text-rose-700 font-bold text-xs uppercase tracking-wider">Absent</span>
                    @elseif($record->status === 'late')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 font-bold text-xs uppercase tracking-wider">Late</span>
                    @elseif($record->status === 'half_day')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-primary-50 text-primary-700 font-bold text-xs uppercase tracking-wider">Half Day</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-primary-50 text-primary-700 font-bold text-xs uppercase tracking-wider">On Leave</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-medium text-slate-600">
                    {{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : '--:--' }}
                </td>
                <td class="px-6 py-4 font-medium text-slate-600">
                    {{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '--:--' }}
                </td>
                <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $record->notes }}">
                    {{ $record->notes ?: '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                    No attendance records found for {{ Carbon\Carbon::parse($month)->format('F Y') }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
