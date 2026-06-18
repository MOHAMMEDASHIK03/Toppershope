@extends('layouts.faculty')

@section('title', 'Faculty Dashboard')
@section('page_header', 'Overview')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-slate-500">Master Courses</h3>
            <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center">
                <i class="ph-bold ph-books text-lg"></i>
            </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ $totalCourses }}</div>
        <p class="text-xs text-slate-400 mt-1">Total unique courses</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-slate-500">Total Batches</h3>
            <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                <i class="ph-bold ph-users-three text-lg"></i>
            </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ $totalBatches }}</div>
        <p class="text-xs text-slate-400 mt-1">Across all categories</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-slate-500">Upcoming Batches</h3>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="ph-bold ph-rocket-launch text-lg"></i>
            </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ $upcomingBatches }}</div>
        <p class="text-xs text-amber-500 mt-1 font-medium">Launching soon</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-slate-500">Enrollments</h3>
            <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                <i class="ph-bold ph-graduation-cap text-lg"></i>
            </div>
        </div>
        <div class="text-2xl font-black text-slate-900">{{ $totalEnrollments }}</div>
        <p class="text-xs text-slate-400 mt-1">Active students</p>
    </div>
</div>

<!-- Main Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Batches Table -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-slate-900">Recently Added Batches</h3>
            <a href="#" class="text-sm font-semibold text-primary hover:text-primary-700 transition-colors">View All</a>
        </div>
        <div class="flex-1 panel-table-wrap">
            <table class="panel-table w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Batch Name</th>
                        <th class="px-6 py-3">Course</th>
                        <th class="px-6 py-3">Start Date</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($recentBatches as $batch)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $batch->name }}</td>
                        <td class="px-6 py-4">{{ $batch->course->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($batch->start_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($batch->is_upcoming)
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">Upcoming</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">Active</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">No batches found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-slate-900 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="#" class="flex items-center p-3 border border-slate-100 rounded-xl hover:border-primary/30 hover:bg-primary-50 transition-colors group">
                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="ph-bold ph-plus"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Create New Course</h4>
                    <p class="text-xs text-slate-500">Design a new master curriculum</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-3 border border-slate-100 rounded-xl hover:border-primary-200 hover:bg-primary-50 transition-colors group">
                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center mr-3 group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <i class="ph-bold ph-users-three"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-primary-700 transition-colors">Launch New Batch</h4>
                    <p class="text-xs text-slate-500">Start enrollment for a course</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection
