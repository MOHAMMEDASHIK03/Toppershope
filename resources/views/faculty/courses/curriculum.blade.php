@extends('layouts.faculty')

@section('title', 'Manage ' . $course->name)
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
        <a href="#" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
            Curriculum Builder
        </a>
        <a href="{{ route('faculty.courses.content.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Content Manager
        </a>
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
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

<!-- Curriculum Builder -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Course Structure</h3>
            <p class="text-sm text-slate-500 mt-1">Organize your course into Subjects, Chapters, and Units.</p>
        </div>
        
        <!-- Add Subject Form -->
        <form action="{{ route('faculty.courses.subjects.store', $course->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="text" name="name" placeholder="New Subject Name" required class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
            <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm shadow-primary-500/20 transition-all text-sm flex items-center">
                <i class="ph-bold ph-plus mr-1.5"></i> Add Subject
            </button>
        </form>
    </div>

    @if($course->subjects->isEmpty())
        <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
            <i class="ph-bold ph-books text-4xl text-slate-300 mb-3"></i>
            <h4 class="font-bold text-slate-700">No Subjects Yet</h4>
            <p class="text-sm text-slate-500">Add your first subject to start building the curriculum.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($course->subjects as $subject)
                <!-- Subject Card -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white" x-data="{ open: true }">
                    <div class="bg-slate-50 px-5 py-4 flex items-center justify-between border-b border-slate-200 cursor-pointer select-none" @click="open = !open">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-caret-down transition-transform duration-200 text-slate-400" :class="open ? 'rotate-0' : '-rotate-90'"></i>
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-6 h-6 rounded bg-primary/10 text-primary flex items-center justify-center text-xs">S</span>
                                {{ $subject->name }}
                            </h4>
                            <span class="text-xs text-slate-400 font-medium pl-2">{{ $subject->chapters->count() }} Chapters</span>
                        </div>
                        
                        <div class="flex items-center gap-3" @click.stop>
                            <!-- Delete Subject -->
                            <form action="{{ route('faculty.courses.subjects.destroy', [$course->id, $subject->id]) }}" method="POST" onsubmit="return confirm('Are you sure? This deletes ALL chapters and units inside this subject!');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1"><i class="ph-bold ph-trash"></i></button>
                            </form>
                        </div>
                    </div>

                    <div x-show="open" x-collapse class="p-5 bg-white">
                        <!-- Add Chapter Form -->
                        <div class="mb-4">
                            <form action="{{ route('faculty.courses.chapters.store', [$course->id, $subject->id]) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="name" placeholder="New Chapter Name" required class="text-sm bg-white border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none w-64">
                                <button type="submit" class="bg-primary-50 hover:bg-primary-100 text-primary-700 font-bold py-1.5 px-3 rounded-lg border border-primary-200 transition-colors text-xs flex items-center">
                                    <i class="ph-bold ph-plus mr-1"></i> Add Chapter
                                </button>
                            </form>
                        </div>

                        @if($subject->chapters->isEmpty())
                            <p class="text-sm text-slate-400 italic py-2 pl-4">No chapters created in this subject yet.</p>
                        @else
                            <div class="space-y-3 pl-4 border-l-2 border-slate-100 ml-3">
                                @foreach($subject->chapters as $chapter)
                                    <!-- Chapter -->
                                    <div class="border border-slate-200 rounded-lg overflow-hidden" x-data="{ chapOpen: true }">
                                        <div class="bg-white px-4 py-3 flex items-center justify-between border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors" @click="chapOpen = !chapOpen">
                                            <div class="flex items-center gap-3">
                                                <i class="ph-bold ph-caret-down transition-transform duration-200 text-slate-300 text-sm" :class="chapOpen ? 'rotate-0' : '-rotate-90'"></i>
                                                <h5 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                                                    <span class="w-5 h-5 rounded bg-primary-100 text-primary-700 flex items-center justify-center text-[10px]">C</span>
                                                    {{ $chapter->name }}
                                                </h5>
                                                <span class="text-[10px] text-slate-400 font-medium pl-2">{{ $chapter->units->count() }} Units</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 text-sm" @click.stop>
                                                <!-- Delete Chapter -->
                                                <form action="{{ route('faculty.courses.chapters.destroy', [$course->id, $subject->id, $chapter->id]) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1"><i class="ph-bold ph-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                        <div x-show="chapOpen" x-collapse class="p-4 bg-slate-50/50">
                                            <!-- Add Unit Form -->
                                            <div class="mb-3">
                                                <form action="{{ route('faculty.courses.units.store', [$course->id, $subject->id, $chapter->id]) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="text" name="name" placeholder="New Unit (Topic) Name" required class="text-xs bg-white border border-slate-300 rounded px-2 py-1 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none w-56">
                                                    <button type="submit" class="bg-primary-50 hover:bg-emerald-100 text-primary-600 font-bold py-1 px-2 rounded border border-emerald-200 transition-colors text-[10px] flex items-center">
                                                        <i class="ph-bold ph-plus mr-1"></i> Add Unit
                                                    </button>
                                                </form>
                                            </div>

                                            @if($chapter->units->isEmpty())
                                                <p class="text-xs text-slate-400 italic py-1 pl-2">No units created yet.</p>
                                            @else
                                                <ul class="space-y-1.5 pl-2">
                                                    @foreach($chapter->units as $unit)
                                                        <!-- Unit Item -->
                                                        <li class="flex items-center justify-between bg-white border border-slate-200 rounded px-3 py-2 shadow-sm">
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                                <span class="text-sm font-semibold text-slate-700">{{ $unit->name }}</span>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <div class="flex gap-1.5 text-xs text-slate-400 font-medium mr-2">
                                                                    <div class="bg-primary-50 text-primary-700 px-1.5 py-0.5 rounded flex items-center gap-1" title="Videos"><i class="ph-fill ph-video-camera"></i> {{ $unit->videos->count() }}</div>
                                                                    <div class="bg-primary-50 text-primary-700 px-1.5 py-0.5 rounded flex items-center gap-1" title="Notes"><i class="ph-fill ph-file-pdf"></i> {{ $unit->notes->count() }}</div>
                                                                    <div class="bg-primary-50 text-primary-600 px-1.5 py-0.5 rounded flex items-center gap-1" title="Quizzes"><i class="ph-fill ph-game-controller"></i> {{ $unit->quizzes->count() }}</div>
                                                                </div>

                                                                <form action="{{ route('faculty.courses.units.destroy', [$course->id, $subject->id, $chapter->id, $unit->id]) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors"><i class="ph-fill ph-x-circle text-lg"></i></button>
                                                                </form>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
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
