@extends('layouts.faculty')

@section('title', 'My Assigned Courses')
@section('page_title', 'My Assigned Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Welcome, {{ Auth::user()->name }}</h2>
        <p class="text-sm text-slate-500 mt-1">Select a course below to manage its content, students, and doubts.</p>
    </div>

    @if($courses->isEmpty())
        <x-admin.empty-state title="No courses assigned" description="You have not been assigned to any master courses yet. If you believe this is a mistake, please contact the administration." />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($courses as $course)
                <article class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-primary-200 transition-all">
                    <div class="relative">
                        <x-course.cover :course="$course" height="h-40" />
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-white/95 backdrop-blur text-slate-700 text-[10px] font-semibold px-2 py-1 rounded-md uppercase tracking-wide shadow-sm border border-slate-100">
                                {{ $course->category?->name ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-semibold text-slate-900 text-base leading-snug mb-3 group-hover:text-primary-700 transition-colors line-clamp-2">
                            {{ $course->name }}
                        </h3>

                        <div class="mt-auto flex flex-wrap gap-2">
                            <span class="inline-flex items-center text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                                {{ $course->batches_count }} {{ Str::plural('batch', $course->batches_count) }}
                            </span>
                            @if($course->is_published)
                                <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-primary-50 border border-emerald-200 px-2 py-1 rounded-md">
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-semibold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-1 rounded-md">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/80">
                        <a href="{{ route('faculty.courses.curriculum', $course->id) }}" class="btn-primary w-full py-2.5 rounded-lg text-sm font-semibold justify-center">
                            Manage course
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
