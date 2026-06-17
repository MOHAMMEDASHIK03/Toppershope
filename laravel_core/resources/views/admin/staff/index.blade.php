@extends('admin.layouts.app')
@section('title', 'Staff Registry')
@section('page_title', 'Staff Registry')

@section('content')
<x-admin.page-header title="Ecosystem staff overview" subtitle="Read-only view of provisioned accounts across departments. Manage users in their respective panels via the sidebar links." />

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <x-admin.stat-card label="Ads managers" :value="$adsUsers->count()" accent="sky" />
    <x-admin.stat-card label="Admission team" :value="$admissionUsers->count()" accent="amber" />
    <x-admin.stat-card label="Faculty members" :value="$facultyUsers->count()" accent="emerald" />
</div>

<x-admin.card :padding="false">
    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>Account name</th>
                    <th>Email</th>
                    <th>Department / role</th>
                    <th class="text-right">Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adsUsers as $user)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="text-slate-600">{{ $user->email }}</td>
                        <td><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-50 text-sky-700 border border-sky-200">Ads team</span></td>
                        <td class="text-right text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
                @foreach($admissionUsers as $user)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="text-slate-600">{{ $user->email }}</td>
                        <td><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-800 border border-amber-200">Admissions ({{ ucfirst($user->role) }})</span></td>
                        <td class="text-right text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
                @foreach($facultyUsers as $user)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="text-slate-600">{{ $user->email }}</td>
                        <td><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">Academic ({{ ucfirst(str_replace('_', ' ', $user->role)) }})</span></td>
                        <td class="text-right text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
                @if($adsUsers->isEmpty() && $admissionUsers->isEmpty() && $facultyUsers->isEmpty())
                    <tr>
                        <td colspan="4">
                            <x-admin.empty-state title="No external staff" description="Staff accounts will appear here once provisioned in their panels." />
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-admin.card>
@endsection
