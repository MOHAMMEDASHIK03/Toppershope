@extends('admin.layouts.app')
@section('title', 'Quizzes: ' . $course->name)
@section('page_title', 'Quiz Manager')

@section('content')
<div x-data="{ selectedUnit: null, unitName: '' }">

    <div class="mb-6 flex items-center gap-4 flex-wrap">
        <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-orange-600 hover:text-indigo-800 transition-colors text-sm font-semibold">
            &larr; Back to course config
        </a>
        <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $course->name }}</h2>
            <p class="text-xs text-slate-500">Quiz manager — create topic-level tests, sections, and questions</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 px-5 py-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 font-semibold text-sm space-y-1">
            @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
        </div>
    @endif

    <div class="admin-panel border border-slate-200 rounded-xl overflow-hidden flex flex-col lg:flex-row" style="min-height: 75vh;">

        <div class="w-full lg:w-1/3 border-b lg:border-b-0 lg:border-r border-slate-200 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-semibold text-slate-900 text-sm">Topic tree</h3>
                <p class="text-xs text-slate-500 mt-0.5">Select a unit to manage its quizzes</p>
            </div>
            <div class="flex-1 overflow-y-auto p-3 space-y-2">
                @forelse($course->subjects as $subject)
                    <div x-data="{ open: false }" class="rounded-lg border border-slate-200 overflow-hidden bg-white">
                        <div @click="open = !open" class="flex items-center gap-2 px-3 py-2.5 cursor-pointer hover:bg-slate-50 transition-colors">
                            <span class="text-slate-400 text-xs transition-transform" :class="open ? 'rotate-90' : ''">&#9654;</span>
                            <span class="font-semibold text-slate-800 text-sm">{{ $subject->name }}</span>
                        </div>
                        <div x-show="open" class="border-t border-slate-100">
                            @forelse($subject->chapters as $chapter)
                                <div x-data="{ chapOpen: false }" class="border-b border-slate-100 last:border-b-0">
                                    <div @click="chapOpen = !chapOpen" class="flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-slate-50 transition-colors">
                                        <span class="text-slate-400 text-xs transition-transform" :class="chapOpen ? 'rotate-90' : ''">&#9654;</span>
                                        <span class="text-slate-600 text-xs font-medium">{{ $chapter->name }}</span>
                                    </div>
                                    <div x-show="chapOpen" class="pl-6 pb-2 space-y-1">
                                        @forelse($chapter->units as $unit)
                                            <div @click="selectedUnit = {{ $unit->id }}; unitName = '{{ addslashes($unit->name) }}'"
                                                 :class="selectedUnit == {{ $unit->id }} ? 'bg-orange-50 text-indigo-700 border-indigo-200' : 'text-slate-600 border-transparent hover:bg-slate-50'"
                                                 class="flex items-center justify-between px-3 py-1.5 rounded-lg cursor-pointer text-xs border transition-all">
                                                <span>{{ $unit->name }}</span>
                                                <span class="text-[10px] font-bold opacity-60">{{ $unit->quizzes->count() }}Q</span>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic px-3 py-1">No units</p>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic px-4 py-2">No chapters</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 text-sm">
                        <p class="font-semibold">No curriculum built yet</p>
                        <p class="text-xs mt-1">Build subjects and units in the course configurator first.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="w-full lg:w-2/3 flex flex-col overflow-hidden bg-white">

            <div x-show="!selectedUnit" class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-2xl mb-4">📋</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Select a topic</h3>
                <p class="text-slate-500 text-sm max-w-xs">Pick a unit from the curriculum tree to view and create quizzes.</p>
            </div>

            <div x-show="selectedUnit" x-cloak class="flex flex-col h-full">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 shrink-0">
                    <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide mb-0.5">Managing quizzes for</p>
                    <h4 class="text-lg font-bold text-slate-900" x-text="unitName"></h4>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                        <h4 class="text-sm font-semibold text-slate-900 mb-4">Create new quiz</h4>
                        <form :action="`/admin/courses/{{ $course->id }}/units/${selectedUnit}/quizzes`" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-3 mb-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Quiz title *</label>
                                    <input type="text" name="title" required placeholder="e.g. Kinematics final review" class="admin-input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Instructions</label>
                                    <textarea name="description" rows="2" placeholder="Brief instructions…" class="admin-input text-sm resize-none"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary text-sm py-2">Create quiz</button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Topic quizzes</h4>

                        @foreach($course->subjects as $subject)
                            @foreach($subject->chapters as $chapter)
                                @foreach($chapter->units as $unit)
                                    @foreach($unit->quizzes as $quiz)
                                        <div x-show="selectedUnit == {{ $unit->id }}" x-cloak
                                             class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col sm:flex-row gap-5 shadow-sm">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-1.5 flex-wrap">
                                                    <h5 class="font-semibold text-slate-900">{{ $quiz->title }}</h5>
                                                    @if($quiz->is_published)
                                                        <span class="badge-active">Published</span>
                                                    @else
                                                        <span class="badge-inactive">Draft</span>
                                                    @endif
                                                </div>
                                                <p class="text-slate-500 text-xs mb-3">{{ $quiz->description ?? 'No description.' }}</p>
                                                <div class="flex gap-2 text-[11px] font-semibold text-slate-500">
                                                    <span class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-lg">{{ $quiz->sections->count() }} sections</span>
                                                    <span class="bg-slate-100 border border-slate-200 px-2 py-1 rounded-lg">{{ $quiz->sections->flatMap->questions->count() }} questions</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-row sm:flex-col gap-2 shrink-0">
                                                <a href="{{ route('admin.courses.quizzes.builder', [$course->id, $quiz->id]) }}" class="btn-primary text-xs py-2 text-center">
                                                    Build &amp; edit
                                                </a>
                                                <form action="{{ route('admin.courses.quizzes.publish', [$course->id, $quiz->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-secondary text-xs py-2">
                                                        {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.courses.quizzes.destroy', [$course->id, $quiz->id]) }}" method="POST" onsubmit="return confirm('Delete this quiz permanently?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-full px-4 py-2 text-rose-700 bg-rose-50 border border-rose-200 font-semibold text-xs rounded-lg hover:bg-rose-100 transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
