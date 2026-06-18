@extends('layouts.ads')
@section('title', 'Campaigns')
@section('page_title', 'Ad Campaigns')

@section('content')
<div class="py-4">
    <div class="flex items-center justify-between mb-6">
        <p class="text-slate-500 text-sm">{{ $campaigns->total() }} campaign(s) total</p>
        <a href="{{ route('ads.campaigns.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 hover:bg-primary-700 text-white font-bold rounded-xl transition shadow-md shadow-primary-500/25 text-sm">
            <i class="ph-bold ph-plus text-base"></i> New Campaign
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($campaigns->isEmpty())
        <div class="py-20 text-center">
            <i class="ph-fill ph-megaphone text-5xl text-slate-200 mb-3 block"></i>
            <p class="font-bold text-slate-600">No campaigns yet</p>
            <a href="{{ route('ads.campaigns.create') }}" class="mt-4 inline-block px-5 py-2 bg-primary-500 text-white font-bold rounded-xl text-sm">Create First Campaign →</a>
        </div>
        @else
        <div class="panel-table-wrap">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Campaign</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">URL</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Leads</th>
                        <th class="px-4 py-3 text-left text-xs font-black text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-black text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($campaigns as $c)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-8 rounded-full shrink-0" style="background: {{ $c->primary_color }}"></div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $c->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $c->course_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ $c->publicUrl() }}" target="_blank" class="text-primary-500 font-mono text-xs hover:underline flex items-center gap-1">
                                /c/{{ $c->slug }} <i class="ph-bold ph-arrow-square-out"></i>
                            </a>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-black text-slate-900">{{ $c->leads_count }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <form method="POST" action="{{ route('ads.campaigns.toggle', $c) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $c->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                    {{ $c->is_active ? 'Active' : 'Paused' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ $c->publicUrl() }}" target="_blank" class="p-2 text-slate-400 hover:text-primary-500 hover:bg-primary-50 rounded-lg transition" title="View Live">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </a>
                                <a href="{{ route('ads.campaigns.edit', $c) }}" class="p-2 text-slate-400 hover:text-primary-500 hover:bg-primary-50 rounded-lg transition" title="Edit">
                                    <i class="ph-bold ph-pencil text-base"></i>
                                </a>
                                <form method="POST" action="{{ route('ads.campaigns.destroy', $c) }}" onsubmit="return confirm('Delete this campaign?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <i class="ph-bold ph-trash text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-panel.pagination :paginator="$campaigns" />
        @endif
    </div>
</div>
@endsection
