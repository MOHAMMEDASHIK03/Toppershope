@extends('admin.layouts.app')
@section('title', 'Active Sessions')
@section('page_title', 'Active Sessions')

@section('content')
<x-admin.page-header title="Live session monitor" subtitle="Track and manage active user sessions across the ecosystem." />

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <x-admin.stat-card label="Active sessions" :value="$sessions->count()" accent="indigo" />
    <x-admin.stat-card label="Unique IPs" :value="$sessions->pluck('ip_address')->unique()->count()" accent="emerald" />
    <x-admin.stat-card label="Active users" :value="$sessions->pluck('user_id')->unique()->count()" accent="sky" />
</div>

@php
    $panelBadge = [
        'HR' => 'bg-rose-50 text-rose-700 border-rose-200',
        'Ads' => 'bg-sky-50 text-sky-700 border-sky-200',
        'Admission' => 'bg-amber-50 text-amber-800 border-amber-200',
        'Faculty' => 'bg-primary-50 text-emerald-700 border-emerald-200',
        'Student' => 'bg-violet-50 text-violet-700 border-violet-200',
        'Admin' => 'bg-primary-50 text-primary-700 border-primary-200',
    ];
@endphp

<x-admin.card :padding="false">
    <x-slot:header>
        <h3 class="font-semibold text-slate-900">Active sessions</h3>
        <form action="{{ route('admin.access-control.sessions') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search name, email, ID…"
                class="flex-1 sm:w-56 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 outline-none">
            <button type="submit" class="btn-primary text-sm py-2">Search</button>
            @if($search ?? false)
                <a href="{{ route('admin.access-control.sessions') }}" class="btn-secondary text-sm py-2">Clear</a>
            @endif
        </form>
    </x-slot:header>

    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Panel</th>
                    <th>IP</th>
                    <th>Device</th>
                    <th>Last activity</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td>
                            <span class="font-semibold text-slate-800 block">{{ $session->user_name }}</span>
                            <span class="text-xs text-slate-500">{{ $session->user_email }}</span>
                        </td>
                        <td>
                            @php $cls = $panelBadge[$session->panel] ?? 'bg-slate-100 text-slate-600 border-slate-200'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold border {{ $cls }}">{{ $session->panel }}</span>
                        </td>
                        <td class="font-mono text-sm text-slate-600">{{ $session->ip_address }}</td>
                        <td class="text-xs text-slate-500 max-w-[200px] truncate" title="{{ $session->user_agent }}">{{ $session->user_agent }}</td>
                        <td class="text-slate-500 text-sm whitespace-nowrap">{{ $session->last_activity->diffForHumans() }}</td>
                        <td class="text-right">
                            <form action="{{ route('admin.access-control.kill-session') }}" method="POST">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ $session->id }}">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
                                    Kill session
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-admin.empty-state
                                :title="($search ?? false) ? 'No matching sessions' : 'No active sessions'"
                                :description="($search ?? false) ? 'No sessions match your search.' : 'No users are currently logged in.'" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.card>
@endsection
