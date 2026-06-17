@extends('layouts.faculty')

@section('title', 'Quizzes: ' . $course->name)
@section('page_header')
    <div class="flex items-center gap-3">
        <a href="{{ route('faculty.dashboard') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
        </a>
        <span>Manage: <span class="text-primary">{{ $course->name }}</span></span>
    </div>
@endsection

@section('content')

<!-- Navbar specific to the Course -->
<div class="mb-8 border-b border-slate-200">
    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('faculty.courses.curriculum', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Curriculum Builder
        </a>
        <a href="{{ route('faculty.courses.content.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Content Manager
        </a>
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
            Quizzes
        </a>
        <a href="{{ route('faculty.courses.doubts.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            Doubts 
            @php $unresolvedCount = $course->doubts()->where('is_resolved', false)->count(); @endphp
            @if($unresolvedCount > 0)
                <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow-sm shadow-red-500/30">{{ $unresolvedCount }}</span>
            @endif
        </a>
        <a href="{{ route('faculty.courses.students.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Students
        </a>
        <a href="{{ route('faculty.courses.results.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Results
        </a>
    </nav>
</div>

<!-- Quizzes Manager -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8" x-data="{ 
    selectedUnit: null, 
    unitName: '',
    
    selectUnit(id, name) {
        this.selectedUnit = id;
        this.unitName = name;
    }
}">
    <div class="flex h-[750px]"> <!-- Slightly taller to accommodate quiz list -->
        
        <!-- Left Sidebar: Curriculum Tree -->
        <div class="w-1/3 border-r border-slate-200 bg-slate-50 flex flex-col h-full overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-white">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-bold ph-list-numbers text-primary"></i> Topics Content
                </h3>
                <p class="text-xs text-slate-500 mt-1">Select a topic unit to manage its quizzes.</p>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @forelse($course->subjects as $subject)
                    <div x-data="{ expanded: false }" class="bg-white border text-sm border-slate-200 rounded-lg shadow-sm">
                        
                        <div @click="expanded = !expanded" class="px-3 py-2 border-b border-slate-100 flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <i class="ph-bold ph-caret-right text-slate-400 transition-transform" :class="expanded ? 'rotate-90' : ''"></i>
                            <span class="font-bold text-slate-700">{{ $subject->name }}</span>
                        </div>

                        <div x-show="expanded" x-collapse>
                            @forelse($subject->chapters as $chapter)
                                <div x-data="{ chapExpanded: false }" class="border-l border-slate-100 ml-3">
                                    <div @click="chapExpanded = !chapExpanded" class="px-3 py-1.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                                        <i class="ph-bold ph-caret-right text-slate-300 text-xs transition-transform" :class="chapExpanded ? 'rotate-90' : ''"></i>
                                        <span class="font-semibold text-slate-600 text-[13px]">{{ $chapter->name }}</span>
                                    </div>

                                    <div x-show="chapExpanded" x-collapse class="pl-7 pb-2 space-y-1">
                                        @forelse($chapter->units as $unit)
                                            <div @click="selectUnit({{ $unit->id }}, '{{ addslashes($unit->name) }}')"
                                                 class="px-2 py-1.5 rounded cursor-pointer text-xs flex items-center justify-between transition-colors m-1"
                                                 :class="selectedUnit == {{ $unit->id }} ? 'bg-primary/10 text-primary font-bold' : 'text-slate-500 hover:bg-slate-100'">
                                                <span class="flex items-center gap-1.5">
                                                    <div class="w-1.5 h-1.5 rounded-full" :class="selectedUnit == {{ $unit->id }} ? 'bg-primary' : 'bg-slate-300'"></div>
                                                    {{ $unit->name }}
                                                </span>
                                                <div class="flex gap-1 text-[10px]">
                                                    <span class="bg-orange-50 text-orange-600 px-1 rounded flex items-center font-semibold"><i class="ph-fill ph-exam mr-0.5"></i> {{ $unit->quizzes->count() }} Quizzes</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-[11px] text-slate-400 px-2 py-1 italic">No units in chapter</div>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <div class="text-[11px] text-slate-400 px-3 py-2 italic ml-4">No chapters yet.</div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm italic">
                        No subjects found. Build your curriculum first.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side: Content Area -->
        <div class="w-2/3 bg-white flex flex-col h-full">
            
            <!-- Empty State -->
            <div x-show="!selectedUnit" class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4 border border-slate-100 shadow-sm">
                    <i class="ph-fill ph-cursor-click"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Select a Topic</h3>
                <p class="text-slate-500 max-w-sm">Choose a unit on the left to start building or managing quizzes.</p>
            </div>

            <!-- Quiz Display -->
            <div x-show="selectedUnit" class="flex flex-col h-full hidden" :class="{ 'hidden': !selectedUnit }">
                
                <!-- Topic Header -->
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-white z-10">
                    <div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wider mb-1 block">Managing Topic Quizzes</span>
                        <h2 class="text-xl font-bold text-slate-900" x-text="unitName"></h2>
                    </div>
                </div>

                <!-- Forms and Display Area -->
                <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6">

                    <!-- SUCCESS/ERROR MESSAGES -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
                            <i class="ph-fill ph-x-circle text-xl mt-0.5"></i>
                            <ul class="list-disc list-inside text-sm font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- CREATE QUIZ SECTION -->
                    <div class="bg-white border border-primary/20 shadow-sm rounded-xl p-5 mb-8">
                        <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-plus-circle text-primary"></i> Create New Quiz
                        </h4>
                        
                        <form :action="`/faculty/course/{{ $course->id }}/units/${selectedUnit}/quizzes`" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Quiz Title</label>
                                    <input type="text" name="title" required placeholder="e.g. Kinematics Final Review Test" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Description / Instructions</label>
                                    <textarea name="description" rows="2" placeholder="Brief instructions for the students..." class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors resize-none"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-lg shadow-sm shadow-orange-500/20 transition-all text-sm">Create Quiz</button>
                            </div>
                        </form>
                    </div>

                    <!-- EXISTING QUIZZES -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-slate-600 text-xs uppercase tracking-wider pl-1 font-semibold flex items-center gap-2">
                            <i class="ph-bold ph-exam"></i> Topic Quizzes
                        </h4>
                        
                        @foreach($course->subjects as $subject)
                            @foreach($subject->chapters as $chapter)
                                @foreach($chapter->units as $unitLoop)
                                    @foreach($unitLoop->quizzes as $quiz)
                                        <div x-show="selectedUnit == {{ $unitLoop->id }}" class="bg-white border text-sm border-slate-200 rounded-xl p-5 flex flex-col sm:flex-row gap-5 hidden relative group shadow-sm hover:shadow transition-shadow" :class="{ 'hidden': selectedUnit != {{ $unitLoop->id }} }">
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-1">
                                                    <h5 class="font-bold text-lg text-slate-800">{{ $quiz->title }}</h5>
                                                    @if($quiz->is_published)
                                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Published</span>
                                                    @else
                                                        <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Draft</span>
                                                    @endif
                                                </div>
                                                <p class="text-slate-500 mb-4 line-clamp-2">{{ $quiz->description ?? 'No description.' }}</p>
                                                
                                                <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
                                                    <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="ph-fill ph-squares-four text-indigo-400"></i> {{ $quiz->sections->count() }} Sections</span>
                                                    <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="ph-fill ph-question text-orange-400"></i> {{ $quiz->sections->flatMap->questions->count() }} Questions</span>
                                                </div>
                                            </div>

                                            <div class="flex flex-row sm:flex-col justify-end sm:justify-center items-center gap-2 border-t sm:border-t-0 sm:border-l border-slate-100 pt-4 sm:pt-0 sm:pl-5">
                                                <a href="{{ route('faculty.courses.quizzes.builder', [$course->id, $quiz->id]) }}" class="bg-orange-50 text-indigo-700 hover:bg-orange-100 font-bold px-4 py-2 rounded-lg text-xs w-full text-center transition-colors flex items-center justify-center gap-1.5 shadow-sm shadow-indigo-100/50">
                                                    <i class="ph-bold ph-pencil-simple"></i> Build & Edit
                                                </a>
                                                <form action="{{ route('faculty.courses.quizzes.destroy', [$course->id, $quiz->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this quiz and all its questions?');" class="w-full">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:bg-red-50 font-bold px-4 py-2 rounded-lg text-xs w-full text-center transition-colors flex items-center justify-center gap-1.5">
                                                        <i class="ph-bold ph-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        
                        <div class="text-center py-8 bg-slate-50/50 border border-dashed border-slate-200 rounded-xl text-slate-400 text-sm italic">
                            Select a topic and create a quiz to see it here.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
