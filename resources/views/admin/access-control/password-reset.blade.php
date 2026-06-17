@extends('admin.layouts.app')
@section('title', 'Force Password Reset')
@section('page_title', 'Password Reset')

@section('content')
<x-admin.page-header title="Cross-panel password reset" subtitle="Force-reset any staff member's password across HR, Ads, Admission, or Faculty panels." />

@php
    $panelBadge = [
        'HR' => 'bg-rose-50 text-rose-700 border-rose-200',
        'Ads' => 'bg-sky-50 text-sky-700 border-sky-200',
        'Admission' => 'bg-amber-50 text-amber-800 border-amber-200',
        'Faculty' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
@endphp

<x-admin.card :padding="false">
    <x-slot:header>
        <h3 class="font-semibold text-slate-900">All staff accounts</h3>
        <div class="flex flex-wrap gap-1.5 text-[10px] font-bold uppercase">
            <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">HR</span>
            <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200">Ads</span>
            <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">Admission</span>
            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Faculty</span>
        </div>
    </x-slot:header>

    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Panel</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            @forelse($allUsers as $user)
                <tbody x-data="{ showReset: false }">
                    <tr>
                        <td>
                            <span class="font-semibold text-slate-800 block">{{ $user->name }}</span>
                            <span class="text-xs text-slate-500">{{ $user->email }}</span>
                        </td>
                        <td>
                            @php $cls = $panelBadge[$user->panel] ?? 'bg-slate-100 text-slate-600 border-slate-200'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold border {{ $cls }}">{{ $user->panel }}</span>
                        </td>
                        <td class="text-slate-600 text-sm capitalize">{{ str_replace('_', ' ', $user->role) }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="text-emerald-600 text-xs font-semibold">● Active</span>
                            @else
                                <span class="text-rose-600 text-xs font-semibold">● Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <button type="button" @click="showReset = !showReset" class="btn-secondary text-xs py-1.5">
                                Reset password
                            </button>
                        </td>
                    </tr>
                    <tr x-show="showReset" x-cloak class="bg-slate-50">
                        <td colspan="5" class="px-4 py-4">
                            <form action="{{ route('admin.access-control.force-reset') }}" method="POST" class="flex flex-col md:flex-row gap-3 items-end">
                                @csrf
                                <input type="hidden" name="model" value="{{ $user->model }}">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="flex-1 w-full">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">New password for {{ $user->name }}</label>
                                    <input type="text" name="new_password" required minlength="6" placeholder="Min 6 characters"
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 outline-none">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-lg whitespace-nowrap">
                                    Force reset
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            @empty
                <tbody>
                    <tr>
                        <td colspan="5">
                            <x-admin.empty-state title="No staff users" description="No staff accounts exist across any panel." />
                        </td>
                    </tr>
                </tbody>
            @endforelse
        </table>
    </div>
</x-admin.card>
@endsection
