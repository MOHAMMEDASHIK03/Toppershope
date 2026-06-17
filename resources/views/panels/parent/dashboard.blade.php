@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Guardian Portal</h1>
            <p class="text-gray-400">Monitor your ward's attendance, test scores, and learning progress.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-500/20 text-green-500 border border-green-500/30">
                Parent Account
            </span>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl border border-white/5 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/10 rounded-full blur-2xl"></div>
            <p class="text-sm text-gray-400 font-medium mb-2">Overall Attendance</p>
            <div class="flex items-end gap-3">
                <p class="text-4xl font-black text-white">92<span class="text-xl text-gray-400">%</span></p>
                <div class="flex items-center text-xs text-green-400 mb-1.5">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    2% up
                </div>
            </div>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border border-white/5 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/10 rounded-full blur-2xl"></div>
            <p class="text-sm text-gray-400 font-medium mb-2">Average Test Score</p>
            <div class="flex items-end gap-3">
                <p class="text-4xl font-black text-white">78<span class="text-xl text-gray-400">%</span></p>
                <span class="text-xs uppercase font-bold tracking-wider text-secondary border border-secondary/30 px-2 py-0.5 rounded mb-1.5">Top 15%</span>
            </div>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border border-white/5 relative overflow-hidden flex flex-col justify-center">
            <button class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors w-full group">
                <div class="text-left">
                    <h4 class="font-bold text-white group-hover:text-primary transition-colors">Download Report</h4>
                    <p class="text-xs text-gray-400">PDF Monthly Summary</p>
                </div>
                <svg class="w-6 h-6 text-gray-500 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Assessments -->
        <div class="glass-panel p-6 rounded-3xl border border-white/5">
            <h2 class="text-xl font-bold text-white mb-6">Recent Assessments</h2>
            <div class="space-y-4">
                
                <div class="p-4 rounded-xl border border-white/10 bg-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-white mb-1">Rotational Mechanics Phase 1</h3>
                        <p class="text-xs text-gray-400 font-mono">Attempted: 2 Days Ago</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-2xl font-black text-green-400">32<span class="text-sm text-gray-500">/40</span></div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 rounded-xl border border-white/10 bg-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-white mb-1">Electrostatics Mega Quiz</h3>
                        <p class="text-xs text-gray-400 font-mono">Attempted: 1 Week Ago</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-2xl font-black text-yellow-500">21<span class="text-sm text-gray-500">/40</span></div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <button class="w-full mt-6 py-2 rounded-xl border border-white/10 text-gray-400 hover:text-white transition-colors text-sm font-medium">View Full History</button>
        </div>

        <!-- Activity & Remarks -->
        <div class="glass-panel p-6 rounded-3xl border border-white/5">
            <h2 class="text-xl font-bold text-white mb-6">Faculty Remarks</h2>
            
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/10 before:to-transparent">
                
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-black/50 text-secondary shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10 backdrop-blur-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-white/5 bg-white/5 ml-4 md:ml-0 shadow-lg group-hover:border-secondary/30 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-bold text-white text-sm">Physics Dept.</h4>
                            <time class="text-[10px] text-gray-500 font-mono">Yesterday</time>
                        </div>
                        <p class="text-sm text-gray-400">Excellent performance in the recent Rotational Mechanics test. Keep up the momentum.</p>
                    </div>
                </div>
                
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-black/50 text-gray-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10 backdrop-blur-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border border-white/5 bg-white/5 ml-4 md:ml-0 shadow-lg group-hover:border-white/20 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-bold text-white text-sm">System</h4>
                            <time class="text-[10px] text-gray-500 font-mono">Last Week</time>
                        </div>
                        <p class="text-sm text-gray-400">Absent for Live Class Session: Chemistry Kinetics.</p>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>
@endsection
