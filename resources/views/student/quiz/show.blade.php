@extends('student.layouts.app')
@section('title', $quiz->title . ' — Exam')
@section('page_title', $quiz->title)

@section('content')
@php $totalTime = $quiz->sections->sum('time_limit_minutes') ?: 60; @endphp

<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 text-center">
        <div class="w-20 h-20 rounded-2xl bg-orange-500/10 flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-orange-600" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24Zm-36,96H92a8,8,0,0,1,0-16h72a8,8,0,0,1,0,16Zm0-32H92a8,8,0,0,1,0-16h72a8,8,0,0,1,0,16Z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $quiz->title }}</h1>
        @if($quiz->description)
            <p class="text-slate-500 text-sm mb-6">{{ $quiz->description }}</p>
        @endif

        {{-- Quiz Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-lg font-black text-orange-600">{{ $quiz->sections->count() }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase">Sections</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-lg font-black text-amber-400">{{ $quiz->sections->sum(fn($s) => $s->questions->count()) }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase">Questions</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-lg font-black text-emerald-400">{{ $totalTime }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase">Minutes</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                @php $totalMarks = $quiz->sections->sum(fn($s) => $s->marks_per_question * $s->questions->count()); @endphp
                <p class="text-lg font-black text-rose-400">{{ $totalMarks }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase">Total Marks</p>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-left mb-8">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">📋 Exam Instructions</h3>
            <ul class="space-y-2 text-xs text-slate-600">
                <li class="flex items-start gap-2"><span class="text-orange-600 mt-0.5">●</span> The quiz will open in <strong class="text-slate-900">fullscreen mode</strong>. Do NOT exit fullscreen or switch tabs.</li>
                <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">●</span> If you <strong class="text-rose-400">switch tabs, minimize, or exit fullscreen</strong>, the quiz will <strong class="text-rose-400">automatically end</strong>.</li>
                <li class="flex items-start gap-2"><span class="text-amber-400 mt-0.5">●</span> Each section has its own time limit. Total time: <strong class="text-slate-900">{{ $totalTime }} minutes</strong>.</li>
                <li class="flex items-start gap-2"><span class="text-emerald-400 mt-0.5">●</span> Only <strong class="text-slate-900">one question</strong> is shown at a time. Use navigation to jump between questions.</li>
                <li class="flex items-start gap-2"><span class="text-orange-600 mt-0.5">●</span> Click <strong class="text-slate-900">Save & Next</strong> to save and move forward. Use <strong class="text-slate-900">Clear Response</strong> to deselect.</li>
                <li class="flex items-start gap-2"><span class="text-amber-400 mt-0.5">●</span> You can <strong class="text-slate-900">Mark for Review</strong> to revisit a question later.</li>
                @foreach($quiz->sections as $section)
                    <li class="flex items-start gap-2"><span class="text-slate-500 mt-0.5">▸</span> <strong class="text-slate-900">{{ $section->name }}:</strong> {{ $section->questions->count() }} questions, +{{ $section->marks_per_question }} per correct, −{{ $section->negative_marks_per_question }} per wrong</li>
                @endforeach
                <li class="flex items-start gap-2"><span class="text-emerald-400 mt-0.5">●</span> You can <strong class="text-slate-900">attempt this quiz multiple times</strong> to improve your score.</li>
                <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">●</span> <strong class="text-rose-400">No copy, select, right-click, or screenshots</strong> allowed during the exam.</li>
            </ul>
        </div>

        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('student.quizzes') }}" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-sm hover:bg-slate-200 transition-all">← Go Back</a>
            <a href="{{ route('student.quiz.attempt', $quiz->id) }}" target="_blank"
               class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm hover:from-emerald-500 hover:to-teal-500 transition-all shadow-lg shadow-emerald-500/20 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M232.4,114.49,88.32,26.35a16,16,0,0,0-16.2-.3A15.86,15.86,0,0,0,64,40.74V215.26a15.94,15.94,0,0,0,8.12,13.9,16,16,0,0,0,16.2-.3L232.4,141.72a16,16,0,0,0,0-27.23Z"/></svg>
                Start Exam
            </a>
        </div>
    </div>

    {{-- Attempt History --}}
    @if($attempts->count() > 0)
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mt-6" id="attempts">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">📊 Your Attempt History</h3>
            <div class="space-y-3">
                @foreach($attempts as $idx => $attempt)
                    @php
                        $attemptMaxMarks = $totalMarks;
                        $pct = $attemptMaxMarks > 0 ? round(max(0, $attempt->score) / $attemptMaxMarks * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-100/40 border border-slate-700/30 hover:border-orange-200 transition-all">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $pct >= 70 ? 'bg-emerald-500/15 text-emerald-400' : ($pct >= 40 ? 'bg-amber-500/15 text-amber-400' : 'bg-rose-500/15 text-rose-400') }}">
                            #{{ $attempts->count() - $idx }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-slate-900">{{ max(0, $attempt->score) }}/{{ $attemptMaxMarks }}</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-md
                                    {{ $pct >= 70 ? 'bg-emerald-50 text-emerald-700' : ($pct >= 40 ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400') }}">
                                    {{ $pct }}%
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-500">{{ $attempt->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="w-24 h-1.5 rounded-full bg-slate-700">
                            <div class="h-full rounded-full {{ $pct >= 70 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width:{{ $pct }}%"></div>
                        </div>
                        <a href="{{ route('student.quiz.results', ['quiz' => $quiz->id, 'attempt' => $attempt->id]) }}"
                           class="px-3 py-2 rounded-lg bg-orange-50 text-indigo-700 text-xs font-bold hover:bg-orange-500/20 transition-all">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
