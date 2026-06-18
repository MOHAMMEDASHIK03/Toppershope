@extends('student.layouts.app')
@section('title', 'My Courses')
@section('page_title', 'My Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">My learning</h2>
        <p class="text-sm text-slate-500 mt-1">All your enrolled courses in one place. Continue where you left off.</p>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($enrollments as $enrollment)
                <a href="{{ route('student.my-courses.show', $enrollment->id) }}"
                   class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden hover:border-primary-200 hover:shadow-md transition-all group block">
                    <div class="h-40 bg-gradient-to-br from-primary-50 to-amber-50 relative overflow-hidden">
                        @if($enrollment->batch->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->batch->course->thumbnail) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-4xl font-bold text-primary-200">{{ strtoupper(substr($enrollment->batch->course->name, 0, 2)) }}</span>
                            </div>
                        @endif
                        <span class="absolute bottom-3 left-3 px-2 py-0.5 rounded-md bg-white/95 text-[10px] font-semibold text-slate-800 border border-slate-200 shadow-sm">
                            {{ $enrollment->batch->name }}
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-slate-900 text-sm group-hover:text-primary-700 transition-colors">{{ $enrollment->batch->course->name }}</h3>
                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-2 flex-wrap">
                            @if($enrollment->batch->course->category)
                                <span class="px-2 py-0.5 rounded bg-primary-50 text-primary-700 font-semibold uppercase text-[10px]">{{ $enrollment->batch->course->category->name }}</span>
                            @endif
                            @if($enrollment->batch->mentor_name)
                                <span>{{ $enrollment->batch->mentor_name }}</span>
                            @endif
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="badge-active">Active</span>
                            <span class="text-primary-700 text-xs font-semibold group-hover:text-primary-700">Continue →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <x-admin.empty-state title="No courses yet" description="Start your learning journey by enrolling in a course from the catalog.">
            <x-slot:action>
                <a href="{{ route('student.catalog') }}" class="btn-primary mt-4 inline-flex text-sm py-2.5 px-5 rounded-lg">Browse courses →</a>
            </x-slot:action>
        </x-admin.empty-state>
    @endif
</div>
@endsection
