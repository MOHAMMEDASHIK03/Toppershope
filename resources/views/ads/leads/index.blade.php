@extends('layouts.ads')
@section('title', 'Leads')
@section('page_title', 'Lead Enquiries')

@section('content')
<div class="py-4">
    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="campaign_id" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500">
            <option value="">All Campaigns</option>
            @foreach($campaigns as $c)
                <option value="{{ $c->id }}" {{ request('campaign_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
            @endforeach
        </select>
        <select name="type" onchange="this.form.submit()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500">
            <option value="">All Types</option>
            <option value="enrol"    {{ request('type') === 'enrol'    ? 'selected' : '' }}>Enrol</option>
            <option value="interest" {{ request('type') === 'interest' ? 'selected' : '' }}>Interest</option>
        </select>
        @if(request('campaign_id') || request('type'))
            <a href="{{ route('ads.leads.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-xl text-sm text-slate-600 font-semibold transition">Clear Filters</a>
        @endif
        <span class="ml-auto self-center text-sm text-slate-400">{{ $leads->total() }} lead(s)</span>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($leads->isEmpty())
            <div class="py-20 text-center">
                <i class="ph-fill ph-users text-5xl text-slate-200 block mb-3"></i>
                <p class="font-bold text-slate-500">No leads found</p>
            </div>
        @else
        <div class="panel-table-wrap">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Campaign</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Message</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($leads as $lead)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-black text-xs shrink-0">
                                    {{ strtoupper(substr($lead->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $lead->name }}</p>
                                    @if($lead->city)<p class="text-xs text-slate-400">{{ $lead->city }}</p>@endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-slate-900 font-semibold">{{ $lead->phone }}</p>
                            <a href="mailto:{{ $lead->email }}" class="text-primary-500 text-xs hover:underline">{{ $lead->email }}</a>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-semibold text-slate-700 max-w-[160px] truncate">{{ $lead->campaign?->title ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $lead->enquiry_type === 'enrol' ? 'bg-primary-100 text-primary-700' : 'bg-primary-100 text-primary-700' }}">
                                {{ $lead->enquiry_type === 'enrol' ? 'Enrol' : 'Interest' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 max-w-xs">
                            <p class="text-xs text-slate-500 truncate">{{ $lead->message ?: '—' }}</p>
                        </td>
                        <td class="px-4 py-4 text-xs text-slate-400 whitespace-nowrap">
                            {{ $lead->created_at->format('d M Y, H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-panel.pagination :paginator="$leads" />
        @endif
    </div>
</div>
@endsection
