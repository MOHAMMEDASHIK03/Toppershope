@extends('hr.layouts.app')
@section('title', 'Employees Overview')
@section('page_title', 'Employees')

@section('content')

{{-- Header --}}
<div class="mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Staff Directory</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">All employees across every panel. Use <strong>Sync from Panels</strong> to import existing users.</p>
    </div>
    
    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
        {{-- Exports --}}
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-extrabold rounded-xl text-xs sm:text-sm shadow-sm hover:bg-slate-50 transition-colors inline-flex items-center gap-1.5 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-42.34-77.66a8,8,0,0,1-11.32,11.32L136,139.31V184a8,8,0,0,1-16,0V139.31l-10.34,10.35a8,8,0,0,1-11.32-11.32l24-24a8,8,0,0,1,11.32,0Z"/></svg>
            Export CSV
        </a>

        {{-- Sync Button --}}
        <form action="{{ route('hr.employees.sync-panels') }}" method="POST" class="inline shrink-0">
            @csrf
            <button type="submit"
                    onclick="return confirm('This will import all existing panel users (HR, Faculty, Ads, Admission) as employee records. Continue?')"
                    class="px-3.5 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold rounded-xl text-xs sm:text-sm hover:bg-amber-100 transition-colors inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M224,48V96a8,8,0,0,1-8,8H168a8,8,0,0,1,0-16h28.69L182.06,73.37A80,80,0,1,0,184.8,184.73a8,8,0,0,1,10.93,11.67A96,96,0,1,1,208,80.35V48a8,8,0,0,1,16,0Z"/></svg>
                Sync Panels
            </button>
        </form>

        <a href="{{ route('hr.employees.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
            Add Employee
        </a>
    </div>
</div>

{{-- Search & Filter Bar --}}
<form method="GET" action="{{ route('hr.employees.index') }}" class="mb-6 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex flex-col lg:flex-row gap-4 items-end">
    <div class="flex-[3] w-full relative">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search Employees</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, or EMP ID..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-xl text-sm font-semibold text-slate-800 outline-none focus:ring-4 focus:ring-indigo-150 transition-all">
        </div>
    </div>
    <div class="w-full lg:w-44">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Department</label>
        <select name="department_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="w-full lg:w-36 px-2">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none">
            <option value="">All Statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="flex-1 md:flex-initial px-6 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2">
            Filter
        </button>
        @if(request()->anyFilled(['search', 'department_id', 'status']))
            <a href="{{ route('hr.employees.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-extrabold rounded-xl text-sm hover:bg-slate-200 transition-colors inline-flex items-center justify-center">
                Clear
            </a>
        @endif
    </div>
</form>

{{-- Employee list --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--employees">
        <div class="panel-list__head">
            <span>Employee</span>
            <span>Role &amp; tenure</span>
            <span>Access</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($employees as $emp)
            @php
                $panelLabel = null;
                $panelColor = null;
                if ($emp->account_type) {
                    if ($emp->account_type === 'App\Models\HR\HrUser') {
                        $panelLabel = 'HR Panel';
                        $panelColor = 'bg-rose-50 border border-rose-100 text-rose-700';
                    } elseif ($emp->account_type === 'App\Models\User') {
                        if (isset($emp->account) && $emp->account->role === 'faculty_head') {
                            $panelLabel = 'Faculty Head';
                            $panelColor = 'bg-purple-50 border border-purple-100 text-purple-700';
                        } else {
                            $panelLabel = 'Faculty';
                            $panelColor = 'bg-indigo-50 border border-indigo-100 text-indigo-700';
                        }
                    } elseif ($emp->account_type === 'App\Models\Ads\AdsUser') {
                        $panelLabel = 'Ads Panel';
                        $panelColor = 'bg-blue-50 border border-blue-100 text-blue-700';
                    } elseif ($emp->account_type === 'App\Models\Admission\AdmissionUser') {
                        $panelLabel = 'Admission CRM';
                        $panelColor = 'bg-emerald-50 border border-emerald-100 text-emerald-700';
                    }
                }
                $initials = strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1));
            @endphp
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Employee</span>
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="panel-list-avatar shrink-0">{{ $initials }}</div>
                        <div class="min-w-0 flex-1">
                            <p class="panel-emp-name">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                            <div class="panel-emp-meta">
                                <span class="panel-emp-id" title="Employee ID">{{ $emp->employee_id }}</span>
                            </div>
                            @if($emp->phone)
                                <p class="text-xs text-slate-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="break-all">{{ $emp->phone }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="min-w-0">
                    <span class="panel-list__cell-label">Role &amp; tenure</span>
                    <p class="font-bold text-slate-800 text-sm leading-snug break-words">{{ $emp->department->name ?? '—' }}</p>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5 break-words">{{ $emp->designation->name ?? '—' }}</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">
                        Joined {{ $emp->joining_date ? $emp->joining_date->format('j M Y') : '—' }}
                    </p>
                </div>

                <div class="min-w-0">
                    <span class="panel-list__cell-label">Access</span>
                    @if($panelLabel)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold leading-tight {{ $panelColor }}">
                            {{ $panelLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-50 border border-slate-200 text-slate-500">No access</span>
                    @endif
                </div>

                <div>
                    <span class="panel-list__cell-label">Status</span>
                    @if($emp->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold text-[10px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold text-[10px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                        </span>
                    @endif
                </div>

                <div class="lg:text-right">
                    <span class="panel-list__cell-label">Actions</span>
                    <div class="flex items-center justify-start lg:justify-end gap-1">
                        <form
                            action="{{ route('hr.employees.toggle-status', $emp->id) }}"
                            method="POST"
                            class="inline"
                            data-confirm="Change status for this employee? Panel access will also be updated."
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="p-2 {{ $emp->is_active ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors"
                                title="{{ $emp->is_active ? 'Deactivate' : 'Activate' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm48-88a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,128Z"/></svg>
                            </button>
                        </form>
                        <a
                            href="{{ route('hr.employees.show', $emp->id) }}"
                            class="p-2 text-orange-600 hover:bg-orange-50 border border-orange-200 rounded-lg transition-colors inline-flex"
                            title="View profile"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.34c18.83-18.83,27.3-37.61,27.65-38.4A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.46,133.46,0,0,1,231.05,128c-7.21,13.46-38.62,64-103.05,64Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"/></svg>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="flex flex-col items-center gap-3 max-w-md mx-auto">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-600 font-bold">No employees found</p>
                        <p class="text-slate-400 text-sm mt-1">Use <strong>Sync from Panels</strong> or <strong>Add Employee</strong> to get started.</p>
                    </div>
                    <div class="flex flex-wrap gap-3 mt-2 justify-center">
                        <form action="{{ route('hr.employees.sync-panels') }}" method="POST" data-confirm="Import all existing panel users as employees?">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-amber-500 text-white font-bold rounded-xl text-sm hover:bg-amber-600 transition-colors">
                                Sync from Panels
                            </button>
                        </form>
                        <a href="{{ route('hr.employees.create') }}" class="px-4 py-2 btn-primary font-bold rounded-xl text-sm">
                            Add Employee
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <x-panel.pagination :paginator="$employees" />
</div>
@endsection

