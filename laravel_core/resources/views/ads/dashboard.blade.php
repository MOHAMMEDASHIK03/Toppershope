@extends('layouts.ads')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="py-4">
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Campaigns', 'value' => $totalCampaigns,  'icon' => 'megaphone',    'bg' => 'bg-orange-50',  'ic' => 'text-orange-500'],
            ['label' => 'Active Campaigns','value' => $activeCampaigns, 'icon' => 'check-circle',  'bg' => 'bg-green-50',   'ic' => 'text-green-500'],
            ['label' => 'Total Leads',     'value' => $totalLeads,      'icon' => 'users',         'bg' => 'bg-orange-50',    'ic' => 'text-blue-500'],
            ['label' => 'Enrol Interest',  'value' => $enrolLeads,      'icon' => 'graduation-cap','bg' => 'bg-purple-50',  'ic' => 'text-purple-500'],
        ] as $stat)
        <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 shadow-sm hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center shrink-0">
                <i class="ph-fill ph-{{ $stat['icon'] }} text-2xl {{ $stat['ic'] }}"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Global Popup Status Banner -->
    <div class="mb-8 p-4 rounded-2xl border {{ $popup->is_active ? 'bg-green-50 border-green-200' : 'bg-slate-50 border-slate-200' }} flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <i class="ph-fill ph-browser text-2xl {{ $popup->is_active ? 'text-green-500' : 'text-slate-400' }}"></i>
            <div>
                <p class="font-bold text-slate-900 text-sm">Global Homepage Popup</p>
                <p class="text-xs text-slate-500">{{ $popup->is_active ? 'Currently ACTIVE — visible to all homepage visitors' : 'Currently inactive' }}</p>
            </div>
        </div>
        <a href="{{ route('ads.popups.index') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:border-orange-400 hover:text-orange-600 transition">
            Manage Popup →
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Campaigns -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-black text-slate-900">Recent Campaigns</h3>
                <a href="{{ route('ads.campaigns.index') }}" class="text-xs text-orange-500 font-bold hover:underline">View All →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentCampaigns as $c)
                <div class="px-6 py-3 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ $c->title }}</p>
                        <p class="text-xs text-slate-400 font-mono">/c/{{ $c->slug }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-md text-xs font-bold {{ $c->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }} shrink-0">
                        {{ $c->is_active ? 'Active' : 'Paused' }}
                    </span>
                </div>
                @empty
                <p class="px-6 py-8 text-sm text-slate-400 text-center">No campaigns yet. <a href="{{ route('ads.campaigns.create') }}" class="text-orange-500 font-bold">Create one →</a></p>
                @endforelse
            </div>
        </div>

        <!-- Recent Leads -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-black text-slate-900">Recent Leads</h3>
                <a href="{{ route('ads.leads.index') }}" class="text-xs text-orange-500 font-bold hover:underline">View All →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentLeads as $lead)
                <div class="px-6 py-3 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-black text-xs shrink-0">
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-slate-900 text-sm truncate">{{ $lead->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $lead->campaign?->title ?? '—' }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-md text-xs font-bold {{ $lead->enquiry_type === 'enrol' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} shrink-0">
                        {{ ucfirst($lead->enquiry_type) }}
                    </span>
                </div>
                @empty
                <p class="px-6 py-8 text-sm text-slate-400 text-center">No leads yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
