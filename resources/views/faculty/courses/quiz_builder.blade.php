@extends('layouts.faculty')

@section('title', 'Builder: ' . $quiz->title)

@section('page_header')
    <div class="flex items-center gap-3">
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
        </a>
        <span>Builder: <span class="text-primary-700">{{ $quiz->title }}</span></span>
    </div>
@endsection

@section('content')

<!-- Error and Success Messages -->
@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm shadow-red-100/50">
        <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
        <ul class="list-disc list-inside text-sm font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div x-data="{ addingSection: false, importingQuiz: false, activeQuestionSection: null }" class="max-w-5xl mx-auto pb-12">

    <!-- Header Metadata -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        
        <div class="relative z-10 hidden md:block w-16 h-16 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <i class="ph-fill ph-exam text-3xl"></i>
        </div>

        <div class="flex-1 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $quiz->title }}</h2>
                @if($quiz->is_published)
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wide">Published</span>
                @else
                    <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wide">Draft Mode</span>
                @endif
            </div>
            <p class="text-sm text-slate-500 font-medium mb-3">{{ $quiz->description ?? 'No quiz description provided.' }}</p>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                <span class="px-2 py-1 bg-slate-50 rounded-md border border-slate-100 flex items-center gap-1.5"><i class="ph-bold ph-book-open"></i> {{ $quiz->unit->chapter->name }} &rsaquo; {{ $quiz->unit->name }}</span>
            </div>
        </div>

        <div class="relative z-10 flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
             <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="bg-white text-slate-600 hover:text-slate-800 font-bold py-2 px-4 rounded-xl text-sm transition-colors border border-slate-200 hover:border-slate-300 shadow-sm text-center">
                Exit Builder
            </a>
            <form action="{{ route('faculty.courses.quizzes.publish', [$course->id, $quiz->id]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="{{ $quiz->is_published ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/20' : 'btn-primary shadow-primary-500/20' }} text-white font-bold py-2 px-5 rounded-xl shadow-sm transition-all text-sm flex items-center justify-center gap-2 w-full">
                    <i class="ph-bold {{ $quiz->is_published ? 'ph-arrow-u-up-left' : 'ph-paper-plane-tilt' }}"></i> 
                    {{ $quiz->is_published ? 'Revert to Draft' : 'Publish Quiz' }}
                </button>
            </form>
        </div>
    </div>


    <!-- Render Sections Loop -->
    <div class="space-y-8">
        @forelse($quiz->sections as $index => $section)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden ring-1 ring-slate-100">
                
                <!-- Section Header -->
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white text-slate-800 rounded-xl flex items-center justify-center font-black text-lg border border-slate-200 shadow-sm">{{ $index + 1 }}</div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg tracking-tight">{{ $section->name }}</h3>
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 mt-1">
                                <span class="flex items-center gap-1" title="Time Limit"><i class="ph-bold ph-timer text-primary-400"></i> {{ $section->time_limit_minutes ? $section->time_limit_minutes . ' Mins' : 'No Limit' }}</span>
                                <span class="flex items-center gap-1" title="Correct Marks"><i class="ph-bold ph-check-circle text-primary-500"></i> +{{ $section->marks_per_question }}</span>
                                <span class="flex items-center gap-1" title="Negative Marks"><i class="ph-bold ph-minus-circle text-red-500"></i> -{{ $section->negative_marks_per_question }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('faculty.courses.quizzes.sections.destroy', [$course->id, $section->id]) }}" method="POST" onsubmit="return confirm('Delete this entire section and ALL its questions?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors border border-transparent shadow-sm"><i class="ph-bold ph-trash text-lg"></i></button>
                    </form>
                </div>

                <!-- Section Questions Area -->
                <div class="p-6">
                    
                    <!-- Render Existing Questions -->
                    @if($section->questions->count() > 0)
                        <div class="space-y-5 mb-6">
                            @foreach($section->questions as $qIndex => $question)
                                <div class="bg-white border text-sm border-slate-200 rounded-xl p-5 relative shadow-sm hover:border-primary-200 transition-colors group">
                                    <!-- Delete Question Btn -->
                                    <form action="{{ route('faculty.courses.quizzes.questions.destroy', [$course->id, $question->id]) }}" method="POST" onsubmit="return confirm('Delete this question?');" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors"><i class="ph-bold ph-trash"></i></button>
                                    </form>

                                    <!-- Question Text & Image -->
                                    <div class="flex gap-4 mb-5">
                                        <div class="w-8 h-8 flex-shrink-0 bg-slate-100 text-slate-600 font-bold rounded-lg flex items-center justify-center">{{ $qIndex + 1 }}.</div>
                                        <div class="flex-1">
                                            @if($question->question_text)
                                                <p class="text-slate-800 font-medium whitespace-pre-wrap leading-relaxed">{{ $question->question_text }}</p>
                                            @endif
                                            @if($question->question_image_path)
                                                <img src="{{ Storage::url($question->question_image_path) }}" alt="Question Image" class="mt-3 max-h-48 rounded-lg border border-slate-200 shadow-sm">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Render MCQs Options -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-12 pr-4">
                                        @foreach($question->options as $optIndex => $option)
                                            <div class="relative flex items-start gap-3 p-3 rounded-lg border {{ $option->is_correct ? 'border-emerald-300 bg-primary-50/50' : 'border-slate-100 bg-slate-50' }}">
                                                @if($option->is_correct)
                                                    <i class="ph-fill ph-check-circle text-primary-500 text-lg relative top-0.5 flex-shrink-0"></i>
                                                @else
                                                    <div class="w-4 h-4 rounded-full border-2 border-slate-300 relative top-1 flex-shrink-0"></div>
                                                @endif
                                                
                                                <div class="flex-1 min-w-0">
                                                    @if($option->option_text)
                                                        <p class="text-slate-700 text-[13px] font-medium">{{ $option->option_text }}</p>
                                                    @endif
                                                    @if($option->option_image_path)
                                                        <img src="{{ Storage::url($option->option_image_path) }}" alt="Option Image" class="mt-2 max-h-24 rounded shadow-sm border border-slate-200">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Add New Question Toggle Button -->
                    <button x-show="activeQuestionSection != {{ $section->id }}" @click="activeQuestionSection = {{ $section->id }}" class="w-full py-4 border-2 border-dashed border-primary-200 bg-primary-50/50 hover:bg-primary-50 rounded-xl text-primary-700 font-bold text-sm transition-colors flex items-center justify-center gap-2">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Add Question to "{{ $section->name }}"
                    </button>

                    <!-- Add New Question Inline Form -->
                    <div x-show="activeQuestionSection == {{ $section->id }}" x-transition x-cloak class="border border-primary-200 bg-primary-50/30 rounded-xl p-6 shadow-sm shadow-primary-100/50" style="display: none;">
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-primary-100">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2"><i class="ph-fill ph-question text-primary-500 text-lg"></i> Formulate Question</h4>
                            <button type="button" @click="activeQuestionSection = null" class="text-slate-400 hover:text-slate-600"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>
                        
                        <form action="{{ route('faculty.courses.quizzes.questions.store', [$course->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Question Prompt -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Question Text</label>
                                    <textarea name="question_text" rows="3" placeholder="Type the question here..." class="w-full text-sm bg-white border border-slate-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 transition-colors resize-none shadow-sm"></textarea>
                                </div>
                                <div class="col-span-2 md:col-span-1" x-data="{ fileName: '' }">
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Question Image Add-on (Optional)</label>
                                    <div class="relative w-full h-24 rounded-lg overflow-hidden transition-colors" :class="fileName ? 'border border-primary-200 bg-primary-50/30' : 'border-2 border-dashed border-slate-300 bg-white hover:border-primary-400'">
                                        
                                        <div x-show="!fileName" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                            <i class="ph-fill ph-image text-2xl text-slate-400 mb-1"></i>
                                            <span class="text-xs font-semibold text-slate-500">Click to upload image</span>
                                        </div>

                                        <div x-show="fileName" style="display: none;" class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                            <div class="flex items-center gap-2 max-w-full px-4">
                                                <i class="ph-fill ph-image text-primary-500 text-xl flex-shrink-0"></i>
                                                <span class="text-sm font-semibold text-primary-700 truncate" x-text="fileName"></span>
                                            </div>
                                            <button type="button" @click="$refs.qImage.value = ''; fileName = ''" class="absolute bottom-2 right-2 w-7 h-7 flex items-center justify-center bg-white rounded-md text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors shadow-sm border border-red-100 z-10">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>

                                        <input type="file" x-ref="qImage" name="question_image" accept="image/*" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" :class="fileName ? 'pointer-events-none' : ''">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">JPEG, PNG, WEBP (Max 2MB). Useful for chemistry structures or physics diagrams.</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-4">
                                <h4 class="font-bold text-slate-800 tracking-tight">Multiple Choice Options</h4>
                                <div class="h-px bg-slate-200 flex-1"></div>
                                <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Select exact True answer</span>
                            </div>

                            <!-- 4 Fixed Options Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm focus-within:border-primary-400 focus-within:ring-1 focus-within:ring-primary-400 transition-all relative group">
                                        
                                        <!-- Correct Answer Radio -->
                                        <div class="absolute top-4 right-4 bg-slate-50 border border-slate-200 rounded px-2 py-1 shadow-sm flex items-center gap-2 z-10 cursor-pointer hover:bg-primary-50 transition-colors group-focus-within:border-emerald-200">
                                            <input type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'required' : '' }} class="text-primary-500 focus:ring-primary-500 w-4 h-4 cursor-pointer">
                                            <label class="text-[10px] font-bold text-slate-600 uppercase tracking-widest cursor-pointer">True</label>
                                        </div>

                                        <div class="font-bold text-slate-800 mb-3 flex items-center gap-1.5"><i class="ph-fill ph-text-aa text-primary-400"></i> Option {{ chr(65 + $i) }}</div>
                                        
                                        <div class="space-y-3">
                                            <textarea name="options[{{ $i }}][text]" rows="2" placeholder="Text for option {{ chr(65 + $i) }}..." class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:bg-white focus:border-primary-300 transition-colors resize-none"></textarea>
                                            
                                            <div x-data="{ fileName: '' }" class="relative flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 focus-within:bg-white focus-within:border-primary-300 transition-colors overflow-hidden">
                                                <i class="ph-fill ph-image flex-shrink-0" :class="fileName ? 'text-primary-500' : 'text-slate-400'"></i>
                                                <div class="flex-1 min-w-0 relative">
                                                    <input x-show="!fileName" type="file" x-ref="optImage" name="options[{{ $i }}][image]" accept="image/*" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" class="w-full text-xs outline-none file:hidden text-slate-500 cursor-pointer">
                                                    <div x-show="fileName" style="display: none;" class="text-xs font-semibold text-primary-700 truncate pr-8 pt-1 pb-1" x-text="fileName"></div>
                                                </div>
                                                <button x-show="fileName" style="display: none;" type="button" @click="$refs.optImage.value = ''; fileName = ''" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center bg-white rounded-md text-red-500 hover:bg-red-50 border border-slate-200 shadow-sm transition-colors z-10">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-primary-100">
                                <button type="button" @click="activeQuestionSection = null" class="px-5 py-2 rounded-lg font-bold text-slate-500 hover:bg-slate-200 transition-colors text-sm">Cancel</button>
                                <button type="submit" class="btn-primary text-white font-bold py-2 px-6 rounded-lg shadow-sm shadow-primary-500/20 transition-all text-sm flex items-center gap-2"><i class="ph-bold ph-plus"></i> Save Question</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white border border-dashed border-slate-300 rounded-2xl">
                <div class="w-20 h-20 bg-primary-50 text-primary-200 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">
                    <i class="ph-fill ph-squares-four"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No Sections Yet</h3>
                <p class="text-slate-500 max-w-sm mx-auto mb-6">Quizzes are divided into sections. Add your first section to define time limits and scoring schemas.</p>
            </div>
        @endforelse
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <button x-show="!addingSection && !importingQuiz" @click="addingSection = true" class="w-full py-5 border-2 border-dashed border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 rounded-2xl text-slate-600 font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="ph-bold ph-plus-circle text-xl text-slate-400"></i> Construct New Section
        </button>
        <button x-show="!addingSection && !importingQuiz" @click="importingQuiz = true" class="w-full py-5 border-2 border-dashed border-primary-300 hover:border-primary-400 bg-primary-50/30 hover:bg-primary-50/60 rounded-2xl text-primary-700 font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="ph-bold ph-upload-simple text-xl text-primary-500"></i> Bulk Import via ZIP
        </button>
    </div>

    <!-- Create Section Form Modal -->
    <div x-show="addingSection" x-transition x-cloak class="mt-6 bg-white p-6 md:p-8 rounded-2xl border border-primary-200 shadow-xl relative" style="display: none;">
        <button type="button" @click="addingSection = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors"><i class="ph-bold ph-x"></i></button>
        
        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-squares-four text-primary-500"></i> New Section Configuration</h3>

        <form action="{{ route('faculty.courses.quizzes.sections.store', [$course->id, $quiz->id]) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <!-- Section Name -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Section Name</label>
                    <input type="text" name="name" required placeholder="e.g. Physics Core Concepts" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 transition-colors">
                </div>
                
                <!-- Time Limit -->
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Time Limit (Minutes)</label>
                    <div class="relative">
                        <i class="ph-bold ph-timer absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="number" name="time_limit_minutes" min="1" placeholder="Leave blank for no limit" class="w-full pl-11 text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 transition-colors">
                    </div>
                </div>
            </div>

            <div class="bg-primary-50/50 border border-primary-100 rounded-xl p-5 mb-6">
                <h4 class="text-xs font-bold text-primary-800 uppercase tracking-widest mb-4">Scoring Schema</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Marks per Q -->
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Positive Marks</label>
                        <div class="relative">
                            <i class="ph-bold ph-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-primary-500 text-lg"></i>
                            <input type="number" step="0.5" name="marks_per_question" value="4" required class="w-full pl-11 font-bold text-sm bg-white border border-slate-200 rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 transition-colors">
                        </div>
                    </div>
                    <!-- Negative Marks -->
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Negative Marks Penalty</label>
                        <div class="relative">
                            <i class="ph-bold ph-minus-circle absolute left-4 top-1/2 -translate-y-1/2 text-red-500 text-lg"></i>
                            <input type="number" step="0.5" name="negative_marks_per_question" value="1" required class="w-full pl-11 font-bold text-sm bg-white border border-slate-200 rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="addingSection = false" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors text-sm">Cancel</button>
                <button type="submit" class="btn-primary text-white font-bold py-2.5 px-8 rounded-xl shadow-md shadow-primary-500/20 transition-all text-sm flex items-center gap-2">Create Section</button>
            </div>
        </form>
    </div>

    <!-- Bulk Import Form Modal -->
    <div x-show="importingQuiz" x-transition x-cloak class="mt-6 bg-white p-6 md:p-8 rounded-2xl border border-primary-200 shadow-xl relative" style="display: none;">
        <button type="button" @click="importingQuiz = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors"><i class="ph-bold ph-x"></i></button>
        
        <h3 class="text-xl font-bold text-slate-800 mb-2 flex items-center gap-2"><i class="ph-fill ph-file-zip text-primary-500"></i> Import Quiz Data</h3>
        <p class="text-sm text-slate-500 mb-6 font-medium">Upload a ZIP file containing <code class="bg-slate-100 text-primary-700 px-1 rounded">quiz.csv</code> and all optional question/option images.</p>

        <form action="{{ route('faculty.courses.quizzes.import', [$course->id, $quiz->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 mb-6 bg-slate-50 flex flex-col items-center justify-center relative hover:border-primary-400 hover:bg-primary-50/30 transition-colors cursor-pointer group">
                <i class="ph-fill ph-upload-simple text-4xl text-slate-400 group-hover:text-primary-400 mb-3 transition-colors"></i>
                <h4 class="font-bold text-slate-700 text-center mb-1">Click to browse or drag ZIP file here</h4>
                <p class="text-xs text-slate-400 font-medium">Maximum size: 50MB</p>
                <input type="file" name="quiz_zip" accept=".zip" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer text-sm">
            </div>

            <div class="bg-primary-50/50 border border-primary-100 rounded-xl p-4 mb-6 flex items-start gap-3">
                <i class="ph-fill ph-info text-primary-500 text-lg mt-0.5"></i>
                <div class="text-xs text-slate-600">
                    <strong class="text-primary-800 block mb-1">CSV Template Structure:</strong>
                    Column order: Section Name, Time Limit, Pos Marks, Neg Marks, Question Text, Question Image Filename, Option A Text, Opt A Img, Opt B Text, Opt B Img, Opt C Text, Opt C Img, Opt D Text, Opt D Img, Correct Option Letter (A/B/C/D).
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ asset('quiz_template.csv') }}" download="quiz_template.csv" class="text-xs font-bold text-primary-700 hover:text-primary-800 flex items-center gap-1"><i class="ph-bold ph-download-simple"></i> Download Template CSV</a>
                <div class="flex justify-end gap-3 w-full sm:w-auto">
                    <button type="button" @click="importingQuiz = false" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors text-sm w-full sm:w-auto">Cancel</button>
                    <button type="submit" class="btn-primary text-white font-bold py-2.5 px-8 rounded-xl shadow-md shadow-primary-500/20 transition-all text-sm flex items-center justify-center gap-2 w-full sm:w-auto"><i class="ph-bold ph-upload-simple"></i> Run Importer</button>
                </div>
            </div>
        </form>
    </div>

</div>

@endsection
