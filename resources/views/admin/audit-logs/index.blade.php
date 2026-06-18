@extends('admin.layouts.app')
@section('title', 'Audit Logs')
@section('page_title', 'Audit Logs')

@section('content')
<x-admin.page-header title="Ecosystem activity monitor" subtitle="Critical actions across HR, Ads, Admission, and Admin panels." />

<x-admin.card :padding="false">
    <x-slot:header>
        <h3 class="font-semibold text-slate-900">Cross-panel security logs</h3>
        <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action or description…"
                class="flex-1 sm:w-64 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-600/15 focus:border-primary-600 outline-none">
            <button type="submit" class="btn-primary text-sm py-2">Search</button>
        </form>
    </x-slot:header>

    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User / panel</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="whitespace-nowrap">
                            <span class="font-medium text-slate-800">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                            <span class="block text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                        </td>
                        <td>
                            @if($log->user)
                                <span class="font-semibold text-slate-800 block">{{ $log->user->name ?? ($log->user->first_name . ' ' . $log->user->last_name) }}</span>
                                <span class="text-xs text-primary-700 font-medium">{{ str_replace('App\\Models\\', '', $log->user_type) }}@if($log->user_id) #{{ $log->user_id }}@endif</span>
                            @else
                                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="inline-block px-2.5 py-1 bg-primary-50 text-primary-700 border border-primary-100 text-xs font-semibold uppercase rounded-lg">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="min-w-[240px]">
                            <p class="text-slate-600">{{ $log->description ?: 'No additional details.' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">IP: {{ $log->ip_address ?? 'N/A' }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <x-admin.empty-state title="No activity logs" description="No matching events in the monitoring system." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-panel.pagination :paginator="$logs" />
</x-admin.card>
@endsection
