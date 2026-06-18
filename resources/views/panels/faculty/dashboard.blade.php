@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Faculty Hub</h1>
            <p class="text-gray-400">Manage course content, assessments, and student performance.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-primary-500/20 text-primary-500 border border-primary-500/30">
                Course Instructor
            </span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl border border-white/5">
            <p class="text-sm text-gray-400 font-medium mb-2">Live Batches</p>
            <p class="text-3xl font-black text-white">{{ $batches->count() }}</p>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-white/5">
            <p class="text-sm text-gray-400 font-medium mb-2">Total Uploaded Videos</p>
            <p class="text-3xl font-black text-white">41</p>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-white/5">
            <p class="text-sm text-gray-400 font-medium mb-2">Active Quizzes</p>
            <p class="text-3xl font-black text-white">8</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <h2 class="text-xl font-bold text-white mb-6">Your Batches overview</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        @foreach($batches as $batch)
        <div class="glass-panel p-6 rounded-3xl border border-white/5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path></svg>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-white border border-white/20">
                        {{ $batch->course->category?->name ?? '—' }}
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-primary transition-colors">{{ $batch->name }}</h3>
                <p class="text-sm text-gray-400 mb-6 font-mono">Course: {{ $batch->course->name }}</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button class="flex items-center justify-center p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white font-medium transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Upload Video
                    </button>
                    <button class="flex items-center justify-center p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white font-medium transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Attach PDF
                    </button>
                    <button class="flex items-center justify-center p-3 col-span-2 rounded-xl bg-secondary/10 border border-secondary/30 hover:bg-secondary/20 text-secondary font-medium transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Create New Assessment Quiz
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        
    </div>
</div>
@endsection
