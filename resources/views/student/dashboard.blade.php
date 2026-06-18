@extends('student.layouts.app')
@section('title', 'Dashboard')
@section('page_title', 'Welcome Back, ' . auth()->user()->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <x-admin.stat-card label="Enrolled Courses" :value="$totalCourses" hint="Active enrollments" accent="indigo" />
    <x-admin.stat-card label="Quizzes Taken" :value="$totalQuizzes" hint="Tests completed" accent="emerald" />
    <x-admin.stat-card label="Average Score" :value="number_format($avgScore, 1)" hint="Across all tests" accent="amber" />
</div>

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-slate-900">Continue Learning</h2>
        <a href="{{ route('student.my-courses') }}" class="text-primary-700 text-sm font-semibold hover:text-primary-700 transition-colors">View All →</a>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($enrollments->take(6) as $enrollment)
                <a href="{{ route('student.my-courses.show', $enrollment->id) }}" class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:border-primary-200 hover:shadow-md transition-all group block shadow-sm">
                    <div class="h-36 bg-gradient-to-br from-primary-100 to-primary-100 flex items-center justify-center relative overflow-hidden">
                        @if($enrollment->batch->course->thumbnail)
                            <img src="{{ asset('storage/' . $enrollment->batch->course->thumbnail) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="text-4xl font-bold text-primary-300">{{ strtoupper(substr($enrollment->batch->course->name, 0, 2)) }}</div>
                        @endif
                        <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-slate-900/70 text-[10px] font-semibold text-white">
                            {{ $enrollment->batch->name }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-900 text-sm mb-1 group-hover:text-primary-700 transition-colors">{{ $enrollment->batch->course->name }}</h3>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            @if($enrollment->batch->course->category)
                                <span class="px-2 py-0.5 rounded bg-primary-50 text-primary-700 font-semibold">{{ strtoupper($enrollment->batch->course->category->name) }}</span>
                            @endif
                            @if($enrollment->batch->mentor_name)
                                <span>{{ $enrollment->batch->mentor_name }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <span class="px-2.5 py-1 rounded-md bg-primary-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200">Active</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <x-admin.empty-state title="No courses yet" description="Browse our catalog and enroll in your first course to start learning.">
            <x-slot:action>
                <a href="{{ route('student.catalog') }}" class="btn-primary mt-4 inline-flex">Browse Courses →</a>
            </x-slot:action>
        </x-admin.empty-state>
    @endif
</div>

@if($recentQuizzes->count() > 0)
<div>
    <h2 class="text-lg font-bold text-slate-900 mb-4">Recent Test Results</h2>
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentQuizzes as $attempt)
                    <tr>
                        <td class="font-semibold">{{ $attempt->quiz->title ?? 'Quiz' }}</td>
                        <td>
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-50 text-emerald-700 border border-emerald-200">
                                {{ $attempt->score }} pts
                            </span>
                        </td>
                        <td class="text-slate-500">{{ $attempt->created_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <a href="{{ route('student.quiz.results', $attempt->quiz_id) }}" class="text-primary-700 text-xs font-semibold hover:text-primary-700">View Results →</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
