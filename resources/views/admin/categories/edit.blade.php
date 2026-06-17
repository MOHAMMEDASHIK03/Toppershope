@extends($layoutView ?? 'admin.layouts.app')
@section('title', 'Edit Category')
@section('page_header', 'Edit Category')
@section('page_title', 'Edit Category')

@section('content')
@php
    $isFaculty = str_contains($layoutView ?? '', 'faculty');
    $btnPrimary = $isFaculty
        ? 'bg-primary hover:bg-primary/90 focus:ring-primary'
        : 'btn-primary focus:ring-orange-500';
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary';
@endphp

<div class="max-w-5xl space-y-8">
    <a href="{{ route($routePrefix . '.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary transition-colors">
        <i class="ph-bold ph-arrow-left"></i>
        Back to categories
    </a>

    <div>
        <h2 class="text-xl font-bold tracking-tight text-slate-800">{{ $category->name }}</h2>
        <p class="text-sm text-slate-500 mt-1">Update category details and manage subcategories below.</p>
    </div>

    <form action="{{ route($routePrefix . '.update', $category) }}" method="POST" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')
        @include('admin.categories._form', ['category' => $category])
        <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center gap-2 {{ $btnPrimary }} text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all focus:ring-2 focus:ring-offset-2">
                <i class="ph-bold ph-floppy-disk"></i>
                Save category
            </button>
        </div>
    </form>

    {{-- Subcategories --}}
    <div>
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Subcategories</h3>
                <p class="text-sm text-slate-500">Class 11, Class 12, Dropper, etc. — used for batch and filter targeting.</p>
            </div>
        </div>

        @if($category->subcategories->isEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center shadow-sm mb-4">
                <p class="text-slate-500 text-sm">No subcategories yet. Add the first one below.</p>
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-4">
                <div class="panel-table-wrap">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-6">Name</th>
                                <th class="py-3 px-6">Slug</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($category->subcategories as $sub)
                            <tr class="hover:bg-slate-50/50 align-middle">
                                <td class="py-3 px-6">
                                    <form id="sub-form-{{ $sub->id }}" action="{{ route($routePrefix . '.subcategories.update', [$category, $sub]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-violet-50 border border-violet-100 flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-list-bullets text-violet-600"></i>
                                        </div>
                                        <input form="sub-form-{{ $sub->id }}" type="text" name="name" value="{{ $sub->name }}" required class="{{ $inputClass }}" placeholder="Name">
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <input form="sub-form-{{ $sub->id }}" type="text" name="slug" value="{{ $sub->slug }}" class="{{ $inputClass }} font-mono text-xs">
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                        <input form="sub-form-{{ $sub->id }}" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-primary focus:ring-primary" {{ $sub->is_active ? 'checked' : '' }}>
                                        <span class="text-xs font-semibold {{ $sub->is_active ? 'text-emerald-700' : 'text-slate-400' }}">{{ $sub->is_active ? 'Active' : 'Hidden' }}</span>
                                    </label>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button form="sub-form-{{ $sub->id }}" type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-colors" title="Save subcategory">
                                            <i class="ph-bold ph-floppy-disk text-lg"></i>
                                        </button>
                                        <form action="{{ route($routePrefix . '.subcategories.destroy', [$category, $sub]) }}" method="POST" class="inline" onsubmit="return confirm('Delete subcategory “{{ $sub->name }}”?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete subcategory">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-primary"></i>
                Add subcategory
            </h4>
            <form action="{{ route($routePrefix . '.subcategories.store', $category) }}" method="POST" class="flex flex-col sm:flex-row flex-wrap gap-3 items-end">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subcategory name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="{{ $inputClass }}" placeholder="e.g. Class 11">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 {{ $btnPrimary }} text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all">
                    <i class="ph-bold ph-plus"></i>
                    Add subcategory
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
