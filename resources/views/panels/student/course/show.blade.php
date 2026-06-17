@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-20 flex flex-col md:flex-row">
    
    <!-- Left Sidebar: Course Curriculum -->
    <div class="w-full md:w-80 lg:w-96 bg-white/5 border-r border-white/10 flex flex-col h-[calc(100vh-5rem)] overflow-hidden">
        
        <!-- Course Meta Header -->
        <div class="p-6 border-b border-white/10 bg-background/50 relative overflow-hidden">
            <div class="relative z-10">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-white mb-4 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
                <h1 class="text-xl font-bold text-white leading-tight mb-2">{{ $batch->name }}</h1>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-secondary font-medium">{{ $batch->category?->name ?? $batch->course->category?->name ?? 'Advanced Tracker' }}</span>
                    <span class="text-gray-400">12 / 85 Lessons</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4 relative z-10">
                <div class="h-1.5 w-full bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary to-secondary w-[15%] relative">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
                <div class="text-[10px] text-right mt-1 text-gray-500 font-bold">15% COMPLETED</div>
            </div>
        </div>

        <!-- Scrollable Lessons List -->
        <div class="flex-1 overflow-y-auto scrollbar-hide py-4 px-2 space-y-1">
            
            <!-- Chapter 1 (Expanded) -->
            <div class="mb-4" x-data="{ expanded: true }">
                <button @click="expanded = !expanded" class="w-full px-4 py-3 flex items-center justify-between bg-white/5 hover:bg-white/10 rounded-xl transition-colors group">
                    <div class="flex items-center text-left">
                        <div class="w-8 h-8 rounded-lg bg-gray-800 text-gray-400 flex items-center justify-center mr-3 text-xs font-bold group-hover:bg-primary/20 group-hover:text-primary transition-colors">01</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Rotational Mechanics</h3>
                            <p class="text-[10px] text-gray-400">4 Video Lessons • 2 PDFs</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-500 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Lessons inside Chapter -->
                <div x-show="expanded" x-collapse class="pl-4 pr-2 pt-2 pb-4 space-y-1">
                    
                    <!-- Watched Lesson -->
                    <a href="#" class="flex border border-transparent items-start p-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <div class="mt-0.5 text-green-500 mr-3">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-300">1.1 Introduction to Torque</p>
                            <span class="text-[10px] text-gray-500 flex items-center mt-1"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 45 mins</span>
                        </div>
                    </a>

                    <!-- CURRENT ACTIVE LESSON -->
                    <a href="#" class="flex border border-primary/30 items-start p-3 rounded-lg bg-primary/10 relative overflow-hidden group">
                        <div class="absolute inset-y-0 left-0 w-1 bg-primary"></div>
                        <div class="mt-0.5 text-primary mr-3 flex items-center justify-center w-4 h-4 relative">
                            <span class="absolute w-full h-full bg-primary rounded-full animate-ping opacity-20"></span>
                            <svg class="w-3 h-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">1.2 Moment of Inertia Advanced</p>
                            <span class="text-[10px] text-primary flex items-center mt-1">Playing Now...</span>
                        </div>
                    </a>

                    <!-- PDF Materials -->
                    <a href="{{ $pdfUrl }}" target="_blank" class="flex border border-transparent items-start p-3 rounded-lg hover:bg-white/5 transition-colors group">
                        <div class="mt-0.5 text-red-400 mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 group-hover:text-white transition-colors">Class Notes PDF</p>
                            <span class="text-[10px] text-gray-500 flex items-center mt-1">Stampted & Secured</span>
                        </div>
                    </a>

                    <!-- Quizzes/Assesments -->
                    @if(isset($quizzes) && $quizzes->count() > 0)
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Assessments</p>
                            @foreach($quizzes as $quiz)
                                <a href="{{ route('student.quiz.show', $quiz->uuid) }}" class="flex border border-transparent items-start p-3 rounded-lg hover:bg-white/5 transition-colors group">
                                    <div class="mt-0.5 text-secondary mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-gray-300 group-hover:text-white transition-colors">{{ $quiz->title }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[10px] text-gray-500 font-medium">{{ $quiz->questions->count() }} Qs</span>
                                            <span class="text-[10px] text-secondary/70 font-bold px-1.5 py-0.5 rounded-sm bg-secondary/10">{{ $quiz->total_marks }} Marks</span>
                                        </div>
                                    </div>
                                    <svg class="w-3 h-3 text-gray-600 group-hover:text-secondary self-center transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Chapter 2 (Locked/Upcoming) -->
            <div class="mb-4">
                <button class="w-full px-4 py-3 flex items-center justify-between bg-white/5 rounded-xl opacity-50 cursor-not-allowed group">
                    <div class="flex items-center text-left">
                        <div class="w-8 h-8 rounded-lg bg-gray-800 text-gray-500 flex items-center justify-center mr-3 text-xs font-bold">02</div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-400">Angular Momentum</h3>
                            <p class="text-[10px] text-gray-500">Locked • Complete Ch 1 first</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Main Content Area (Video Player & Info) -->
    <div class="flex-1 overflow-y-auto h-[calc(100vh-5rem)] bg-[#0B1120] relative">
        <div class="max-w-6xl mx-auto">
            
            <!-- Video Player Wrap -->
            <div class="w-full aspect-video bg-black relative flex items-center justify-center group overflow-hidden" x-data="{ playing: false, showPlayButton: true }">
                
                <video id="course-video" controlsList="nodownload" class="w-full h-full object-contain" @play="playing = true; showPlayButton = false" @pause="playing = false" >
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                
                <!-- Play Button Overlay -->
                <button x-show="showPlayButton" @click="document.getElementById('course-video').play()" class="absolute inset-0 m-auto w-20 h-20 bg-primary/80 backdrop-blur-md rounded-full flex items-center justify-center text-white shadow-[0_0_50px_rgba(27,42,255,0.6)] hover:scale-110 transition-transform duration-300 z-20">
                    <svg class="w-10 h-10 ml-2" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                </button>
                
                <!-- Dynamic Piracy Watermark (Overlay) -->
                <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden flex items-center justify-center opacity-[0.15] select-none">
                    <div class="transform -rotate-45 text-white text-2xl font-mono whitespace-nowrap track-user font-black" style="animation: scroll 20s linear infinite;">
                        {{ Auth::user()->name }} • {{ Auth::user()->email }} • {{ now()->format('Y-m-d H:i:s') }}
                    </div>
                </div>

            </div>

            <!-- Content Details under video -->
            <div class="p-6 md:p-10">
                <div class="flex items-start justify-between mb-6 border-b border-white/10 pb-6">
                    <div>
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary/20 text-primary mb-3 border border-primary/30 uppercase tracking-widest">
                            Chapter 1
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black text-white mb-2">Moment of Inertia Advanced Constraints</h2>
                        <p class="text-sm text-gray-400 flex items-center gap-4">
                            <span><strong class="text-white">Prof. Verma</strong> (Physics HOD)</span>
                            <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                            <span>Recorded: 12 Oct '25</span>
                        </p>
                    </div>
                    
                    <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-sm font-bold text-white transition-colors flex items-center shadow-lg whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Ask Doubt
                    </button>
                </div>

                <!-- Teacher's Notes / Description -->
                <div class="prose prose-invert max-w-none text-gray-300 text-sm md:text-base leading-relaxed">
                    <p class="mb-4">In this session, we will tackle the most intimidating aspect of rotational mechanics: constraint relations combined with moment of inertia equations. We will derive the classic cylinder-on-a-plank problem from first principles.</p>
                    
                    <h4 class="text-white font-bold text-lg mt-6 mb-3">Key Concepts Covered:</h4>
                    <ul class="list-none space-y-2 mb-6">
                        <li class="flex items-start"><svg class="w-5 h-5 text-secondary mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Parallel Axis Theorem complex applications</span></li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-secondary mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Energy conservation with rolling friction</span></li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-secondary mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>Pseudo force analysis in non-inertial frames</span></li>
                    </ul>

                    <div class="glass-panel p-5 rounded-2xl bg-secondary/5 border-l-4 border-secondary mt-8 flex items-start">
                        <svg class="w-6 h-6 text-secondary mr-3 flex-shrink-0 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h5 class="text-white font-bold text-sm mb-1">Homework Alert</h5>
                            <p class="text-xs m-0">Please refer to the attached "Class Notes PDF" below the video player. Attempt JEE Advanced PYQs from 2012-2022 related to rolling motion before viewing the next lecture.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scroll {
    0% { transform: rotate(-45deg) translateY(0) translateX(200%); }
    100% { transform: rotate(-45deg) translateY(0) translateX(-200%); }
}
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
@endsection
