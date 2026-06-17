@extends($layoutView ?? 'admin.layouts.app')
@section('title', 'Exam Categories')
@section('page_header', 'Exam Categories')
@section('page_title', 'Exam Categories')

@section('content')
@php
    $isFaculty = str_contains($layoutView ?? '', 'faculty');
    $btnPrimary = $isFaculty
        ? 'bg-primary hover:bg-primary/90 focus:ring-primary'
        : 'btn-primary focus:ring-orange-500';
@endphp

<div>
    @if(!$isFaculty)
    <x-admin.page-header title="Exam category library" subtitle="Manage exam categories and subcategories used across the platform.">
        <x-slot:actions>
            <a href="{{ route($routePrefix . '.create') }}" class="btn-primary">
                <i class="ph ph-plus"></i> Add category
            </a>
        </x-slot:actions>
    </x-admin.page-header>
    @else
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800">Exam Category Library</h2>
            <p class="text-sm text-slate-500 mt-1">Manage exam categories and subcategories used across the platform.</p>
        </div>
        <a href="{{ route($routePrefix . '.create') }}" class="inline-flex items-center justify-center gap-2 {{ $btnPrimary }} text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all focus:ring-2 focus:ring-offset-2 h-fit">
            <i class="ph-bold ph-plus text-lg"></i>
            Add Category
        </a>
    </div>
    @endif

    @if($categories->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="ph-fill ph-squares-four text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">No categories yet</h3>
            <p class="text-slate-500 max-w-md mx-auto mb-6">Create your first exam category to organize courses, batches, and public landing pages.</p>
            <a href="{{ route($routePrefix . '.create') }}" class="inline-flex items-center gap-2 {{ $btnPrimary }} text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all">
                <i class="ph-bold ph-plus"></i>
                Create First Category
            </a>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full table-fixed text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-4 sm:px-6 w-[40%]">Category</th>
                            <th class="py-4 px-3 text-center w-[12%]">Subcategories</th>
                            <th class="py-4 px-3 text-center w-[10%]">Courses</th>
                            <th class="py-4 px-3 text-center w-[10%]">Batches</th>
                            <th class="py-4 px-3 text-center w-[12%]">Status</th>
                            <th class="py-4 px-4 sm:px-6 text-right w-[16%]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $category)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0">
                                        <i class="ph-fill ph-tag text-xl text-primary"></i>
                                    </div>
                                    <p class="font-bold text-slate-800 truncate">{{ $category->name }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="text-sm font-bold text-slate-800">{{ $category->subcategories_count }}</span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="text-sm font-bold text-slate-800">{{ $category->courses_count }}</span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="text-sm font-bold text-slate-800">{{ $category->batches_count }}</span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($category->is_active)
                                    <span class="badge-active">Active</span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">Hidden</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" title="View public page">
                                        <i class="ph-bold ph-arrow-square-out text-lg"></i>
                                    </a>
                                    <a href="{{ route($routePrefix . '.edit', $category) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-colors" title="Edit category & subcategories">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>
                                    <form action="{{ route($routePrefix . '.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category? Subcategories must be removed first.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete category">
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
    @endif
</div>
@endsection
