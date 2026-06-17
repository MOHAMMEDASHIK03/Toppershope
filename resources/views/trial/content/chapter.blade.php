@extends('trial.layouts.app')

@section('title', $chapter->name)
@section('page_title', $chapter->name)

@push('header-actions')
    <a href="{{ route('trial.dashboard') }}" class="btn-secondary text-sm py-1.5 px-3">
        <i class="ph ph-arrow-left"></i>
        Back to subjects
    </a>
@endpush

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="max-w-4xl">
    <div class="flex items-end justify-between mb-6 gap-4 flex-wrap">
        <div>
            <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider">{{ $chapter->subject->name }}</p>
            <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ $chapter->name }}</h2>
        </div>
        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-bold text-xs rounded-full">FREE PREVIEW</span>
    </div>

    @if($chapter->units->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-12 text-center text-slate-400">
            Units will appear here soon.
        </div>
    @else
        <div class="space-y-5">
            @foreach($chapter->units as $unit)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm" x-data="{ open: true }">
                    <button @click="open = !open" type="button" class="w-full flex justify-between items-center bg-slate-50 border-b border-slate-100 px-6 py-4 hover:bg-slate-100 transition focus:outline-none">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-700 w-6 h-6 rounded flex items-center justify-center text-xs">U{{ $unit->order }}</span>
                            {{ $unit->name }}
                        </h3>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-slate-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" class="px-6 py-4 space-y-3">
                        @forelse($unit->videos as $video)
                            <a href="{{ $video->video_url }}" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 p-3 rounded-lg border border-slate-100 hover:border-indigo-300 hover:bg-indigo-50 transition group">
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-500 font-bold text-lg flex items-center justify-center shrink-0">▶</div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-800 group-hover:text-indigo-700 text-sm leading-tight">{{ $video->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $video->duration_minutes }} mins</p>
                                </div>
                            </a>
                        @empty
                        @endforelse

                        @forelse($unit->notes as $note)
                            <a href="{{ Storage::url($note->file_path) }}" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 p-3 rounded-lg border border-slate-100 hover:border-amber-300 hover:bg-amber-50 transition group">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-500 font-bold text-lg flex items-center justify-center shrink-0">📄</div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-800 group-hover:text-amber-700 text-sm leading-tight">{{ $note->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">PDF Document</p>
                                </div>
                            </a>
                        @empty
                        @endforelse

                        @forelse($unit->quizzes as $quiz)
                            <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-100 bg-slate-50 opacity-80">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-500 font-bold text-lg flex items-center justify-center shrink-0">✍️</div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-800 text-sm leading-tight">
                                        {{ $quiz->title }}
                                        <span class="text-[10px] ml-2 px-1.5 py-0.5 bg-slate-200 rounded font-medium">Full quiz engine locked in trial</span>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $quiz->duration_minutes }} mins</p>
                                </div>
                            </div>
                        @empty
                        @endforelse

                        @if($unit->videos->isEmpty() && $unit->notes->isEmpty() && $unit->quizzes->isEmpty())
                            <p class="text-sm text-slate-400 text-center py-2">No content added yet.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
