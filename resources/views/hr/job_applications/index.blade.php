@extends('hr.layouts.app')
@section('title', 'Applicants')
@section('page_title', 'Recruitment')

@push('styles')
<style>
    .app-list-head {
        display: none;
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 5.5rem) minmax(0, 6.5rem) minmax(0, 4.5rem) minmax(0, 5.5rem);
        gap: 1rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #94a3b8;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    @media (min-width: 1024px) {
        .app-list-head { display: grid; }
    }
    .app-list-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        transition: background 0.15s;
    }
    .app-list-row:last-child { border-bottom: none; }
    .app-list-row:hover { background: #fafbfc; }
    @media (min-width: 1024px) {
        .app-list-row {
            grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 5.5rem) minmax(0, 6.5rem) minmax(0, 4.5rem) minmax(0, 5.5rem);
            gap: 1rem;
            padding: 1rem 1.25rem;
        }
    }
    .app-list-cell__label {
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }
    @media (min-width: 1024px) {
        .app-list-cell__label { display: none; }
    }
    .app-avatar {
        width: 2.5rem;
        height: 2.5rem;
        flex-shrink: 0;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.875rem;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ffedd5;
    }
    .app-profile-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.9375rem;
        line-height: 1.3;
        word-break: break-word;
    }
    .app-profile-email {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.125rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
    }
    .app-role-title {
        font-weight: 700;
        color: #334155;
        font-size: 0.875rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .app-manage-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
        font-weight: 700;
        color: #ea580c;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 0.625rem;
        text-decoration: none;
        transition: all 0.15s;
    }
    .app-manage-btn:hover {
        background: #ffedd5;
        border-color: #fb923c;
        color: #c2410c;
    }
    @media (min-width: 1024px) {
        .app-manage-btn { width: auto; min-width: 5.5rem; }
    }
</style>
@endpush

@section('content')
@php
    $statusColors = [
        'applied' => ['text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'dot' => 'bg-blue-500'],
        'shortlisted' => ['text' => 'text-purple-700', 'bg' => 'bg-purple-50', 'dot' => 'bg-purple-500'],
        'interviewed' => ['text' => 'text-indigo-700', 'bg' => 'bg-indigo-50', 'dot' => 'bg-indigo-500'],
        'offered' => ['text' => 'text-amber-700', 'bg' => 'bg-amber-50', 'dot' => 'bg-amber-500'],
        'rejected' => ['text' => 'text-rose-700', 'bg' => 'bg-rose-50', 'dot' => 'bg-rose-500'],
        'hired' => ['text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'dot' => 'bg-emerald-500'],
    ];
@endphp

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Job Applications</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Review candidates and schedule interviews</p>
        @if(request('job'))
            <p class="text-xs text-orange-600 font-semibold mt-1">Filtered by job posting · <a href="{{ route('hr.job-applications.index') }}" class="underline">Clear filter</a></p>
        @endif
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('hr.job-applications.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
            Add manual application
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @if($applications->isNotEmpty())
        <div class="app-list-head">
            <span>Applicant</span>
            <span>Applied role</span>
            <span>Date</span>
            <span>Status</span>
            <span class="text-center">Interviews</span>
            <span class="text-right">Actions</span>
        </div>

        <div>
            @foreach($applications as $app)
                @php $c = $statusColors[$app->status] ?? $statusColors['applied']; @endphp
                <article class="app-list-row">
                    <div class="min-w-0">
                        <p class="app-list-cell__label">Applicant</p>
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="app-avatar" aria-hidden="true">{{ strtoupper(substr($app->applicant_name, 0, 1)) }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="app-profile-name">{{ $app->applicant_name }}</p>
                                <p class="app-profile-email" title="{{ $app->email }}">{{ $app->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-0">
                        <p class="app-list-cell__label">Applied role</p>
                        <p class="app-role-title" title="{{ $app->jobPosting?->title ?? 'N/A' }}">
                            {{ $app->jobPosting?->title ?? 'Deleted role' }}
                        </p>
                        <p class="text-xs text-slate-400 capitalize mt-0.5">{{ str_replace('-', ' ', $app->jobPosting?->employment_type ?? '—') }}</p>
                    </div>

                    <div>
                        <p class="app-list-cell__label">Date</p>
                        <p class="text-sm font-semibold text-slate-600">{{ $app->created_at->format('d M Y') }}</p>
                    </div>

                    <div>
                        <p class="app-list-cell__label">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $c['bg'] }} {{ $c['text'] }} font-bold text-xs capitalize">
                            <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                            {{ $app->status }}
                        </span>
                    </div>

                    <div class="lg:text-center">
                        <p class="app-list-cell__label">Interviews</p>
                        @if($app->interviews_count > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 bg-slate-100 text-slate-700 font-bold rounded-md text-xs">
                                {{ $app->interviews_count }}
                            </span>
                        @else
                            <span class="text-sm text-slate-300 font-medium">—</span>
                        @endif
                    </div>

                    <div class="lg:text-right">
                        <p class="app-list-cell__label">Actions</p>
                        <a href="{{ route('hr.job-applications.edit', $app) }}" class="app-manage-btn">
                            Manage
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="px-6 py-14 text-center">
            <div class="w-16 h-16 mx-auto bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mb-4 border border-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M128,136a8,8,0,0,0-8,8v16H104a8,8,0,0,0,0,16h16v16a8,8,0,0,0,16,0V176h16a8,8,0,0,0,0-16H136V144A8,8,0,0,0,128,136ZM216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,88Z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">No applications found</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto">Waiting for candidates to apply, or add one manually from a job posting.</p>
        </div>
    @endif

    <x-panel.pagination :paginator="$applications" />
</div>
@endsection
