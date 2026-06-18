@extends('student.layouts.app')
@section('title', $doubt->subject)
@section('page_title', 'Doubt Details')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('student.doubts') }}" class="text-sm text-slate-500 hover:text-primary-700 transition-colors mb-6 inline-block">← Back to Doubts</a>

    {{-- Original Doubt --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between mb-3">
            <h1 class="text-lg font-semibold text-slate-900">{{ $doubt->subject }}</h1>
            <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $doubt->is_resolved ? 'bg-primary-50 text-emerald-700' : 'bg-amber-500/10 text-amber-400' }}">
                {{ $doubt->is_resolved ? 'Resolved' : 'Open' }}
            </span>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed">{{ $doubt->description }}</p>
        <p class="text-[10px] text-slate-600 mt-3">{{ $doubt->created_at->format('d M Y, h:i A') }}</p>

        @if($doubt->faculty_reply)
            <div class="mt-4 p-4 rounded-xl bg-primary-500/5 border border-primary-500/10">
                <p class="text-xs font-bold text-emerald-400 mb-1">Faculty Response:</p>
                <p class="text-sm text-slate-600">{{ $doubt->faculty_reply }}</p>
            </div>
        @endif
    </div>

    {{-- Replies --}}
    @if($doubt->replies && $doubt->replies->count() > 0)
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Discussion</h3>
        <div class="space-y-3 mb-6">
            @foreach($doubt->replies as $reply)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 {{ $reply->user_id === auth()->id() ? 'border-l-4 border-l-primary-500' : 'border-l-4 border-l-primary-500' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-bold {{ $reply->user_id === auth()->id() ? 'text-primary-700' : 'text-emerald-400' }}">
                            {{ $reply->user->name ?? 'Unknown' }}
                        </span>
                        <span class="text-[10px] text-slate-600">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-600">{{ $reply->reply_text }}</p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Reply Form --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <form action="{{ route('student.doubts.reply', $doubt->id) }}" method="POST">
            @csrf
            <h3 class="text-sm font-semibold text-slate-900 mb-3">Add a Reply</h3>
            <textarea name="reply_text" rows="3" required placeholder="Type your reply..."
                class="w-full px-4 py-3 admin-input rounded-xl text-sm resize-none outline-none focus:ring-2 focus:ring-primary-600/15 focus:border-primary-500 resize-none mb-3"></textarea>
            <button type="submit" class="px-5 py-2.5 rounded-xl btn-primary text-white text-xs font-bold transition-all">
                Post Reply
            </button>
        </form>
    </div>
</div>
@endsection
