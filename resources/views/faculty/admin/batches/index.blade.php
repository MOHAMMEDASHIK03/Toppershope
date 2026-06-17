@extends('layouts.faculty')

@section('title', 'Batch Management')
@section('page_title', 'Batches & Cohorts')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-users-three text-2xl text-orange-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $batches->total() }}</p>
                <p class="text-xs text-slate-500 font-medium">Total batches</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-check-circle text-2xl text-emerald-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $activeBatchCount }}</p>
                <p class="text-xs text-slate-500 font-medium">Active batches</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-clock-countdown text-2xl text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $upcomingCount }}</p>
                <p class="text-xs text-slate-500 font-medium">Upcoming</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-student text-2xl text-slate-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ number_format($totalEnrolled) }}</p>
                <p class="text-xs text-slate-500 font-medium">Total enrolled</p>
            </div>
        </div>
    </div>

    {{-- Filter + actions --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('faculty.head.batches.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Filter by course</label>
                <select name="course_id" onchange="this.form.submit()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm py-2.5 px-3">
                    <option value="">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" onchange="this.form.submit()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm py-2.5 px-3">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="filling_fast" {{ request('status') === 'filling_fast' ? 'selected' : '' }}>Filling fast</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            @if(request()->hasAny(['course_id', 'status']))
                <a href="{{ route('faculty.head.batches.index') }}" class="btn-secondary text-sm py-2.5 px-4">
                    <i class="ph ph-x"></i> Clear
                </a>
            @endif
            <div class="ml-auto">
                <a href="{{ route('faculty.head.batches.create') }}" class="btn-primary text-sm py-2.5 px-5 rounded-xl font-semibold shadow-sm">
                    <i class="ph-bold ph-plus"></i> Create new batch
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="font-semibold text-slate-800">All batches</h3>
                <p class="text-xs text-slate-500 mt-0.5">Showing {{ $batches->firstItem() ?? 0 }}–{{ $batches->lastItem() ?? 0 }} of {{ $batches->total() }}</p>
            </div>
        </div>

        @if($batches->isEmpty())
            <x-admin.empty-state title="No batches yet" description="Create a batch to let students start enrolling.">
                <x-slot:action>
                    <a href="{{ route('faculty.head.batches.create') }}" class="btn-primary mt-4 inline-flex text-sm py-2.5 px-5 rounded-xl">
                        <i class="ph-bold ph-plus"></i> Create first batch
                    </a>
                </x-slot:action>
            </x-admin.empty-state>
        @else
            <div class="panel-table-wrap">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Batch</th>
                            <th>Course</th>
                            <th>Pricing</th>
                            <th class="text-center">Seats</th>
                            <th class="text-center">Mode</th>
                            <th class="text-center">Start date</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-800">{{ $batch->name }}</p>
                                    @if($batch->is_upcoming)
                                        <span class="inline-block text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full mt-0.5">Coming soon</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="font-medium text-slate-700">{{ $batch->course->name ?? '—' }}</p>
                                    @if($batch->category)
                                        <span class="text-[10px] font-semibold text-slate-500">{{ $batch->category->name }}@if($batch->subcategory) · {{ $batch->subcategory->name }}@endif</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="font-bold text-slate-800">₹{{ number_format($batch->price) }}</p>
                                    @if($batch->original_price && $batch->original_price > $batch->price)
                                        <p class="text-xs text-slate-400 line-through">₹{{ number_format($batch->original_price) }}</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <p class="text-sm font-semibold text-slate-700">{{ $batch->filled_seats }} / {{ $batch->total_seats }}</p>
                                    <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden w-20 mx-auto">
                                        <div class="h-full rounded-full {{ $batch->fill_percent >= 80 ? 'bg-red-400' : ($batch->fill_percent >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                             style="width: {{ $batch->fill_percent }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $batch->fill_percent }}% full</p>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 whitespace-nowrap">
                                        {{ $batch->mode ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($batch->start_date)
                                        <p class="text-xs font-medium text-slate-700">{{ $batch->start_date->format('d M Y') }}</p>
                                    @else
                                        <span class="text-xs text-slate-400">TBC</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($batch->status === 'active')
                                        <span class="badge-active">Active</span>
                                    @elseif($batch->status === 'filling_fast')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-200">Filling fast</span>
                                    @elseif($batch->status === 'closed')
                                        <span class="badge-inactive">Closed</span>
                                    @else
                                        <span class="text-xs text-slate-400">{{ $batch->status }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('faculty.head.batches.edit', $batch) }}"
                                           class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                                           title="Edit batch">
                                            <i class="ph-bold ph-pencil-simple text-base"></i>
                                        </a>
                                        <form method="POST" action="{{ route('faculty.head.batches.destroy', $batch) }}"
                                              onsubmit="return confirm('Delete batch \'{{ addslashes($batch->name) }}\'? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete batch">
                                                <i class="ph-bold ph-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-panel.pagination :paginator="$batches" />
        @endif
    </div>
</div>
@endsection
