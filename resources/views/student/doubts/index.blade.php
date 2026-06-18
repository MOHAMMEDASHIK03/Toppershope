@extends('student.layouts.app')
@section('title', 'Doubt Forum')
@section('page_title', 'Doubt Forum')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Doubts & Q&A</h2>
        <p class="text-slate-500 text-sm mt-1">Ask questions and get answers from your faculty.</p>
    </div>
    <button type="button" onclick="document.getElementById('newDoubtForm').classList.toggle('hidden')" class="btn-primary text-sm py-2 px-4 rounded-lg font-semibold">
        <i class="ph ph-plus"></i> Ask doubt
    </button>
</div>

{{-- New Doubt Form --}}
<div id="newDoubtForm" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-6 hidden">
    <form action="{{ route('student.doubts.store') }}" method="POST">
        @csrf
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Post a New Doubt</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Subject / Title</label>
                <input type="text" name="title" required placeholder="e.g. Doubt in Integration by Parts"
                    class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Your Question</label>
                <textarea name="body" rows="4" required placeholder="Describe your doubt in detail..."
                    class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 resize-none"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary text-sm py-2.5 px-5 rounded-lg font-semibold">Post doubt</button>
                <button type="button" onclick="document.getElementById('newDoubtForm').classList.add('hidden')" class="btn-secondary text-sm py-2.5 px-5 rounded-lg">Cancel</button>
            </div>
        </div>
    </form>
</div>

{{-- Doubts List --}}
@if(isset($doubts) && $doubts->count() > 0)
    <div class="space-y-4">
        @foreach($doubts as $doubt)
            <a href="{{ route('student.doubts.show', $doubt->id) }}" class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 block hover:border-orange-200 transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 text-sm">{{ $doubt->subject }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $doubt->description }}</p>
                        <div class="flex items-center gap-3 mt-3 text-[10px] text-slate-500">
                            <span>{{ $doubt->created_at->diffForHumans() }}</span>
                            @if($doubt->replies_count > 0)
                                <span>{{ $doubt->replies_count }} {{ Str::plural('reply', $doubt->replies_count) }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $doubt->is_resolved ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-500/10 text-amber-400' }}">
                        {{ $doubt->is_resolved ? 'Resolved' : 'Open' }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-12 text-center">
        <h3 class="text-lg font-semibold text-slate-900 mb-2">No Doubts Yet</h3>
        <p class="text-slate-500 text-sm">Click "+ Ask Doubt" to post your first question.</p>
    </div>
@endif
@endsection
