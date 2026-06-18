@extends('layouts.faculty')

@section('title', 'Faculty Management')
@section('page_title', 'Faculty Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-sm text-slate-500">Manage which instructors are assigned to teach each master course.</p>
        <a href="{{ route('faculty.head.faculties.assign.create') }}" class="btn-primary text-sm py-2.5 px-5 rounded-xl font-semibold shadow-sm">
            <i class="ph-bold ph-plus"></i> Assign faculty to course
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-chalkboard-teacher text-2xl text-primary-700"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $facultyUsers->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">Total faculty</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-books text-2xl text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $allCourses->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">Master courses</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-link text-2xl text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $facultyUsers->sum(fn($f) => $f->courses->count()) }}</p>
                <p class="text-xs text-slate-500 font-medium">Assignments</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                <i class="ph-fill ph-warning-circle text-2xl text-primary-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $facultyUsers->filter(fn($f) => $f->courses->isEmpty())->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">Unassigned</p>
            </div>
        </div>
    </div>

    @if($facultyUsers->isEmpty())
        <x-admin.empty-state title="No faculty members yet" description="Add faculty users via user management or seeders, then assign them to courses.">
            <x-slot:action>
                <a href="{{ route('faculty.head.faculties.assign.create') }}" class="btn-primary mt-4 inline-flex text-sm py-2.5 px-5 rounded-lg">Assign faculty</a>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($facultyUsers as $fac)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="p-5 flex items-center gap-4 border-b border-slate-100 bg-slate-50/50">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold text-lg shrink-0 shadow-sm">
                            {{ strtoupper(substr($fac->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-800 truncate">{{ $fac->name }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $fac->email }}</p>
                        </div>
                        <a href="{{ route('faculty.head.faculties.assign.create', ['user_id' => $fac->id]) }}"
                           class="shrink-0 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 text-xs font-semibold rounded-lg border border-primary-100 transition-colors">
                            + Assign
                        </a>
                    </div>

                    <div class="p-4">
                        @if($fac->courses->isEmpty())
                            <div class="py-6 flex flex-col items-center text-slate-400 text-center">
                                <i class="ph-fill ph-link-break text-2xl mb-1"></i>
                                <p class="text-xs font-medium">No courses assigned yet.</p>
                            </div>
                        @else
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Assigned courses ({{ $fac->courses->count() }})</p>
                            <div class="space-y-2">
                                @foreach($fac->courses as $course)
                                    <div class="flex items-center justify-between group rounded-lg px-3 py-2 hover:bg-slate-50 border border-transparent hover:border-slate-100">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-2 h-2 rounded-full shrink-0 {{ $course->is_published ? 'bg-emerald-400' : 'bg-amber-400' }}"></div>
                                            <p class="text-sm font-medium text-slate-700 truncate">{{ $course->name }}</p>
                                            @if($course->category)
                                                <span class="shrink-0 text-[10px] font-semibold bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">{{ $course->category->name }}</span>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('faculty.head.faculties.unassign') }}" class="shrink-0 ml-2">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $fac->id }}">
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit"
                                                class="p-1.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100"
                                                title="Remove assignment"
                                                onclick="return confirm('Remove {{ addslashes($fac->name) }} from {{ addslashes($course->name) }}?')">
                                                <i class="ph-bold ph-x text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
