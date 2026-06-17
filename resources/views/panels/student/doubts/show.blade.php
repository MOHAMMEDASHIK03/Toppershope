@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-32">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Thread Header -->
        <div class="mb-8">
            <a href="{{ route('student.doubts.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Doubts
            </a>
            
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-2xl md:text-3xl font-black text-white leading-tight">{{ $doubt->subject }}</h1>
                <div class="shrink-0 mt-1">
                    @if($doubt->status === 'open')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-secondary/10 text-secondary border border-secondary/20 block">Open</span>
                    @elseif($doubt->status === 'resolved')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20 block">Resolved</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-500/10 text-gray-400 border border-gray-500/20 block">Closed</span>
                    @endif
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500 mt-2 font-mono">Ticket #{{ $doubt->uuid }}</div>
        </div>

        <div class="space-y-6">
            
            <!-- Original Post -->
            <div class="glass-panel p-6 md:p-8 rounded-3xl border border-white/5 relative">
                <div class="absolute -left-3 top-8 w-6 h-px bg-white/10 hidden md:block"></div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold shadow-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-white">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Student</div>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-gray-500">{{ $doubt->created_at->format('M j, Y g:i A') }}</div>
                </div>
                
                <div class="prose prose-invert prose-p:text-gray-300 max-w-none">
                    <p>{!! nl2br(e($doubt->description)) !!}</p>
                </div>
            </div>

            <!-- Replies -->
            @foreach($doubt->replies as $reply)
                <div class="glass-panel p-6 md:p-8 rounded-3xl border {{ $reply->user->hasRole('faculty') || $reply->user->hasRole('admin') ? 'border-secondary/30 bg-secondary/5' : 'border-white/5' }} relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            @if($reply->user->hasRole('faculty') || $reply->user->hasRole('admin'))
                                <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center text-secondary font-bold border border-secondary/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white flex items-center gap-2">
                                        {{ $reply->user->name }}
                                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="text-[10px] text-secondary uppercase tracking-widest font-bold">Faculty Support</div>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-white font-bold border border-white/10">
                                    {{ substr($reply->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white">{{ $reply->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Student</div>
                                </div>
                            @endif
                        </div>
                        <div class="text-xs font-medium text-gray-500">{{ $reply->created_at->diffForHumans() }}</div>
                    </div>
                    
                    <div class="prose prose-invert prose-p:text-gray-300 max-w-none">
                        <p>{!! nl2br(e($reply->reply_text)) !!}</p>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Reply Draft box -->
        @if($doubt->status !== 'closed')
        <div class="mt-8">
            <form action="{{ route('student.doubts.reply', $doubt->uuid) }}" method="POST">
                @csrf
                <div class="relative">
                    <textarea name="reply_text" required rows="3" placeholder="Type your reply or follow-up question here..." class="w-full bg-black/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-primary transition-colors resize-none pr-32"></textarea>
                    <div class="absolute bottom-4 right-4">
                        <button type="submit" class="px-6 py-2 bg-white text-black hover:bg-gray-200 font-bold rounded-xl transition-colors">
                            Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @else
        <div class="mt-8 text-center p-6 border border-white/5 rounded-2xl bg-white/5 text-gray-400 text-sm font-medium">
            This doubt thread has been closed by the faculty.
        </div>
        @endif

    </div>
</div>
@endsection
