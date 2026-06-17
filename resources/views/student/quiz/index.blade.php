@extends('student.layouts.app')
@section('title', 'Test Series')
@section('page_title', 'Test Series')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Available Tests</h2>
    <p class="text-slate-500 text-sm mt-1">Take quizzes from your enrolled courses to test your preparation.</p>
</div>

{{-- Advanced Filters & Search --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-8">
    <form action="{{ route('student.quizzes') }}" method="GET" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-4">
            {{-- Search Title --}}
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Search Title</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search tests..."
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-500 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <button type="submit" class="px-5 py-2.5 btn-primary text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-500/10">Search</button>
                </div>
            </div>

            {{-- Course/Batch/Subject/Chapter/Unit Filters --}}
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Course</label>
                <select name="course_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 appearance-none cursor-pointer" onchange="resetDownstream('course'); document.getElementById('filterForm').submit();">
                    <option value="">All Courses</option>
                    @foreach($filterCourses as $fc)
                        <option value="{{ $fc->id }}" {{ $courseId == $fc->id ? 'selected' : '' }}>{{ $fc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Batch</label>
                <select name="batch_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 appearance-none cursor-pointer" onchange="document.getElementById('filterForm').submit();" {{ !$courseId ? 'disabled' : '' }}>
                    <option value="">All Batches</option>
                    @foreach($filterBatches as $fb)
                        <option value="{{ $fb->id }}" {{ $batchId == $fb->id ? 'selected' : '' }}>{{ $fb->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Subject</label>
                <select name="subject_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 appearance-none cursor-pointer" onchange="resetDownstream('subject'); document.getElementById('filterForm').submit();" {{ !$courseId ? 'disabled' : '' }}>
                    <option value="">All Subjects</option>
                    @foreach($filterSubjects as $fs)
                        <option value="{{ $fs->id }}" {{ $subjectId == $fs->id ? 'selected' : '' }}>{{ $fs->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1.5">Chapter / Unit</label>
                <select name="chapter_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 appearance-none cursor-pointer mb-2" onchange="resetDownstream('chapter'); document.getElementById('filterForm').submit();" {{ !$subjectId ? 'disabled' : '' }}>
                    <option value="">All Chapters</option>
                    @foreach($filterChapters as $fch)
                        <option value="{{ $fch->id }}" {{ $chapterId == $fch->id ? 'selected' : '' }}>{{ $fch->name }}</option>
                    @endforeach
                </select>
                @if($chapterId)
                    <select name="unit_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 appearance-none cursor-pointer mt-2" onchange="document.getElementById('filterForm').submit();">
                        <option value="">All Units</option>
                        @foreach($filterUnits as $fu)
                            <option value="{{ $fu->id }}" {{ $unitId == $fu->id ? 'selected' : '' }}>{{ $fu->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>
        
        {{-- Selected Filters Tags --}}
        @if($courseId || $batchId || $subjectId || $chapterId || $unitId || $search)
            <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-slate-200">
                <span class="text-xs text-slate-500">Active Filters:</span>
                @if($search) <span class="px-2 py-1 bg-orange-50 text-indigo-700 text-[10px] font-bold rounded border border-indigo-500/20">Title: {{ $search }}</span> @endif
                @if($courseId) <span class="px-2 py-1 bg-orange-50 text-indigo-700 text-[10px] font-bold rounded border border-indigo-500/20">Course Selected</span> @endif
                @if($batchId) <span class="px-2 py-1 bg-purple-500/10 text-purple-400 text-[10px] font-bold rounded border border-purple-500/20">Batch Selected</span> @endif
                @if($subjectId) <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-200">Subject Selected</span> @endif
                @if($chapterId) <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-200">Chapter Selected</span> @endif
                @if($unitId) <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-200">Unit Selected</span> @endif
                <a href="{{ route('student.quizzes') }}" class="text-xs text-rose-400 hover:text-rose-300 ml-2">Clear All</a>
            </div>
        @endif
    </form>
</div>

<script>
function resetDownstream(level) {
    if (level === 'course') {
        document.querySelector('select[name="batch_id"]').value = '';
        document.querySelector('select[name="subject_id"]').value = '';
        document.querySelector('select[name="chapter_id"]').value = '';
        if(document.querySelector('select[name="unit_id"]')) document.querySelector('select[name="unit_id"]').value = '';
    } else if (level === 'subject') {
        document.querySelector('select[name="chapter_id"]').value = '';
        if(document.querySelector('select[name="unit_id"]')) document.querySelector('select[name="unit_id"]').value = '';
    } else if (level === 'chapter') {
        if(document.querySelector('select[name="unit_id"]')) document.querySelector('select[name="unit_id"]').value = '';
    }
}
</script>

@if($quizzes->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($quizzes as $quiz)
            @php
                $attemptData = $attemptCounts[$quiz->id] ?? null;
                $totalAttempts = $attemptData->total ?? 0;
                $bestScore = $attemptData->best_score ?? 0;
                $totalMarks = $quiz->sections->sum(fn($s) => $s->marks_per_question * $s->questions->count());
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 hover:border-orange-200 transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-slate-900 text-sm">{{ $quiz->title }}</h3>
                        @if($quiz->unit && $quiz->unit->chapter && $quiz->unit->chapter->subject && $quiz->unit->chapter->subject->course)
                            <p class="text-[10px] text-slate-500 mt-1">{{ $quiz->unit->chapter->subject->course->name }}</p>
                        @endif
                    </div>
                    @if($totalAttempts > 0)
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold">{{ $totalAttempts }} attempt{{ $totalAttempts > 1 ? 's' : '' }}</span>
                    @else
                        <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-400 text-[10px] font-bold">Not Attempted</span>
                    @endif
                </div>

                @if($quiz->description)
                    <p class="text-xs text-slate-400 mb-3 line-clamp-2">{{ $quiz->description }}</p>
                @endif

                <div class="flex items-center gap-3 text-[10px] text-slate-500 mb-2">
                    <span>{{ $quiz->sections->count() }} section{{ $quiz->sections->count() > 1 ? 's' : '' }}</span>
                    <span>•</span>
                    <span>{{ $quiz->sections->sum(fn($s) => $s->questions->count()) }} questions</span>
                    @php $totalTime = $quiz->sections->sum('time_limit_minutes'); @endphp
                    @if($totalTime > 0)
                        <span>•</span>
                        <span>{{ $totalTime }} min</span>
                    @endif
                </div>

                @if($totalAttempts > 0 && $totalMarks > 0)
                    <div class="mb-3">
                        <div class="flex justify-between text-[10px] text-slate-500 mb-1">
                            <span>Best Score</span>
                            <span class="text-emerald-400 font-bold">{{ max(0, $bestScore) }}/{{ $totalMarks }}</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500" style="width: {{ min(100, max(0, $bestScore) / $totalMarks * 100) }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('student.quiz.show', $quiz->id) }}" class="flex-1 text-center px-4 py-2.5 rounded-xl btn-primary text-white text-xs font-bold  transition-all shadow-lg shadow-orange-500/10">
                        {{ $totalAttempts > 0 ? 'Retake Quiz' : 'Start Quiz' }}
                    </a>
                    @if($totalAttempts > 0)
                        <a href="{{ route('student.quiz.show', $quiz->id) }}#attempts" class="px-4 py-2.5 rounded-xl bg-slate-100 text-orange-600 text-xs font-bold hover:bg-slate-200 transition-all">
                            History
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-16 text-center">
        <div class="w-20 h-20 rounded-2xl bg-amber-500/10 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="text-amber-400" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24Z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-900 mb-2">No Tests Available</h3>
        <p class="text-slate-500 text-sm">Tests from your enrolled courses will appear here once published.</p>
    </div>
@endif
@endsection
