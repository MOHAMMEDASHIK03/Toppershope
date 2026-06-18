@extends('student.layouts.app')
@section('title', $course->name . ' — Learning')
@section('page_title', $course->name)

@push('styles')
<style>
    .curriculum-item { transition: all 0.2s ease; }
    .curriculum-item:hover { background: rgba(99,102,241,0.05); }
    .curriculum-item.active { background: rgba(99,102,241,0.1); border-left: 3px solid #6366f1; }
</style>
@endpush

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('student.my-courses') }}" class="hover:text-primary-700 transition-colors">My Courses</a>
    <span>›</span>
    <span class="text-slate-600">{{ $course->name }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Left — Curriculum Tree --}}
    <div class="lg:col-span-1">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden sticky top-24">
            <div class="p-4 border-b border-slate-200">
                <h3 class="font-semibold text-slate-900 text-sm">Course Content</h3>
                <p class="text-[10px] text-slate-500 mt-1">{{ $enrollment->batch->name }}</p>
            </div>
            <div class="max-h-[60vh] overflow-y-auto">
                @foreach($course->subjects as $subject)
                    <div class="border-b border-slate-800/50 last:border-b-0">
                        <div class="px-4 py-3 bg-slate-100/30 flex justify-between items-center cursor-pointer select-none hover:bg-slate-50 transition-colors" onclick="toggleAccordion('subject-{{ $subject->id }}')">
                            <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wide">{{ $subject->name }}</h4>
                            <svg id="icon-subject-{{ $subject->id }}" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500 transition-transform duration-200" style="transform: rotate(180deg);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        <div id="subject-{{ $subject->id }}" class="block">
                            @foreach($subject->chapters as $chapter)
                                <div class="border-t border-slate-800/30">
                                    <div class="px-4 py-2 flex justify-between items-center cursor-pointer hover:bg-slate-50 select-none transition-colors" onclick="toggleAccordion('chapter-{{ $chapter->id }}')">
                                        <p class="text-xs font-bold text-slate-600">{{ $chapter->name }}</p>
                                        <svg id="icon-chapter-{{ $chapter->id }}" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" style="transform: rotate(180deg);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                    <div id="chapter-{{ $chapter->id }}" class="pb-2 px-2 block">
                                        @foreach($chapter->units as $unit)
                                            <div id="sidebar-unit-{{ $unit->id }}" class="curriculum-item px-3 py-1.5 rounded-lg ml-2 mb-0.5 cursor-pointer" onclick="showUnit({{ $unit->id }})">
                                                <p class="text-[11px] text-slate-400 font-medium">{{ $unit->name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    @if($unit->videos->count())
                                                        <span class="text-[9px] text-sky-400 font-bold">{{ $unit->videos->count() }} video{{ $unit->videos->count() > 1 ? 's' : '' }}</span>
                                                    @endif
                                                    @if($unit->notes->count())
                                                        <span class="text-[9px] text-emerald-400 font-bold">{{ $unit->notes->count() }} note{{ $unit->notes->count() > 1 ? 's' : '' }}</span>
                                                    @endif
                                                    @if($unit->quizzes->count())
                                                        <span class="text-[9px] text-amber-400 font-bold">{{ $unit->quizzes->count() }} quiz</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right — Content Area --}}
    <div class="lg:col-span-3 pb-24">
        <!-- Default State -->
        <div id="default-content" class="bg-white border border-slate-200 rounded-xl shadow-sm p-12 text-center h-[60vh] flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-primary-500/30 mb-4" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,88Zm-32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h48A8,8,0,0,1,144,136Zm32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,184Z"/></svg>
            <p class="text-slate-400 font-bold">Select a unit from the curriculum</p>
            <p class="text-slate-500 text-xs mt-1">Click on any unit in the left sidebar to view its videos, notes, and quizzes.</p>
        </div>

        @foreach($course->subjects as $subject)
            @foreach($subject->chapters as $chapter)
                @foreach($chapter->units as $unit)
                    <div id="content-unit-{{ $unit->id }}" class="unit-content hidden transition-all duration-300">
                        <div class="mb-6">
                            <div class="flex items-center gap-2 text-[10px] font-bold text-primary-700 uppercase tracking-widest mb-2">
                                <span>{{ $subject->name }}</span>
                                <span class="text-slate-600">›</span>
                                <span>{{ $chapter->name }}</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                                <span class="w-1.5 h-8 rounded-full bg-primary-500"></span>
                                {{ $unit->name }}
                            </h2>
                        </div>

                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-4 border border-primary-500/10 shadow-lg shadow-primary-500/5">
                            <h3 class="font-bold text-sm text-slate-800 mb-4 border-b border-slate-800/50 pb-3">Learning Material</h3>

                            <div class="flex flex-col gap-3">
                                {{-- Videos --}}
                                @foreach($unit->videos as $video)
                                    <a href="{{ route('student.my-courses.video', [$enrollment->id, $video->id]) }}" class="flex items-center gap-4 p-4 rounded-xl bg-primary-500/5 border border-primary-500/20 hover:border-primary-500/50 transition-all group hover:bg-primary-500/10">
                                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-sky-400" viewBox="0 0 256 256"><path d="M232.4,114.49,88.32,26.35a16,16,0,0,0-16.2-.3A15.86,15.86,0,0,0,64,40.74V215.26a15.94,15.94,0,0,0,8.12,13.9,16,16,0,0,0,16.2-.3L232.4,141.72a16,16,0,0,0,0-27.23Z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 truncate group-hover:text-sky-300">{{ $video->title }}</p>
                                                <p class="text-xs text-slate-500">Video Lesson</p>
                                            </div>
                                            @if($video->duration_minutes)
                                                <div class="text-xs font-bold text-slate-400 bg-slate-100/80 px-3 py-1.5 rounded-lg border border-slate-200">
                                                    {{ $video->duration_minutes }} min
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach

                                {{-- Notes --}}
                                @foreach($unit->notes as $note)
                                    <a href="{{ route('student.my-courses.note', [$enrollment->id, $note->id]) }}" class="flex items-center gap-4 p-4 rounded-xl bg-primary-500/5 border border-emerald-200 hover:border-primary-500/50 transition-all group hover:bg-primary-500/10">
                                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-emerald-400" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34Z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 truncate group-hover:text-emerald-300">{{ $note->title }}</p>
                                                <p class="text-xs text-slate-500">PDF Notes</p>
                                            </div>
                                            <div class="text-xs font-bold text-emerald-400/90 bg-primary-500/10 px-4 py-2 rounded-lg border border-emerald-200">
                                                View PDF
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                                {{-- Quizzes --}}
                                @foreach($unit->quizzes as $quiz)
                                    @if($quiz->is_published)
                                    <a href="{{ route('student.quiz.show', $quiz->id) }}" class="flex items-center gap-4 p-4 rounded-xl bg-amber-500/5 border border-amber-500/20 hover:border-amber-500/50 transition-all group hover:bg-amber-500/10">
                                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-amber-400" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24Zm-36,96H92a8,8,0,0,1,0-16h72a8,8,0,0,1,0,16Z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 truncate group-hover:text-amber-300">{{ $quiz->title }}</p>
                                                <p class="text-xs text-slate-500">Online Quiz</p>
                                            </div>
                                            <div class="text-xs font-bold text-amber-900 group-hover:text-white bg-amber-400 group-hover:bg-amber-500 px-5 py-2 rounded-lg transition-all shadow-lg shadow-amber-500/20">
                                                Start Quiz
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                @endforeach
                            </div>

                            @if($unit->videos->count() === 0 && $unit->notes->count() === 0 && $unit->quizzes->count() === 0)
                                <div class="p-8 text-center bg-slate-100/20 rounded-xl border border-dashed border-slate-700">
                                    <p class="text-sm text-slate-500 font-medium pb-2">No learning materials found for this unit.</p>
                                    <p class="text-xs text-slate-600">Materials will appear here once faculty uploads them.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        @endforeach

        @if($course->subjects->count() === 0)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-12 text-center h-[60vh] flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-slate-600 mb-4" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,88Zm-32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h48A8,8,0,0,1,144,136Zm32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,184Z"/></svg>
                <p class="text-slate-400 font-bold">No curriculum content available yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.classList.add('block');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            content.classList.remove('block');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    function showUnit(unitId) {
        // Hide default screen
        const defaultContent = document.getElementById('default-content');
        if (defaultContent) defaultContent.classList.add('hidden');

        // Hide all unit contents
        document.querySelectorAll('.unit-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Show the selected unit
        const selectedUnit = document.getElementById('content-unit-' + unitId);
        if (selectedUnit) {
            selectedUnit.classList.remove('hidden');
        }

        // Update active styling in sidebar
        document.querySelectorAll('.curriculum-item').forEach(el => {
            el.classList.remove('active');
        });
        const selectedSidebarItem = document.getElementById('sidebar-unit-' + unitId);
        if (selectedSidebarItem) {
            selectedSidebarItem.classList.add('active');
        }
    }
</script>
@endpush
