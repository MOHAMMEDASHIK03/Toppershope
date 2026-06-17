@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Super Admin Console</h1>
            <p class="text-gray-400">System metrics and configurations.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-500/20 text-red-500 border border-red-500/30">
                Administrator
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-primary group">
            <p class="text-sm text-gray-400 font-medium mb-2">Total Students</p>
            <p class="text-3xl font-black text-white">{{ number_format($totalUsers ?? 50) }}</p>
            <div class="mt-4 flex items-center text-xs text-green-400 bg-green-500/10 px-2 py-1 rounded w-max">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +12% vs last week
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-secondary group">
            <p class="text-sm text-gray-400 font-medium mb-2">Total Enrollments</p>
            <p class="text-3xl font-black text-white">{{ number_format($totalEnrollments ?? 72) }}</p>
            <div class="mt-4 flex items-center text-xs text-green-400 bg-green-500/10 px-2 py-1 rounded w-max">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Active
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-yellow-500 group">
            <p class="text-sm text-gray-400 font-medium mb-2">Active Protected Sessions</p>
            <p class="text-3xl font-black text-white flex items-center gap-2">
                {{ number_format($activeSessions ?? 1) }} <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span></span>
            </p>
             <div class="mt-4 flex items-center text-xs text-yellow-500 bg-yellow-500/10 px-2 py-1 rounded w-max border border-yellow-500/20">
                Live Data DB Connected
            </div>
        </div>
        <div class="glass-panel p-6 rounded-2xl border-l-4 border-accent group">
            <p class="text-sm text-gray-400 font-medium mb-2">System Revenue (Mock)</p>
            <p class="text-3xl font-black text-white">₹8.4L</p>
            <div class="mt-4 flex items-center text-xs text-green-400 bg-green-500/10 px-2 py-1 rounded w-max">
                Razorpay OK
            </div>
        </div>
    </div>

    <!-- Management Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass-panel p-6 rounded-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Database Management</h2>
            <div class="space-y-4">
                <a href="#" class="block p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-white">Homepage CMS Builder</h3>
                        <p class="text-xs text-gray-400">Update hero banners, faculty lists, and public announcements.</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="#" class="block p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-white">Manage Courses & Batches</h3>
                        <p class="text-xs text-gray-400">Create or edit structural offerings.</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="#" class="block p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-white">Manage Users & Roles (RBAC)</h3>
                        <p class="text-xs text-gray-400">Assign faculty, staff, and admin statuses.</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="#" class="block p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-white">Payment Transactions Ledger</h3>
                        <p class="text-xs text-gray-400">View all Razorpay logs securely.</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Security & Auditing</h2>
            <div class="p-4 rounded-xl border border-red-500/30 bg-red-500/5 mb-4 relative overflow-hidden">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-white">Live Session Monitor</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500 text-white animate-pulse">Running</span>
                </div>
                <p class="text-xs text-gray-400 mb-4">Single-session middleware is actively defending against multi-device logins in the MySQL cluster.</p>
                <div class="h-1.5 w-full bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 w-[100%] rounded-full shadow-[0_0_10px_rgba(34,197,94,0.8)]"></div>
                </div>
            </div>
            
             <a href="#" class="block p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors flex justify-between items-center text-gray-400 hover:text-white">
                 <div class="flex items-center gap-3">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     <span class="font-bold text-sm">View System Audit Logs</span>
                 </div>
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
             </a>
        </div>
    </div>
</div>
@endsection
