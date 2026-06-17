@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Doubt Resolution Center</h1>
            <p class="text-gray-400">Answer student queries and close active support tickets.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-secondary/20 text-secondary border border-secondary/30">
                Support Staff
            </span>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-yellow-500 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Open Tickets</p>
                <p class="text-3xl font-black text-white">{{ $doubts->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center text-yellow-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Resolved Today</p>
                <p class="text-3xl font-black text-white">12</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center text-green-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-primary flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Avg Response Time</p>
                <p class="text-3xl font-black text-white">45<span class="text-base text-gray-500 font-medium ml-1">mins</span></p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Ticket Queue -->
    <div class="glass-panel rounded-3xl border border-white/5 overflow-hidden">
        <div class="p-6 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <span class="relative flex h-3 w-3 mr-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                </span>
                Active Queue
            </h2>
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Search ticket # or subject..." class="bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-secondary transition-colors w-full md:w-64">
            </div>
        </div>
        
        <div class="divide-y divide-white/5">
            @forelse($doubts as $doubt)
            <div class="p-6 hover:bg-white/5 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Open</span>
                        <span class="text-xs text-secondary font-mono">#{{ explode('-', $doubt->uuid)[0] }}</span>
                        <span class="text-xs text-gray-500 font-medium">{{ $doubt->batch ? $doubt->batch->name : 'General' }}</span>
                    </div>
                    <a href="{{ route('student.doubts.show', $doubt->uuid) }}" class="text-lg font-bold text-white group-hover:text-primary transition-colors block mb-1">
                        {{ $doubt->subject }}
                    </a>
                    <p class="text-sm text-gray-400 line-clamp-1 break-all">{{ $doubt->description }}</p>
                </div>
                
                <div class="shrink-0 flex items-center justify-between md:flex-col md:items-end gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-[10px] text-white font-bold border border-white/10">
                            {{ substr($doubt->user->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $doubt->user->name }}</span>
                    </div>
                    <span class="text-xs text-gray-500 font-mono">{{ $doubt->created_at->diffForHumans() }}</span>
                </div>
                
                <a href="{{ route('student.doubts.show', $doubt->uuid) }}" class="hidden md:flex shrink-0 w-10 h-10 rounded-full bg-white/5 border border-white/10 items-center justify-center text-gray-400 group-hover:text-white group-hover:border-white/30 transition-all group-hover:bg-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto bg-green-500/10 rounded-full flex items-center justify-center text-green-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Inbox Zero!</h3>
                <p class="text-gray-400 text-sm">All student doubts have been resolved successfully.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
