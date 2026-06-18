@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">{{ Auth::user()->name }}</span></h1>
            <p class="text-gray-400">Your learning journey continues here. Keep pushing forward!</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/20 text-primary border border-primary/30">
                Student Profile
            </span>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Active Courses</p>
                <p class="text-3xl font-black text-white">2</p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-primary/20 flex items-center justify-center border border-primary/30">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between border-l-4 border-secondary">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Overall Progress</p>
                <p class="text-3xl font-black text-white">45<span class="text-lg text-secondary">%</span></p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-secondary/20 flex items-center justify-center border border-secondary/30">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 font-medium mb-1">Doubt Tickets</p>
                <p class="text-3xl font-black text-white">1 <span class="text-sm font-normal text-green-400">Resolved</span></p>
            </div>
            <div class="h-12 w-12 rounded-xl bg-accent/20 flex items-center justify-center border border-accent/30">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Enrolled Courses -->
            <div class="glass-panel rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6">My Enrolled Courses</h2>
                
                <div class="space-y-4">
                    @forelse ($enrollments as $enrollment)
                    <div class="group border border-white/10 rounded-xl p-4 bg-white/5 hover:bg-white/10 transition-colors flex flex-col sm:flex-row gap-4 relative overflow-hidden">
                        <div class="h-24 w-full sm:w-32 rounded-lg bg-gray-800 flex-shrink-0 relative overflow-hidden border border-white/10">
                            <!-- Placeholder Image -->
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-accent/20 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-secondary mb-1 block">{{ $enrollment->batch->course->category?->name }}</span>
                                    <h3 class="text-white font-bold">{{ $enrollment->batch->name }}</h3>
                                </div>
                                <span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded font-bold uppercase">Active</span>
                            </div>
                            <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $enrollment->batch->course->description }}</p>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-primary to-secondary w-[0%]"></div>
                                </div>
                                <span class="text-xs text-gray-400 font-medium w-8 text-right">0%</span>
                            </div>
                        </div>
                        <a href="{{ route('course.show', $enrollment->batch->uuid) }}" class="absolute inset-0 z-10"></a>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-2 group-hover:translate-x-0 duration-300 pointer-events-none">
                             <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center shadow-[0_0_15px_rgba(27,42,255,0.5)]">
                                 <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                             </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-gray-400 text-sm mb-4">You are not enrolled in any active batches.</p>
                        <a href="/" class="inline-flex items-center px-4 py-2 bg-primary/20 text-primary border border-primary/30 rounded-lg text-sm font-bold hover:bg-primary/30 transition-colors">Browse Courses</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Test Scores -->
            <div class="glass-panel rounded-2xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Recent Performance</h2>
                    <a href="#" class="text-sm text-primary hover:text-secondary transition-colors">View Analytics</a>
                </div>
                
                <div class="panel-table-wrap">
                    <table class="panel-table w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-xs text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 font-semibold">Test Name</th>
                                <th class="pb-3 font-semibold">Date</th>
                                <th class="pb-3 font-semibold text-right">Score</th>
                                <th class="pb-3 font-semibold text-right">Percentile</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                <td class="py-4 font-medium text-white">Weekly Mock - Physics</td>
                                <td class="py-4 text-gray-400">Oct 12, 2026</td>
                                <td class="py-4 text-right text-white font-bold">85/100</td>
                                <td class="py-4 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-500/20 text-green-400">
                                        98.2 %ile
                                    </span>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 font-medium text-white">Minor Test 3 - Math</td>
                                <td class="py-4 text-gray-400">Oct 05, 2026</td>
                                <td class="py-4 text-right text-white font-bold">72/100</td>
                                <td class="py-4 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/20 text-yellow-400">
                                        89.5 %ile
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Upcoming Schedule -->
            <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <h2 class="text-xl font-bold text-white mb-6">Today's Schedule</h2>
                
                <div class="space-y-4">
                    <div class="relative pl-6 pb-4 border-l border-white/10 last:border-0 last:pb-0">
                        <div class="absolute left-[-5px] top-1.5 h-2.5 w-2.5 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)] animate-pulse"></div>
                        <p class="text-xs text-red-400 font-bold mb-1">LIVE NOW • 10:00 AM</p>
                        <p class="text-sm font-bold text-white mb-1">Kinematics Revision</p>
                        <p class="text-xs text-gray-400">Dr. H.C. Verma Appr. • 2 Hrs</p>
                        <button class="mt-3 px-4 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded flex items-center gap-2 transition-colors">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                            Join Class
                        </button>
                    </div>
                    
                    <div class="relative pl-6 pb-4 border-l border-white/10 last:border-0 last:pb-0">
                        <div class="absolute left-[-5px] top-1.5 h-2.5 w-2.5 rounded-full bg-gray-600 border-2 border-background"></div>
                        <p class="text-xs text-gray-500 font-medium mb-1">02:00 PM</p>
                        <p class="text-sm font-bold text-gray-300 mb-1">Organic Chemistry Doubt Session</p>
                        <p class="text-xs text-gray-500">Subhash Sir • 1 Hr</p>
                    </div>
                </div>
            </div>

            <!-- Notice Board -->
            <div class="glass-panel rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-6">Notice Board</h2>
                
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-primary/20 text-primary text-[10px] uppercase font-bold px-2 py-0.5 rounded">Alert</span>
                            <span class="text-xs text-gray-500">2 hours ago</span>
                        </div>
                        <p class="text-sm text-gray-300 group-hover:text-white transition-colors">All India Mock Test Date Rescheduled to Sunday, 10 AM.</p>
                    </div>
                    
                    <div class="p-4 rounded-xl bg-white/5 border border-white/10 hover:border-accent/50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-accent/20 text-accent text-[10px] uppercase font-bold px-2 py-0.5 rounded">Update</span>
                            <span class="text-xs text-gray-500">Yesterday</span>
                        </div>
                        <p class="text-sm text-gray-300 group-hover:text-white transition-colors">New PDF Material: "High Yield Biology Diagrams" uploaded in your library.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
