@extends('hr.layouts.app')
@section('title', 'Daily Attendance')
@section('page_title', 'Attendance')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Daily Attendance Tracker</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Mark and monitor employee presence</p>
    </div>
    
    <form action="{{ route('hr.attendance.index') }}" method="GET" class="flex gap-3">
        <input type="date" name="date" value="{{ $date }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none font-medium text-slate-700 text-sm shadow-sm" onchange="this.form.submit()">
        <button type="submit" class="px-4 py-2 bg-orange-50 text-indigo-700 font-bold rounded-xl text-sm shadow-sm hover:bg-orange-100 transition-colors">
            Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="panel-table-wrap">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold select-none">
                <tr>
                    <th class="px-4 py-3 w-1/4">Employee</th>
                    <th class="px-4 py-3 w-36">Status</th>
                    <th class="px-4 py-3">Check In & Out</th>
                    <th class="px-4 py-3 w-1/3">Notes / Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($employees as $emp)
                @php
                    $att = $attendances[$emp->id] ?? null;
                    $status = $att ? $att->status : 'present';
                @endphp
                <tr x-data="{ 
                    status: '{{ $status }}', 
                    timeMode: '{{ $att ? ($att->check_out && !$att->check_in ? 'check_out' : 'check_in') : 'check_in' }}' 
                }" class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center shrink-0">
                                {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium">{{ $emp->employee_id }} • {{ $emp->department->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    
                    <form action="{{ route('hr.attendance.mark') }}" method="POST">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        
                        <td class="px-4 py-3">
                            <select name="status" x-model="status" class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none font-semibold text-slate-700 text-xs">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="half_day">Half Day</option>
                                <option value="late">Late</option>
                                <option value="on_leave">OnLeave</option>
                            </select>
                        </td>
                        
                        <td class="px-4 py-3">
                            <div x-show="status === 'absent' || status === 'on_leave'" x-transition class="text-xs font-bold text-slate-400 italic">
                                Direct Save (No Time Required)
                            </div>
                            
                            <div x-show="status !== 'absent' && status !== 'on_leave'" x-transition class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                <select x-model="timeMode" class="px-2 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 text-xs font-bold focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 outline-none shrink-0 w-28">
                                    <option value="check_in">Check In</option>
                                    <option value="check_out">Check Out</option>
                                </select>
                                
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <!-- Check In -->
                                    <input type="time" name="check_in" 
                                           value="{{ $att ? ($att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '') : '09:00' }}"
                                           x-show="timeMode === 'check_in'"
                                           :disabled="timeMode !== 'check_in'"
                                           class="w-28 sm:w-32 px-2 py-1 bg-slate-50 border border-slate-200 rounded-md focus:border-indigo-600 outline-none font-semibold text-slate-700 text-xs">
                                    
                                    <!-- Check Out -->
                                    <input type="time" name="check_out" 
                                           value="{{ $att ? ($att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '') : '18:00' }}"
                                           x-show="timeMode === 'check_out'"
                                           :disabled="timeMode !== 'check_out'"
                                           class="w-28 sm:w-32 px-2 py-1 bg-slate-50 border border-slate-200 rounded-md focus:border-indigo-600 outline-none font-semibold text-slate-700 text-xs">
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <input type="text" name="notes" value="{{ $att->notes ?? '' }}" placeholder="Reason..." class="flex-1 min-w-[80px] px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md focus:border-indigo-600 outline-none font-medium text-slate-700 text-xs placeholder:text-slate-400">
                                
                                <button type="submit" class="p-1.5 {{ $att ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-orange-50 text-orange-600 hover:bg-orange-100' }} rounded-md font-bold transition-colors shrink-0" title="Save Attendance">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"/></svg>
                                </button>
                                <a href="{{ route('hr.attendance.report', $emp->id) }}" class="p-1.5 bg-slate-50 text-slate-600 hover:bg-slate-100 rounded-md font-bold transition-colors shrink-0" title="View Report">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M247.31,124.76c-.35-.79-8-17.76-23.31-33C203.22,70.91,168.62,56,128,56S52.78,70.91,32,91.76c-15.31,15.24-23,32.21-23.31,33a8,8,0,0,0,0,6.48c.35.79,8,17.76,23.31,33C52.78,185.09,87.38,200,128,200s75.22-14.91,96-35.76c15.31-15.24,23-32.21,23.31-33A8,8,0,0,0,247.31,124.76ZM128,168a40,40,0,1,1,40-40A40,40,0,0,1,128,168Zm0-64a24,24,0,1,0,24,24A24,24,0,0,0,128,104Z"/></svg>
                                </a>
                            </div>
                        </td>
                    </form>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                        No active employees found in the system.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-panel.pagination :paginator="$employees" />
</div>
@endsection
