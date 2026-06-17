@extends('trial.layouts.app')

@section('title', 'My Trial')
@section('page_title', $batch->course->name)

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Welcome to your preview</h2>
        <p class="text-sm text-slate-500 mt-1">
            During this trial, you have full access to the <strong>first chapter</strong> of every subject in the {{ $batch->name }} batch.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($subjects as $subject)
            @php
                $chapter = $subject->chapters->first();
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">{{ $subject->name }}</p>
                        <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ $chapter?->name ?? 'No Chapters Yet' }}</h3>
                    </div>
                    <span class="text-2xl opacity-80">
                        {{ str_contains(strtolower($subject->name), 'phy') ? '⚛️' : (str_contains(strtolower($subject->name), 'chem') ? '🧪' : '📚') }}
                    </span>
                </div>

                @if($chapter)
                    @php
                        $units = $chapter->units()->withCount(['videos', 'notes', 'quizzes'])->get();
                        $vCount = $units->sum('videos_count');
                        $nCount = $units->sum('notes_count');
                        $qCount = $units->sum('quizzes_count');
                    @endphp
                    <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold mb-5">
                        <span>▶ {{ $vCount }} Videos</span>
                        <span>📄 {{ $nCount }} Notes</span>
                        <span>✍️ {{ $qCount }} Quizzes</span>
                    </div>
                    <a href="{{ route('trial.content.chapter', $chapter->id) }}"
                       class="block w-full text-center py-2.5 bg-emerald-50 text-emerald-700 font-semibold rounded-lg border border-emerald-200 hover:bg-emerald-100 transition text-sm">
                        Start Learning First Chapter →
                    </a>
                @else
                    <p class="text-xs text-slate-400">Content coming soon.</p>
                @endif
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-white border border-slate-200 rounded-xl">
                No subjects loaded for this batch yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
