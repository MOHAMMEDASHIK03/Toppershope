@extends('admin.layouts.app')
@section('title', 'Branch Management')
@section('page_title', 'Ecosystem Branches')

@section('content')
<x-admin.page-header title="Branch management" subtitle="Create and oversee physical and virtual branches within the ecosystem.">
    <x-slot:actions>
        <button type="button" onclick="document.getElementById('add-branch-modal').classList.remove('hidden')" class="btn-primary">
            <i class="ph ph-plus"></i> Add branch
        </button>
    </x-slot:actions>
</x-admin.page-header>

<!-- Branches Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($branches as $branch)
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col h-full hover:border-indigo-200 transition-colors">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center border border-indigo-100 shrink-0">
                        <i class="ph ph-buildings text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 text-lg leading-tight">{{ $branch->name }}</h3>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Code: {{ $branch->code }}</p>
                    </div>
                </div>
                @if($branch->is_active)
                    <span class="badge-active">Active</span>
                @else
                    <span class="badge-inactive">Inactive</span>
                @endif
            </div>

            <p class="text-sm text-slate-600 mb-6 flex-1 flex items-start gap-2">
                <i class="ph ph-map-pin mt-0.5 shrink-0 text-slate-400"></i>
                {{ $branch->location ?: 'No physical location assigned.' }}
            </p>

            <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                <div class="text-xs font-medium text-slate-500">
                    <span class="font-semibold text-slate-800">{{ $branch->employees_count }}</span> employees linked
                </div>
                <div class="flex gap-1">
                    <button type="button" onclick="openEditModal({{ $branch->toJson() }})" class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                        <i class="ph ph-pencil-simple"></i>
                    </button>
                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Delete this branch?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                            <i class="ph ph-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white border-2 border-dashed border-slate-200 rounded-xl">
            <x-admin.empty-state title="No branches yet" description="Create the first physical or virtual branch for Topper's Hope." />
        </div>
    @endforelse
</div>

<x-panel.pagination :paginator="$branches" class="rounded-xl border border-slate-200 shadow-sm" />

@php $branchInputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-indigo-500 transition-all'; @endphp

<!-- Add Branch Modal -->
<div id="add-branch-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('add-branch-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden z-10">
        <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <h3 class="text-lg font-black text-slate-800">Create branch</h3>
            <button type="button" onclick="document.getElementById('add-branch-modal').classList.add('hidden')" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Branch name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="{{ $branchInputClass }}" placeholder="e.g. Main Campus (Kochi)">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Unique code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required class="{{ $branchInputClass }} font-mono" placeholder="KOCHI-HQ-01">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Physical location</label>
                    <textarea name="location" rows="3" class="{{ $branchInputClass }} resize-none" placeholder="Full address, or Virtual"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('add-branch-modal').classList.add('hidden')" class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 btn-primary text-white font-bold text-sm rounded-xl shadow-sm transition-colors">Create branch</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Branch Modal -->
<div id="edit-branch-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('edit-branch-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden z-10">
        <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <h3 class="text-lg font-black text-slate-800">Edit branch</h3>
            <button type="button" onclick="document.getElementById('edit-branch-modal').classList.add('hidden')" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/></svg>
            </button>
        </div>
        <form id="edit-branch-form" method="POST">
            @csrf @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Branch name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="{{ $branchInputClass }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Unique code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="edit-code" required class="{{ $branchInputClass }} font-mono">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Physical location</label>
                    <textarea name="location" id="edit-location" rows="3" class="{{ $branchInputClass }} resize-none"></textarea>
                </div>
                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-xl border border-slate-200 bg-slate-50">
                    <input type="checkbox" name="is_active" id="edit-active" value="1" class="mt-0.5 w-5 h-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                    <div>
                        <span class="block text-sm font-semibold text-slate-800">Branch is active</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Inactive branches prevent new assignments.</span>
                    </div>
                </label>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('edit-branch-modal').classList.add('hidden')" class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 btn-primary text-white font-bold text-sm rounded-xl shadow-sm transition-colors">Save changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(branch) {
        document.getElementById('edit-name').value = branch.name;
        document.getElementById('edit-code').value = branch.code;
        document.getElementById('edit-location').value = branch.location || '';
        document.getElementById('edit-active').checked = branch.is_active;
        document.getElementById('edit-branch-form').action = `/admin/branches/${branch.id}`;
        document.getElementById('edit-branch-modal').classList.remove('hidden');
    }
</script>
@endsection
