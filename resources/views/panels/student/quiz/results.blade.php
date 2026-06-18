@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-20">
    <div class="max-w-4xl mx-auto px-4">
        
        <!-- Score Header -->
        <div class="glass-panel rounded-3xl p-8 md:p-12 text-center relative overflow-hidden mb-12 border-t-2 {{ $attempt->score > ($quiz->total_marks * 0.7) ? 'border-green-500' : 'border-secondary' }}">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary/20 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-secondary/20 rounded-full blur-[100px]"></div>
            
            <div class="relative z-10">
                <h1 class="text-sm font-bold tracking-widest text-gray-400 uppercase mb-4">Performance Report</h1>
                <h2 class="text-3xl md:text-5xl font-black text-white mb-8">{{ $quiz->title }}</h2>
                
                <div class="inline-flex flex-col items-center justify-center w-48 h-48 rounded-full border-8 border-white/5 bg-background shadow-[0_0_50px_rgba(0,0,0,0.5)] mb-8 relative">
                    <!-- Fake circular progress using conic gradient -->
                    @php 
                        $percentage = max(0, min(100, ($attempt->score / max($quiz->total_marks, 1)) * 100));
                        $color = $percentage > 70 ? '#22c55e' : ($percentage > 40 ? '#9F7AEA' : '#ef4444');
                    @endphp
                    <div class="absolute inset-[-8px] rounded-full z-0" style="background: conic-gradient({{ $color }} {{ $percentage }}%, transparent 0);"></div>
                    <div class="absolute inset-[2px] rounded-full bg-background z-10"></div>
                    
                    <div class="relative z-20 flex flex-col items-center">
                        <span class="text-5xl font-black text-white">{{ $attempt->score }}</span>
                        <span class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">out of {{ $quiz->total_marks }}</span>
                    </div>
                </div>

                <div class="flex justify-center gap-8 text-left">
                    <div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Accuracy</div>
                        <div class="text-xl font-bold text-white">{{ number_format($percentage) }}%</div>
                    </div>
                    <div class="w-px bg-white/10"></div>
                    <div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Time Taken</div>
                        <div class="text-xl font-bold text-white">{{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} mins</div>
                    </div>
                </div>
                
                <div class="mt-10">
                    <a href="{{ route('course.show', $quiz->batch->uuid) }}" class="px-8 py-3 bg-white/5 border border-white/10 hover:bg-white/10 rounded-xl text-white font-bold transition-colors">
                        Return to Course
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Review -->
        <div class="space-y-6">
            <h3 class="text-xl font-black text-white mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Detailed Analysis
            </h3>
            
            @foreach($attempt->answers as $index => $answer)
                @php
                    $q = $answer->question;
                    $statusColor = $answer->is_correct === true ? 'green' : ($answer->is_correct === false ? 'red' : 'gray');
                @endphp
                <div class="glass-panel p-6 rounded-2xl border-l-4 border-{{ $statusColor }}-500 bg-white/5 relative">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-3">
                            <span class="text-gray-500 font-bold mt-0.5">Q{{ $index + 1 }}.</span>
                            <div class="text-gray-200 font-medium">{!! nl2br(e($q->question_text)) !!}</div>
                        </div>
                        <div class="shrink-0 flex items-center">
                            @if($answer->is_correct === true)
                                <span class="text-green-400 text-sm font-bold flex items-center">+{{ $q->marks }} <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>
                            @elseif($answer->is_correct === false && $answer->option_id)
                                <span class="text-red-400 text-sm font-bold flex items-center">-{{ $q->negative_marks }} <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            @else
                                <span class="text-gray-500 text-sm font-bold">Unattempted</span>
                            @endif
                        </div>
                    </div>

                    <div class="ml-7 space-y-2 mt-4">
                        @foreach($q->options as $option)
                            @php
                                $isUserChoice = $answer->option_id == $option->id;
                                $isCorrectChoice = $option->is_correct;
                                
                                $bgClass = 'bg-white/5 border border-white/5';
                                $textClass = 'text-gray-400';
                                
                                if($isCorrectChoice) {
                                    $bgClass = 'bg-green-500/10 border border-green-500/30';
                                    $textClass = 'text-green-300 font-bold';
                                } elseif($isUserChoice && !$isCorrectChoice) {
                                    $bgClass = 'bg-red-500/10 border border-red-500/30';
                                    $textClass = 'text-red-300';
                                }
                            @endphp
                            <div class="px-4 py-2 rounded-lg {{ $bgClass }} flex justify-between items-center text-sm">
                                <span class="{{ $textClass }}">{{ $option->option_text }}</span>
                                
                                @if($isUserChoice)
                                    <span class="text-[10px] uppercase font-bold tracking-widest {{ $isCorrectChoice ? 'text-green-500' : 'text-red-500' }}">Your Answer</span>
                                @elseif($isCorrectChoice)
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-green-500">Correct Answer</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
        </div>
    </div>
</div>
@endsection
