@extends('admin.layouts.app')
@section('title', 'HR Staff Provisioning')
@section('page_title', 'HR Users')

@section('content')
<x-admin.page-header title="HR Staff Provisioning" subtitle="Manage access credentials for Human Resource administrators.">
    <x-slot:actions>
        <a href="{{ route('admin.hr-users.create') }}" class="btn-primary">
            <i class="ph ph-plus"></i> Provision HR Admin
        </a>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :padding="false">
    <div class="panel-table-wrap">
        <table class="admin-table w-full text-left">
            <thead>
                <tr>
                    <th>Admin name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hrUsers as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-slate-600">{{ $user->email }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge-active">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase text-rose-700 bg-rose-50 border border-rose-200">Suspended</span>
                            @endif
                        </td>
                        <td class="text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.hr-users.edit', $user) }}" class="p-2 text-slate-400 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="Edit">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('admin.hr-users.destroy', $user) }}" method="POST" onsubmit="return confirm('Revoke this access? This cannot be undone.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-rose-50 rounded-lg transition-colors" title="Revoke">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-admin.empty-state title="No HR administrators" description="Provision an account to grant HR panel access." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-panel.pagination :paginator="$hrUsers" />
</x-admin.card>
@endsection
