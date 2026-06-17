@extends('layouts.faculty')

@section('title', 'Student Roster - ' . $course->title)

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
            <a href="{{ route('faculty.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-slate-600 font-medium truncate max-w-[200px] md:max-w-xs">{{ $course->title }}</span>
            <span>/</span>
            <span class="text-primary font-medium">Student Roster</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
            <a href="{{ route('course.detail', $course->slug ?? $course->id) }}" target="_blank" class="hover:text-primary transition-colors group flex items-center gap-2">
                <i class="fas fa-arrow-left text-lg text-slate-400 group-hover:text-primary transition-colors"></i>
                Manage: {{ $course->title }}
            </a>
            <a href="{{ route('course.detail', $course->slug ?? $course->id) }}" target="_blank" class="text-xs font-normal text-slate-400 hover:text-primary mt-1 flex items-center gap-1 border border-slate-200 rounded-full px-2 py-0.5 bg-white transition-all hover:border-primary/30 hover:shadow-sm">
                View Website <i class="fas fa-external-link-alt text-[10px]"></i>
            </a>
        </h1>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="border-b border-slate-200 mb-8 overflow-x-auto hide-scrollbar">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('faculty.courses.curriculum', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Curriculum Builder
        </a>
        <a href="{{ route('faculty.courses.content.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Content Manager
        </a>
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Quizzes
        </a>
        <a href="{{ route('faculty.courses.doubts.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors relative">
            Doubts 
            @php $unresolvedCount = $course->doubts()->where('is_resolved', false)->count(); @endphp
            @if($unresolvedCount > 0)
                <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow-sm shadow-red-500/30">{{ $unresolvedCount }}</span>
            @endif
        </a>
        <a href="{{ route('faculty.courses.students.index', $course->id) }}" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
            Students
        </a>
        <a href="{{ route('faculty.courses.results.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Results
        </a>
    </nav>
</div>

<!-- Main Content Area -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Enrolled Students</h2>
            <p class="text-sm text-slate-500">Filter students by their assigned batches.</p>
        </div>
        
        <form method="GET" action="{{ route('faculty.courses.students.index', $course->id) }}" class="flex items-center gap-3">
            <select name="batch_id" onchange="this.form.submit()" class="text-sm border-slate-200 rounded-lg focus:ring-primary focus:border-primary shadow-sm py-2 pl-3 pr-10 appearance-none bg-white">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                        {{ $batch->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($enrollments->isEmpty())
        <div class="text-center py-16 bg-slate-50 rounded-xl border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto shadow-sm mb-4">
                <i class="fas fa-users text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-slate-800 font-bold mb-1">No Students Found</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto">There are currently no active student enrollments for the selected criteria.</p>
        </div>
    @else
        <div class="panel-table-wrap">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-y border-slate-100/80">
                        <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-12">#</th>
                        <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Student</th>
                        <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Contact</th>
                        <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Batch</th>
                        <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Enrolled On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($enrollments as $index => $enrollment)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-4 text-slate-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($enrollment->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $enrollment->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-500">{{ $enrollment->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <p class="text-slate-600 truncate max-w-[150px]"><i class="fas fa-phone-alt text-slate-400 text-xs mr-1"></i> {{ $enrollment->user->phone ?? 'Not provided' }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-xs font-semibold whitespace-nowrap border border-slate-200/60 shadow-sm">
                                    {{ $enrollment->batch->name ?? 'Unknown Batch' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <p class="text-slate-500 text-xs font-medium">{{ $enrollment->created_at->format('M j, Y') }}</p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
