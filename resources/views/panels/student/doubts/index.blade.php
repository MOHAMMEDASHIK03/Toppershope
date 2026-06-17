@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Doubt Resolution</h1>
                <p class="text-gray-400 mt-1">Get your questions answered by expert faculty</p>
            </div>
            <button x-data @click="$dispatch('open-doubt-modal')" class="btn-primary py-2.5 px-6 whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ask a Doubt
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($doubts as $doubt)
                <a href="{{ route('student.doubts.show', $doubt->uuid) }}" class="glass-panel p-5 rounded-2xl border border-white/5 hover:border-white/10 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                            @if($doubt->status === 'open')
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($doubt->status === 'resolved')
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-white font-bold group-hover:text-primary transition-colors text-lg">{{ $doubt->subject }}</h3>
                            <div class="flex flex-wrap items-center gap-2 md:gap-4 mt-2">
                                <span class="text-xs text-gray-500 font-medium font-mono">{{ $doubt->uuid }}</span>
                                <span class="hidden md:block w-1.5 h-1.5 rounded-full bg-white/10"></span>
                                <span class="text-xs text-gray-400">{{ $doubt->created_at->diffForHumans() }}</span>
                                <span class="hidden md:block w-1.5 h-1.5 rounded-full bg-white/10"></span>
                                @if($doubt->status === 'open')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-secondary/10 text-secondary border border-secondary/20">Open</span>
                                @elseif($doubt->status === 'resolved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20">Resolved</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-500/10 text-gray-400 border border-gray-500/20">Closed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-gray-500 group-hover:text-white transition-colors shrink-0 flex justify-end md:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            @empty
                <div class="glass-panel p-12 rounded-3xl border border-white/5 text-center">
                    <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">No doubts asked yet</h3>
                    <p class="text-gray-400 max-w-sm mx-auto">When you encounter problems during your studies, you can ask them here.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Ask Doubt Modal -->
<div x-data="{ open: false }" 
     @open-doubt-modal.window="open = true" 
     x-show="open" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
     style="display: none;">
    
    <div x-show="open" @click="open = false" x-transition.opacity class="absolute inset-0 bg-background/90 backdrop-blur-sm"></div>
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="glass-panel w-full max-w-xl rounded-2xl border border-white/10 shadow-2xl relative z-10 overflow-hidden">
        
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-white/5">
            <h3 class="text-lg font-bold text-white">Ask a Doubt</h3>
            <button @click="open = false" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('student.doubts.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Related Batch</label>
                    <select name="batch_id" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-colors appearance-none">
                        <option value="" disabled selected>Select Batch...</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Subject / Topic</label>
                    <input type="text" name="subject" required placeholder="e.g. Centripetal Force Calculation" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-colors">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Detailed Description</label>
                    <textarea name="description" required rows="4" placeholder="Explain your doubt clearly so faculty can help..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl border border-white/10 hover:bg-white/5 text-white font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-opacity-90 text-white font-bold shadow-[0_0_15px_rgba(27,42,255,0.4)] transition-colors">
                    Submit Doubt
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
