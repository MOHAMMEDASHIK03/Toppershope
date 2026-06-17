@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-20" x-data="quizEngine()">
    
    <!-- Top Sticky Header bar -->
    <div class="fixed top-16 inset-x-0 h-14 bg-background/90 backdrop-blur-md border-b border-white/10 z-40 flex items-center justify-between px-4 md:px-10">
        <div class="flex items-center">
            <a href="{{ route('course.show', $quiz->batch->uuid) }}" class="text-gray-400 hover:text-white mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-white hidden md:block">{{ $quiz->title }}</h1>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex flex-col items-end">
                <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Time Remaining</span>
                <span class="text-xl font-mono font-black text-secondary" :class="{'text-red-500 animate-pulse': timeLeft < 300}" x-text="formattedTime">--:--</span>
            </div>
            
            <button @click="submitQuiz()" class="px-6 py-2 bg-primary hover:bg-opacity-90 text-white font-bold rounded-lg transition-colors border border-primary/50 shadow-[0_0_15px_rgba(27,42,255,0.4)]">
                Submit Test
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-4xl mx-auto pt-24 pb-32 px-4">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-white mb-2">{{ $quiz->title }}</h2>
            <p class="text-gray-400">{{ $quiz->description }}</p>
            <div class="flex justify-center gap-4 mt-6">
                <div class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-gray-300">
                    <span class="text-primary">{{ $quiz->questions->count() }}</span> Questions
                </div>
                <div class="px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-gray-300">
                    <span class="text-secondary">{{ $quiz->total_marks }}</span> Total Marks
                </div>
            </div>
        </div>

        <form id="quiz-form" action="{{ route('student.quiz.submit', $quiz->uuid) }}" method="POST">
            @csrf
            
            <div class="space-y-12">
                @foreach($quiz->questions as $index => $question)
                <div class="glass-panel p-6 md:p-8 rounded-3xl border border-white/5 relative group hover:border-white/10 transition-colors" id="q-{{ $question->id }}">
                    <!-- Question Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-gray-800 flex items-center justify-center text-lg font-black text-white border border-white/10 shadow-inner">
                                {{ $index + 1 }}
                            </div>
                            <div class="text-lg md:text-xl font-medium text-gray-100 leading-relaxed pt-1">
                                {!! nl2br(e($question->question_text)) !!}
                            </div>
                        </div>
                        <div class="shrink-0 flex flex-col items-end gap-1 ml-4 pt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20 w-max">
                                +{{ $question->marks }} Marks
                            </span>
                            @if($question->negative_marks > 0)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20 w-max">
                                -{{ $question->negative_marks }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3 pl-14">
                        @foreach($question->options as $option)
                        <label class="block relative group/option cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="peer sr-only" @change="markAnswered({{ $question->id }})">
                            <div class="p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 peer-checked:bg-primary/20 peer-checked:border-primary/50 transition-all flex items-center gap-4">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-600 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center shrink-0 transition-colors">
                                    <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <span class="text-gray-300 peer-checked:text-white font-medium">{{ $option->option_text }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Mobile sticky bottom Submit -->
            <div class="fixed bottom-0 inset-x-0 p-4 bg-background/90 backdrop-blur-md border-t border-white/10 md:hidden z-40">
                <button type="button" @click="submitQuiz()" class="w-full py-3 bg-primary text-white font-bold rounded-xl active:scale-95 transition-transform">
                    Submit Test (<span x-text="answeredCount">0</span>/{{ $quiz->questions->count() }})
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function quizEngine() {
    return {
        timeLeft: {{ $quiz->duration_minutes * 60 }},
        answered: new Set(),
        get answeredCount() {
            return this.answered.size;
        },
        get formattedTime() {
            const m = Math.floor(this.timeLeft / 60).toString().padStart(2, '0');
            const s = (this.timeLeft % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },
        init() {
            // Timer interval
            setInterval(() => {
                if(this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    // Auto submit when time runs out
                    document.getElementById('quiz-form').submit();
                }
            }, 1000);

            // Prevent leaving easily
            window.addEventListener('beforeunload', this.warnUnload);
        },
        markAnswered(qId) {
            this.answered.add(qId);
        },
        warnUnload(e) {
            e.preventDefault();
            e.returnValue = '';
        },
        submitQuiz() {
            if(confirm('Are you sure you want to submit the quiz? You cannot change your answers after submitting.')) {
                window.removeEventListener('beforeunload', this.warnUnload);
                document.getElementById('quiz-form').submit();
            }
        }
    }
}
</script>
@endsection
