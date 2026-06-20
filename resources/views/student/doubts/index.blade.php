@extends('student.layouts.app')
@section('title', 'Doubt Forum')
@section('page_title', 'Doubt Forum')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-zinc-100">Doubts & Q&A</h2>
        <p class="text-slate-500 dark:text-zinc-400 text-sm mt-1">Ask questions and get answers from your faculty.</p>
    </div>
    <button type="button" onclick="document.getElementById('newDoubtForm').classList.toggle('hidden')" class="btn-primary text-sm py-2 px-4 rounded-lg font-semibold">
        <i class="ph ph-plus"></i> Ask doubt
    </button>
</div>

{{-- New Doubt Form --}}
<div id="newDoubtForm" class="bg-white dark:bg-[#18181c] border border-slate-200 dark:border-[#2a2a32] rounded-xl shadow-sm p-6 mb-6 {{ $errors->any() || old('title') ? '' : 'hidden' }}">
    <form action="{{ route('student.doubts.store') }}" method="POST">
        @csrf
        <h3 class="text-sm font-semibold text-slate-900 dark:text-zinc-100 mb-4">Post a New Doubt</h3>

        @if(empty($courseOptions))
            <p class="text-sm text-slate-500 dark:text-zinc-400 mb-4">No courses are available yet. Browse the <a href="{{ route('student.catalog') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">course catalog</a> to get started.</p>
        @else
            @unless($hasEnrollments)
                <p class="text-sm text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-lg px-3 py-2 mb-4">
                    You are not enrolled in a batch yet. Select the course your doubt relates to, or enroll from
                    <a href="{{ route('student.my-courses') }}" class="font-semibold underline">My Courses</a>.
                </p>
            @endunless

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wide mb-2">Course</label>
                    <select name="course_id" required class="admin-input w-full rounded-xl text-sm">
                        <option value="">Select a course</option>
                        @foreach($courseOptions as $option)
                            <option value="{{ $option['course_id'] }}" @selected(old('course_id') == $option['course_id'])>
                                {{ $option['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wide mb-2">Subject / Title</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Doubt in Integration by Parts"
                        class="w-full px-4 py-3 admin-input rounded-xl text-sm outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-500">
                    @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wide mb-2">Your Question</label>
                    <textarea name="body" rows="4" required placeholder="Describe your doubt in detail..."
                        class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-500">{{ old('body') }}</textarea>
                    @error('body') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary text-sm py-2.5 px-5 rounded-lg font-semibold">Post doubt</button>
                    <button type="button" onclick="document.getElementById('newDoubtForm').classList.add('hidden')" class="btn-secondary text-sm py-2.5 px-5 rounded-lg">Cancel</button>
                </div>
            </div>
        @endif
    </form>
</div>

{{-- Doubts List --}}
@if(isset($doubts) && $doubts->count() > 0)
    <div class="space-y-4">
        @foreach($doubts as $doubt)
            <a href="{{ route('student.doubts.show', $doubt->id) }}" class="bg-white dark:bg-[#18181c] border border-slate-200 dark:border-[#2a2a32] rounded-xl shadow-sm p-5 block hover:border-primary-200 dark:hover:border-primary-500/40 transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 dark:text-zinc-100 text-sm">{{ $doubt->subject }}</h3>
                        @if($doubt->course)
                            <p class="text-[10px] font-semibold text-primary-600 dark:text-primary-400 mt-0.5">{{ $doubt->course->name }}</p>
                        @endif
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1 line-clamp-2">{{ $doubt->description }}</p>
                        <div class="flex items-center gap-3 mt-3 text-[10px] text-slate-500 dark:text-zinc-500">
                            <span>{{ $doubt->created_at->diffForHumans() }}</span>
                            @if($doubt->replies_count > 0)
                                <span>{{ $doubt->replies_count }} {{ Str::plural('reply', $doubt->replies_count) }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $doubt->is_resolved ? 'bg-primary-50 dark:bg-primary-500/10 text-emerald-700 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                        {{ $doubt->is_resolved ? 'Resolved' : 'Open' }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="bg-white dark:bg-[#18181c] border border-slate-200 dark:border-[#2a2a32] rounded-xl shadow-sm p-12 text-center">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-zinc-100 mb-2">No Doubts Yet</h3>
        <p class="text-slate-500 dark:text-zinc-400 text-sm">Click "+ Ask doubt" to post your first question.</p>
    </div>
@endif
@endsection
