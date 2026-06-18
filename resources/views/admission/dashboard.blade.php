@extends('layouts.admission')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="pt-2 space-y-6">

    {{-- Alert: needs follow-up (expired trials) --}}
    @if($stats['needs_followup'] > 0)
    <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 font-semibold text-sm">
        <span class="text-xl">⏰</span>
        <div>
            <p class="font-black">{{ $stats['needs_followup'] }} contact(s) need follow-up!</p>
            <p class="text-xs font-medium mt-0.5 opacity-70">Their trial access has expired — call them now to close the deal.</p>
        </div>
        <a href="{{ route('admission.contacts.index') }}" class="ml-auto shrink-0 px-3 py-1.5 text-xs font-black bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">View All</a>
    </div>
    @endif

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $cards = [
                ['label' => 'Total Leads',     'value' => $stats['total_contacts'],  'color' => 'indigo', 'icon' => '👥'],
                ['label' => 'Pending Calls',   'value' => $stats['pending_calls'],   'color' => 'orange', 'icon' => '📞'],
                ['label' => 'Will Join',        'value' => $stats['will_join'],       'color' => 'blue',   'icon' => '🎯'],
                ['label' => 'Active Trials',   'value' => $stats['active_trials'],   'color' => 'green',  'icon' => '⏱️'],
                ['label' => 'Expiring Soon',   'value' => $stats['trials_expiring'], 'color' => 'amber',  'icon' => '⚠️'],
                ['label' => 'Need Follow-up',  'value' => $stats['needs_followup'],  'color' => 'red',    'icon' => '🔔'],
            ];
            $palettes = [
                'indigo' => ['bg-orange-50', 'text-indigo-700', 'text-indigo-400'],
                'orange' => ['bg-orange-50', 'text-orange-700', 'text-orange-400'],
                'blue'   => ['bg-orange-50',   'text-blue-700',   'text-blue-400'],
                'green'  => ['bg-emerald-50','text-emerald-7 00','text-emerald-400'],
                'amber'  => ['bg-amber-50',  'text-amber-700',  'text-amber-400'],
                'red'    => ['bg-red-50',    'text-red-700',    'text-red-400'],
            ];
        @endphp
        @foreach($cards as $card)
        @php [$bg, $text, $sub] = $palettes[$card['color']]; @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide leading-tight">{{ $card['label'] }}</p>
                <span class="text-xl">{{ $card['icon'] }}</span>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Contacts --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-black text-slate-900 text-sm">Recent Contacts</h2>
                <a href="{{ route('admission.contacts.index') }}" class="text-xs text-indigo-500 font-bold hover:text-indigo-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentContacts as $c)
                <a href="{{ route('admission.contacts.show', $c) }}"
                   class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm text-white shrink-0"
                         style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                        {{ strtoupper(substr($c->display_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm truncate group-hover:text-orange-600">{{ $c->display_name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $c->display_email }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $c->call_status_badge['class'] }}">
                            {{ $c->call_status_badge['label'] }}
                        </span>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $c->outcome_badge['class'] }}">
                            {{ $c->outcome_badge['label'] }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">No contacts yet. Sync leads to begin.</div>
                @endforelse
            </div>
        </div>

        {{-- Trials expiring soon --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-black text-slate-900 text-sm">Trials Expiring Soon</h2>
                <a href="{{ route('admission.trials.index') }}" class="text-xs text-indigo-500 font-bold hover:text-indigo-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($expiringTrials as $t)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-black text-sm shrink-0">⏱</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm truncate">{{ $t->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $t->batch?->name }} — {{ $t->batch?->course?->name }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-black text-amber-600">
                            {{ $t->daysLeft() > 0 ? $t->daysLeft().'d left' : 'Expires today' }}
                        </p>
                        <p class="text-xs text-slate-400">{{ $t->expires_at->format('d M') }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">No trials expiring in the next 24 hours.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sync Tools --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 flex flex-wrap items-center gap-4">
        <div>
            <p class="font-black text-slate-800 text-sm">Import & Sync</p>
            <p class="text-xs text-slate-500 mt-0.5">Bring in new ad leads and registered users into the CRM</p>
        </div>
        <div class="flex gap-3 ml-auto flex-wrap">
            <form method="POST" action="{{ route('admission.sync.ad-leads') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white text-xs font-black rounded-xl hover:bg-orange-500 transition">
                    Sync Ad Leads
                </button>
            </form>
            <form method="POST" action="{{ route('admission.sync.users') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white text-xs font-black rounded-xl hover:bg-orange-500 transition">
                    Sync Users
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
