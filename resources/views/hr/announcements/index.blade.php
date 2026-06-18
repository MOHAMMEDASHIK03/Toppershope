@extends('hr.layouts.app')
@section('title', 'Announcements')
@section('page_title', 'Company')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Company Announcements</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Broadcast important news and policies</p>
    </div>
    <a href="{{ route('hr.announcements.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-primary-700 focus:ring-4 focus:ring-primary-100 transition-colors inline-flex items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
        New Broadcast
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--announcements">
        <div class="panel-list__head">
            <span>Title</span>
            <span>Audience</span>
            <span>Preview</span>
            <span>Posted</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($announcements as $post)
            <article class="panel-list__row items-center {{ $post->is_active ? '' : 'opacity-90' }}">
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">Title</span>
                    <p class="panel-list-row-title" title="{{ $post->title }}">{{ $post->title }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Audience</span>
                    @if($post->department_id)
                        <span class="text-xs font-bold text-slate-600 border border-slate-200 px-2 py-0.5 rounded-md inline-block max-w-full truncate" title="{{ $post->department->name }}">
                            {{ $post->department->name }}
                        </span>
                    @else
                        <span class="text-xs font-bold text-primary-700 bg-primary-50 border border-primary-100 px-2 py-0.5 rounded-md inline-block whitespace-nowrap">Company wide</span>
                    @endif
                </div>
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">Preview</span>
                    <p class="panel-list-row-muted" title="{{ strip_tags($post->content) }}">{{ Str::limit(strip_tags($post->content), 90) }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Posted</span>
                    <p class="text-xs font-bold text-slate-700 whitespace-nowrap">{{ $post->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Status</span>
                    @if($post->is_active)
                        <span class="panel-list-status-pill bg-primary-50 border border-emerald-100 text-emerald-700">Live</span>
                    @else
                        <span class="panel-list-status-pill bg-slate-100 border border-slate-200 text-slate-600">Archived</span>
                    @endif
                </div>
                <div class="panel-list__cell--end">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.announcements.edit', $post)" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No public announcements yet.<br>
                <a href="{{ route('hr.announcements.create') }}" class="text-primary-700 hover:underline mt-2 inline-block">Broadcast something important</a>
            </div>
        @endforelse
    </div>

    <x-panel.pagination :paginator="$announcements" />
</div>
@endsection
