@extends('admin.layouts.app')
@section('title', 'Builder: ' . $quiz->title)
@section('page_title', 'Quiz Builder')

@section('content')
<div x-data="{ addingSection: false, importingQuiz: false, activeQuestionSection: null }" class="pb-12">

    {{-- BACK + HEADER --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.quizzes.index', $course->id) }}" class="text-primary-700 hover:text-primary-800 text-sm font-semibold transition-colors">
                &larr; Back to quizzes
            </a>
            <div class="h-4 w-px bg-slate-200"></div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-black text-slate-900">{{ $quiz->title }}</h2>
                    @if($quiz->is_published)
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase bg-primary-500/20 text-emerald-400 border border-primary-500/30 rounded-md">Published</span>
                    @else
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase bg-slate-700 text-slate-400 border border-slate-600 rounded-md">Draft</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">{{ $quiz->description ?? 'No description.' }}</p>
            </div>
        </div>
        <form action="{{ route('admin.courses.quizzes.publish', [$course->id, $quiz->id]) }}" method="POST">
            @csrf
            <button type="submit" class="{{ $quiz->is_published ? 'bg-amber-500 hover:bg-amber-600' : 'bg-primary-600 hover:bg-emerald-700' }} text-white font-semibold px-5 py-2.5 rounded-xl text-sm shadow-sm transition-all">
                {{ $quiz->is_published ? 'Revert to Draft' : 'Publish Quiz' }}
            </button>
        </form>
    </div>

    {{-- FLASH MESSAGES --}}
    @if($errors->any())
        <div class="mb-6 px-5 py-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 font-semibold text-sm space-y-1">
            @foreach($errors->all() as $err)<p>{{ $err }}</p>@endforeach
        </div>
    @endif

    {{-- SECTIONS LOOP --}}
    <div class="space-y-6">
        @forelse($quiz->sections as $index => $section)
        <div class="admin-panel border border-slate-200 rounded-2xl overflow-hidden">

            {{-- Section Header --}}
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-900 border border-slate-700 rounded-xl flex items-center justify-center font-black text-slate-900 text-lg">{{ $index + 1 }}</div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">{{ $section->name }}</h3>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-400 mt-0.5">
                            <span>⏱ {{ $section->time_limit_minutes ? $section->time_limit_minutes . ' min' : 'No Limit' }}</span>
                            <span class="text-emerald-400">+{{ $section->marks_per_question }} correct</span>
                            <span class="text-rose-400">-{{ $section->negative_marks_per_question }} wrong</span>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.courses.quizzes.sections.destroy', [$course->id, $section->id]) }}" method="POST" onsubmit="return confirm('Delete this section and ALL its questions?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-primary-500/10 hover:bg-primary-500/20 text-rose-400 font-bold text-xs rounded-xl transition-all">Delete Section</button>
                </form>
            </div>

            {{-- Questions --}}
            <div class="p-6">
                @if($section->questions->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($section->questions as $qIndex => $question)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 relative">
                        <form action="{{ route('admin.courses.quizzes.questions.destroy', [$course->id, $question->id]) }}" method="POST"
                              onsubmit="return confirm('Delete question?')" class="absolute top-3 right-3">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-2 py-1 text-rose-400 hover:bg-primary-500/10 rounded-lg text-xs font-bold transition-all">✕ Delete</button>
                        </form>
                        <div class="flex gap-3 mb-4">
                            <div class="w-8 h-8 shrink-0 bg-slate-900 border border-slate-700 rounded-lg flex items-center justify-center text-xs font-black text-slate-300">{{ $qIndex + 1 }}</div>
                            <div class="flex-1">
                                @if($question->question_text)
                                    <p class="text-slate-900 font-medium whitespace-pre-wrap leading-relaxed">{{ $question->question_text }}</p>
                                @endif
                                @if($question->question_image_path)
                                    <img src="{{ Storage::url($question->question_image_path) }}" class="mt-3 max-h-48 rounded-xl border border-slate-700 shadow">
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pl-11">
                            @foreach($question->options as $option)
                            <div class="flex items-start gap-2 p-3 rounded-xl border {{ $option->is_correct ? 'border-primary-500/40 bg-primary-500/10' : 'border-slate-200 bg-slate-900/30' }}">
                                @if($option->is_correct)
                                    <span class="text-emerald-400 font-black text-sm shrink-0">✓</span>
                                @else
                                    <span class="w-4 h-4 rounded-full border-2 border-slate-600 shrink-0 mt-0.5"></span>
                                @endif
                                <div>
                                    @if($option->option_text)
                                        <p class="text-slate-300 text-xs font-medium">{{ $option->option_text }}</p>
                                    @endif
                                    @if($option->option_image_path)
                                        <img src="{{ Storage::url($option->option_image_path) }}" class="mt-1.5 max-h-20 rounded-lg border border-slate-700">
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Add Question Toggle --}}
                <button x-show="activeQuestionSection != {{ $section->id }}"
                        @click="activeQuestionSection = {{ $section->id }}"
                        class="w-full py-4 border-2 border-dashed border-primary-500/30 hover:border-primary-500/60 bg-primary-500/5 hover:bg-primary-500/10 rounded-xl text-primary-400 font-bold text-sm transition-all">
                    + Add Question to "{{ $section->name }}"
                </button>

                {{-- Add Question Form --}}
                <div x-show="activeQuestionSection == {{ $section->id }}" style="display:none;"
                     class="border border-primary-500/30 bg-slate-50 rounded-xl p-6 mt-4">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="font-bold text-slate-900">Formulate Question</h4>
                        <button type="button" @click="activeQuestionSection = null" class="text-slate-400 hover:text-slate-900 text-sm">✕ Close</button>
                    </div>
                    <form action="{{ route('admin.courses.quizzes.questions.store', [$course->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 mb-1">Question Text</label>
                                <textarea name="question_text" rows="3" placeholder="Type the question..." class="admin-input text-sm py-2.5 px-3 focus:ring-2 focus:ring-primary-500 outline-none resize-none"></textarea>
                            </div>
                            <div x-data="{ fileName: '' }">
                                <label class="block text-xs font-bold text-slate-400 mb-1">Question Image (optional)</label>
                                <div class="relative bg-slate-800 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary-500">
                                    <input x-show="!fileName" type="file" x-ref="qImage" name="question_image" accept="image/*" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="w-full text-sm text-slate-400 file:mr-3 file:py-2 file:px-3 file:border-0 file:text-sm file:font-bold file:bg-slate-700 file:text-slate-300 hover:file:bg-slate-600 cursor-pointer outline-none">
                                    <div x-show="fileName" style="display: none;" class="w-full flex items-center justify-between py-2 px-3">
                                        <div class="flex items-center gap-2 max-w-[80%]">
                                            <span class="text-sm font-semibold text-slate-300 truncate" x-text="fileName"></span>
                                        </div>
                                        <button type="button" @click="$refs.qImage.value = ''; fileName = ''" class="w-7 h-7 flex items-center justify-center bg-slate-700 rounded-lg text-rose-400 hover:bg-primary-500/20 hover:text-rose-300 transition-colors font-bold text-xs">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 flex items-center gap-2">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-widest">MCQ Options</h5>
                            <div class="h-px bg-slate-700 flex-1"></div>
                            <span class="text-[10px] font-bold text-amber-400 uppercase">Mark the correct one</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">
                            @for($i = 0; $i < 4; $i++)
                            <div class="bg-slate-900 border border-slate-700 rounded-xl p-4 focus-within:border-primary-500 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-black text-slate-300">Option {{ chr(65 + $i) }}</span>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-[11px] font-bold text-slate-400">
                                        <input type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'required' : '' }} class="accent-primary">
                                        <span>Correct</span>
                                    </label>
                                </div>
                                <textarea name="options[{{ $i }}][text]" rows="2" placeholder="Option text..." class="admin-input text-xs py-2 px-2.5 focus:ring-1 focus:ring-primary-500 outline-none resize-none mb-2"></textarea>
                                <div x-data="{ fileName: '' }" class="relative bg-slate-800 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-primary-500">
                                    <input x-show="!fileName" type="file" x-ref="optImage" name="options[{{ $i }}][image]" accept="image/*" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:border-0 file:text-xs file:font-bold file:bg-slate-700 file:text-slate-300 hover:file:bg-slate-600 cursor-pointer outline-none">
                                    <div x-show="fileName" style="display: none;" class="flex items-center justify-between py-1 px-2">
                                        <div class="flex items-center gap-2 max-w-[80%]">
                                            <span class="text-xs font-semibold text-slate-300 truncate" x-text="fileName"></span>
                                        </div>
                                        <button type="button" @click="$refs.optImage.value = ''; fileName = ''" class="w-5 h-5 flex items-center justify-center bg-slate-700 rounded text-rose-400 hover:bg-primary-500/20 hover:text-rose-300 transition-colors text-[10px] font-bold">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="activeQuestionSection = null" class="px-4 py-2.5 text-slate-400 hover:text-slate-900 font-bold text-sm transition-colors rounded-xl">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 btn-primary text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-primary-500/20 transition-all">Save Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 admin-panel border border-slate-200 rounded-2xl">
            <div class="text-5xl mb-3">📋</div>
            <h3 class="text-xl font-black text-slate-900 mb-2">No Sections Yet</h3>
            <p class="text-slate-400 text-sm max-w-sm mx-auto">Add a section below to start building questions.</p>
        </div>
        @endforelse
    </div>

    {{-- CTA Buttons --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <button x-show="!addingSection && !importingQuiz" @click="addingSection = true"
                class="py-5 border-2 border-dashed border-slate-600 hover:border-primary-500/50 bg-slate-800/30 hover:bg-primary-500/5 rounded-2xl text-slate-400 hover:text-primary-400 font-bold text-sm transition-all">
            + Construct New Section
        </button>
        <button x-show="!addingSection && !importingQuiz" @click="importingQuiz = true"
                class="py-5 border-2 border-dashed border-slate-600 hover:border-primary-500/50 bg-slate-800/30 hover:bg-primary-500/5 rounded-2xl text-slate-400 hover:text-emerald-400 font-bold text-sm transition-all">
            ↑ Bulk Import via ZIP
        </button>
    </div>

    {{-- Add Section Form --}}
    <div x-show="addingSection" x-cloak class="mt-6 admin-panel border-primary-200 p-6 md:p-8 relative">
        <button @click="addingSection = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 font-bold">✕</button>
        <h3 class="text-lg font-black text-slate-900 mb-6">New Section Configuration</h3>
        <form action="{{ route('admin.courses.quizzes.sections.store', [$course->id, $quiz->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 mb-1">Section Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Physics Core Concepts" class="admin-input text-sm py-2.5 px-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Time Limit (Minutes)</label>
                    <input type="number" name="time_limit_minutes" min="1" placeholder="Leave blank for no limit" class="admin-input text-sm py-2.5 px-3 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-5">
                <h4 class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-4">Scoring Schema</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Positive Marks *</label>
                        <input type="number" step="0.5" name="marks_per_question" value="4" required class="admin-input text-sm py-2.5 px-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Negative Marks Penalty *</label>
                        <input type="number" step="0.5" name="negative_marks_per_question" value="1" required class="admin-input text-sm py-2.5 px-3 focus:ring-2 focus:ring-primary-500 outline-none">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="addingSection = false" class="px-5 py-2.5 text-slate-400 hover:text-slate-900 font-bold text-sm rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 btn-primary text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-primary-500/20 transition-all">Create Section</button>
            </div>
        </form>
    </div>

    {{-- Bulk ZIP Import Form --}}
    <div x-show="importingQuiz" x-cloak class="mt-6 admin-panel border-emerald-200 p-6 md:p-8 relative">
        <button @click="importingQuiz = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 font-bold">✕</button>
        <h3 class="text-lg font-black text-slate-900 mb-2">Bulk Import via ZIP</h3>
        <p class="text-sm text-slate-400 mb-5">Upload a ZIP containing <code class="bg-slate-800 text-emerald-400 px-1.5 py-0.5 rounded text-xs">quiz.csv</code> and any question/option image files.</p>
        <form action="{{ route('admin.courses.quizzes.import', [$course->id, $quiz->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-600 hover:border-primary-500/50 rounded-xl bg-slate-800/30 hover:bg-primary-500/5 cursor-pointer transition-all mb-5 relative">
                <span class="text-3xl mb-1">📦</span>
                <span class="text-slate-400 font-bold text-sm">Click to choose ZIP (max 50MB)</span>
                <input type="file" name="quiz_zip" accept=".zip" required class="absolute inset-0 opacity-0 cursor-pointer">
            </label>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-5 text-xs text-slate-400">
                <strong class="text-slate-900 block mb-1">CSV column order:</strong>
                Section Name · Time Limit · Pos Marks · Neg Marks · Question Text · Q Image Filename · Opt A Text · A Img · Opt B Text · B Img · Opt C Text · C Img · Opt D Text · D Img · Correct (A/B/C/D)
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="importingQuiz = false" class="px-5 py-2.5 text-slate-400 hover:text-slate-900 font-bold text-sm rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-primary-500/20 transition-all">Run Importer</button>
            </div>
        </form>
    </div>

</div>
@endsection
