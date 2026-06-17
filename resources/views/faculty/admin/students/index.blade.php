@extends('layouts.faculty')

@section('title', 'Students')
@section('page_header', 'Students')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-student text-2xl text-orange-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($totalStudents) }}</p>
                <p class="text-xs text-slate-500 font-semibold">Total Students</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-check-circle text-2xl text-emerald-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($activeCount) }}</p>
                <p class="text-xs text-slate-500 font-semibold">Active Enrollments</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-users-three text-2xl text-orange-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ number_format($totalBatches) }}</p>
                <p class="text-xs text-slate-500 font-semibold">Total Batches</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-currency-inr text-2xl text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">₹{{ number_format($totalRevenue) }}</p>
                <p class="text-xs text-slate-500 font-semibold">Total Revenue</p>
            </div>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <form method="GET" action="{{ route('faculty.head.students.index') }}" class="flex flex-wrap items-end gap-3">

            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Search Student</label>
                <div class="relative">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Name, email or phone..."
                        class="w-full pl-9 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-primary focus:border-primary text-sm py-2.5">
                </div>
            </div>

            {{-- Course Filter --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Course</label>
                <select name="course_id" onchange="this.form.submit()"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-primary focus:border-primary text-sm py-2.5">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Batch Filter --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Batch</label>
                <select name="batch_id" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-primary focus:border-primary text-sm py-2.5">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="min-w-[140px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-primary focus:border-primary text-sm py-2.5">
                    <option value="">All Status</option>
                    <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 btn-primary text-white font-bold rounded-xl text-sm shadow transition-all flex items-center gap-1.5">
                    <i class="ph-bold ph-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['search','course_id','batch_id','status']))
                <a href="{{ route('faculty.head.students.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition-colors flex items-center gap-1.5">
                    <i class="ph-bold ph-x"></i> Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ===== RESULTS TABLE ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800">Enrolled Students</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Showing {{ $enrollments->firstItem() ?? 0 }}–{{ $enrollments->lastItem() ?? 0 }} of {{ $enrollments->total() }} enrollments
                    @if(request()->hasAny(['search','course_id','batch_id','status']))
                        <span class="ml-1 inline-flex items-center gap-1 text-primary font-semibold"><i class="ph-fill ph-funnel text-xs"></i> Filtered</span>
                    @endif
                </p>
            </div>
        </div>

        @if($enrollments->isEmpty())
        <div class="p-16 text-center text-slate-400">
            <i class="ph-fill ph-student text-5xl mb-3 block"></i>
            <p class="font-bold text-slate-600">No students found.</p>
            <p class="text-sm mt-1">
                @if(request()->hasAny(['search','course_id','batch_id','status']))
                    Try adjusting your filters.
                @else
                    Students will appear here once they enroll in a batch.
                @endif
            </p>
        </div>
        @else
        <div class="panel-table-wrap">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Student</th>
                        <th class="py-3 px-4">Course</th>
                        <th class="py-3 px-4">Batch</th>
                        <th class="py-3 px-4 text-center">Mode</th>
                        <th class="py-3 px-4 text-center">Enrolled On</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($enrollments as $enrollment)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        {{-- Student --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-black text-sm shrink-0">
                                    {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate">{{ $enrollment->user->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $enrollment->user->email }}</p>
                                    @if($enrollment->user->phone)
                                    <p class="text-[10px] text-slate-400">{{ $enrollment->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Course --}}
                        <td class="py-4 px-4">
                            @if($enrollment->batch?->course)
                            <div>
                                <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $enrollment->batch->course->name }}</p>
                                @if($enrollment->batch->course->category)
                                <span class="inline-block mt-0.5 text-[10px] font-bold bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded-full">{{ $enrollment->batch->course->category->name }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        {{-- Batch --}}
                        <td class="py-4 px-4">
                            @if($enrollment->batch)
                            <p class="text-sm font-semibold text-slate-700">{{ $enrollment->batch->name }}</p>
                            @if($enrollment->batch->start_date)
                            <p class="text-[10px] text-slate-400 mt-0.5">From {{ $enrollment->batch->start_date->format('d M Y') }}</p>
                            @endif
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        {{-- Mode --}}
                        <td class="py-4 px-4 text-center">
                            @if($enrollment->batch?->mode)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-orange-50 text-indigo-700 border border-indigo-100 whitespace-nowrap">
                                {{ $enrollment->batch->mode }}
                            </span>
                            @endif
                        </td>

                        {{-- Enrolled On --}}
                        <td class="py-4 px-4 text-center">
                            <p class="text-xs font-semibold text-slate-600">{{ $enrollment->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400">{{ $enrollment->created_at->diffForHumans() }}</p>
                        </td>

                        {{-- Status --}}
                        <td class="py-4 px-4 text-center">
                            @if($enrollment->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                                </span>
                            @elseif($enrollment->status === 'expired')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-slate-50 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Expired
                                </span>
                            @elseif($enrollment->status === 'suspended')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspended
                                </span>
                            @else
                                <span class="text-xs text-slate-400">{{ $enrollment->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-panel.pagination :paginator="$enrollments" />
        @endif
    </div>

</div>
@endsection
