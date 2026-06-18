@extends('layouts.faculty')

@section('title', 'Doubts: ' . $course->name)

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
        <a href="{{ route('faculty.courses.quizzes.index', $course->id) }}" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Quizzes
        </a>
        <a href="{{ route('faculty.courses.doubts.index', $course->id) }}" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2">
            Doubts 
            @php $unresolvedCount = $doubts->where('is_resolved', false)->count(); @endphp
            @if($unresolvedCount > 0)
                <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">{{ $unresolvedCount }}</span>
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

<!-- Doubts Manager UI -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8" x-data="{ 
    selectedDoubtId: {{ $doubts->first() ? $doubts->first()->id : 'null' }}
}">
    <div class="flex h-[700px]">
        
        <!-- Left Sidebar: Doubts List -->
        <div class="w-1/3 border-r border-slate-200 bg-slate-50 flex flex-col h-full overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-white shadow-sm z-10">
                <h3 class="font-bold text-slate-800 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i class="ph-fill ph-chats-circle text-primary-500"></i> Student Doubts</span>
                    <button class="text-slate-400 hover:text-primary-700 transition-colors"><i class="ph-bold ph-funnel"></i></button>
                </h3>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($doubts as $doubt)
                    <div @click="selectedDoubtId = {{ $doubt->id }}"
                         class="p-4 border-b border-slate-100 cursor-pointer transition-colors relative"
                         :class="selectedDoubtId === {{ $doubt->id }} ? 'bg-primary-50 hover:bg-primary-50' : 'bg-white hover:bg-slate-50'">
                        
                        <!-- Active Indicator -->
                        <div x-show="selectedDoubtId === {{ $doubt->id }}" class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>

                        <div class="flex justify-between items-start mb-1 gap-2">
                            <h4 class="font-bold text-sm text-slate-800 line-clamp-1 flex-1">{{ $doubt->subject }}</h4>
                            @if($doubt->is_resolved)
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wide flex-shrink-0">Resolved</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wide flex-shrink-0">Pending</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                            <div class="w-5 h-5 rounded-full bg-slate-200 flex flex-shrink-0 items-center justify-center font-bold text-[10px] text-slate-600">
                                {{ substr($doubt->student->name, 0, 1) }}
                            </div>
                            <span class="truncate">{{ $doubt->student->name }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="flex-shrink-0">{{ $doubt->created_at->diffForHumans(null, true, true) }}</span>
                        </div>
                        
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $doubt->description }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-sm italic px-6">
                        <i class="ph-fill ph-check-circle text-4xl text-emerald-300 mb-3 block"></i>
                        No student doubts left! You're all caught up.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side: Content Area (Discord Style Chat Pane) -->
        <div class="w-2/3 bg-white flex flex-col h-full relative">
            
            <!-- Empty State -->
            <div x-show="!selectedDoubtId" class="absolute inset-0 z-20 bg-white flex flex-col items-center justify-center text-center p-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4 border border-slate-100 shadow-sm">
                    <i class="ph-fill ph-chats text-primary-200"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Select a Conversation</h3>
                <p class="text-slate-500 max-w-sm">Click on a student's doubt from the left pane to view details and provide an answer.</p>
            </div>

            <!-- Loop through doubts creating hidden panes -->
            @foreach($doubts as $doubt)
                <div x-show="selectedDoubtId === {{ $doubt->id }}" style="display: none;" class="flex flex-col h-full bg-slate-50/30">
                    
                    <!-- Chat Header -->
                    <div class="px-6 py-4 border-b border-slate-200 bg-white flex items-center justify-between shadow-sm z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-black text-lg shadow-inner">
                                {{ substr($doubt->student->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $doubt->student->name }}</h3>
                                <div class="text-xs text-slate-500 font-medium flex items-center gap-2">
                                    <i class="ph-bold ph-calendar-blank"></i> {{ $doubt->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div>
                             @if($doubt->is_resolved)
                                <div class="bg-primary-50 border border-emerald-200 text-emerald-700 px-3 py-1.5 rounded-lg flex items-center gap-2 text-xs font-bold shadow-sm">
                                    <i class="ph-fill ph-check-circle text-lg"></i> Query Resolved
                                </div>
                            @else
                                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-lg flex items-center gap-2 text-xs font-bold shadow-sm animate-pulse">
                                    <i class="ph-fill ph-clock text-lg"></i> Waiting for Reply
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Scrollable Chat History -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        
                        <!-- Success Msg -->
                        <!-- Initial Question Bubble -->
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center font-bold text-xs text-slate-600 mt-1">
                                {{ substr($doubt->student->name, 0, 1) }}
                            </div>
                            <div class="flex-1 max-w-2xl bg-white border border-slate-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                                <h4 class="font-black text-slate-800 mb-2 border-b border-slate-100 pb-2 flex items-center gap-2">
                                    <i class="ph-fill ph-question text-primary-400 text-xl"></i>
                                    {{ $doubt->subject }}
                                </h4>
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $doubt->description }}</p>
                                
                                @if($doubt->image_path)
                                    <div class="mt-4 border border-slate-100 rounded-xl overflow-hidden shadow-sm inline-block">
                                        <img src="{{ Storage::url($doubt->image_path) }}" alt="Doubt Context" class="max-h-64 object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Faculty Reply Bubble (If resolved) -->
                        @if($doubt->is_resolved && $doubt->faculty_reply)
                            <div class="flex items-start gap-4 justify-end">
                                <div class="flex-1 max-w-2xl bg-primary-50 border border-primary-100 rounded-2xl rounded-tr-sm p-4 shadow-sm text-right">
                                    <div class="flex items-center justify-end gap-2 mb-2 border-b border-primary-200/50 pb-2">
                                        <span class="text-xs text-primary-400 font-semibold">{{ $doubt->updated_at->format('M j, Y g:i A') }}</span>
                                        <h4 class="font-black text-primary-900">Your Response</h4>
                                    </div>
                                    <p class="text-primary-900 text-sm leading-relaxed whitespace-pre-wrap text-left inline-block">{{ $doubt->faculty_reply }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-primary-500 flex-shrink-0 flex items-center justify-center font-bold text-xs text-white mt-1 shadow-sm">
                                    <i class="ph-bold ph-student"></i>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Input Area Form -->
                    @if(!$doubt->is_resolved)
                    <div class="bg-white border-t border-slate-200 p-4">
                        <form action="{{ route('faculty.courses.doubts.reply', [$course->id, $doubt->id]) }}" method="POST">
                            @csrf
                            <div class="relative rounded-2xl border border-slate-300 bg-slate-50 focus-within:bg-white focus-within:border-primary-400 focus-within:ring-2 focus-within:ring-primary-100 transition-all p-2 shadow-sm">
                                <textarea name="faculty_reply" rows="3" required placeholder="Type your detailed solution here..." class="w-full bg-transparent outline-none resize-none text-sm p-2 text-slate-700"></textarea>
                                
                                <div class="flex items-center justify-between border-t border-slate-200 pt-2 mt-2 px-2">
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-primary-700 hover:bg-primary-50 transition-colors"><i class="ph-bold ph-image text-lg"></i></button>
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-primary-700 hover:bg-primary-50 transition-colors"><i class="ph-bold ph-paperclip text-lg"></i></button>
                                    </div>
                                    <button type="submit" class="btn-primary text-white font-bold py-1.5 px-4 rounded-xl text-sm shadow-sm transition-colors flex items-center gap-2">
                                        Send Reply <i class="ph-bold ph-paper-plane-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
            @endforeach
            
        </div>
    </div>
</div>

@endsection
