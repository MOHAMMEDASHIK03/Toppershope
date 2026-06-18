@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pt-32">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Admissions Command Center</h1>
            <p class="text-gray-400">Manage student leads, offline registrations, and enrollment tracking.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-primary-500/20 text-primary-500 border border-primary-500/30">
                Admissions Team
            </span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="glass-panel p-6 rounded-2xl border border-white/5">
            <p class="text-sm text-gray-400 font-medium mb-2">Today's Leads</p>
            <p class="text-3xl font-black text-white">14</p>
            <span class="text-xs text-green-500 mt-2 block">+3 from web campaign</span>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-white/5 bg-primary/5 border-primary/20">
            <p class="text-sm text-primary font-medium mb-2">Conversions (This Week)</p>
            <p class="text-3xl font-black text-white">42</p>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-white/5">
            <p class="text-sm text-gray-400 font-medium mb-2">Follow-ups Pending</p>
            <p class="text-3xl font-black text-yellow-500">18</p>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-white/5 flex items-center justify-center">
            <button class="w-full h-full min-h-[100px] rounded-xl bg-white text-black font-bold flex flex-col items-center justify-center hover:bg-gray-200 transition-colors">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Manual Enrollment
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Online Enrollments via Razorpay -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl border border-white/5">
            <h2 class="text-xl font-bold text-white mb-6">Recent Online Enrollments</h2>
            
            <div class="space-y-4">
                @foreach($enrollments as $enrollment)
                <div class="p-4 rounded-xl border border-white/10 bg-white/5 flex items-center justify-between group hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold border border-white/10">
                            {{ substr($enrollment->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-white">{{ $enrollment->user->name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono">{{ $enrollment->user->phone ?? $enrollment->user->email }}</div>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <div class="text-sm text-gray-300 font-medium">{{ $enrollment->batch->name }}</div>
                        <div class="text-[10px] text-green-500 font-bold uppercase tracking-wider">₹{{ number_format($enrollment->amount_paid, 2) }} • Paid</div>
                    </div>
                    <div class="sm:hidden text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Call List / Lead Tracking -->
        <div class="glass-panel p-6 rounded-3xl border border-white/5">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">Call List</h2>
                <span class="px-2 py-1 rounded text-[10px] font-bold bg-red-500/20 text-red-500 border border-red-500/30">Priority</span>
            </div>
            
            <div class="space-y-4">
                <!-- Mock Leads -->
                <div class="p-4 rounded-xl border border-white/5 bg-black/30">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-bold text-gray-200">Rahul Sharma</div>
                        <span class="text-xs text-yellow-500">Left Checkout</span>
                    </div>
                    <div class="text-sm text-primary mb-3 font-mono">+91 98765 43210</div>
                    <div class="flex gap-2">
                        <button class="flex-1 py-1.5 rounded-lg bg-green-500/20 text-green-400 text-xs font-bold border border-green-500/30 hover:bg-green-500/30">Called</button>
                        <button class="flex-1 py-1.5 rounded-lg bg-yellow-500/20 text-yellow-500 text-xs font-bold border border-yellow-500/30 hover:bg-yellow-500/30">Snooze</button>
                    </div>
                </div>
                
                <div class="p-4 rounded-xl border border-white/5 bg-black/30">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-bold text-gray-200">Sneha Patel</div>
                        <span class="text-xs text-primary-400">Inquiry Form</span>
                    </div>
                    <div class="text-sm text-primary mb-3 font-mono">+91 99887 76655</div>
                    <div class="flex gap-2">
                        <button class="flex-1 py-1.5 rounded-lg bg-green-500/20 text-green-400 text-xs font-bold border border-green-500/30 hover:bg-green-500/30">Called</button>
                        <button class="flex-1 py-1.5 rounded-lg bg-yellow-500/20 text-yellow-500 text-xs font-bold border border-yellow-500/30 hover:bg-yellow-500/30">Snooze</button>
                    </div>
                </div>
            </div>
            
            <button class="w-full mt-6 py-2 rounded-xl border border-white/10 text-gray-400 hover:text-white transition-colors text-sm font-medium">View All Leads</button>
        </div>
        
    </div>
</div>
@endsection
