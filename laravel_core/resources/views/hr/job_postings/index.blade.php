@extends('hr.layouts.app')
@section('title', 'Careers Hub')
@section('page_title', 'Recruitment')

@push('styles')
<style>
    .job-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgb(15 23 42 / 0.04);
        transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .job-card:hover {
        border-color: #fdba74;
        box-shadow: 0 12px 28px rgb(15 23 42 / 0.08);
        transform: translateY(-2px);
    }
    .job-card__accent {
        height: 4px;
        width: 100%;
        background: #94a3b8;
    }
    .job-card--open .job-card__accent { background: linear-gradient(90deg, #10b981, #34d399); }
    .job-card--draft .job-card__accent { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .job-card--closed .job-card__accent { background: linear-gradient(90deg, #94a3b8, #cbd5e1); }

    .job-card__top {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: start;
        gap: 0.75rem;
        padding: 1.25rem 1.25rem 0;
    }
    .job-card__icon {
        width: 2.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ffedd5;
    }
    .job-card--open .job-card__icon { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .job-card--closed .job-card__icon { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }

    .job-card__badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        padding-top: 0.25rem;
    }
    .job-card__status {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .job-card__status-dot {
        width: 0.375rem;
        height: 0.375rem;
        border-radius: 9999px;
        background: currentColor;
    }
    .job-card__status--open { color: #047857; background: #ecfdf5; }
    .job-card__status--draft { color: #b45309; background: #fffbeb; }
    .job-card__status--closed { color: #64748b; background: #f1f5f9; }

    .job-card__type {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #64748b;
        padding: 0.25rem 0.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
    }
    .job-card__edit {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        color: #94a3b8;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        transition: all 0.15s;
    }
    .job-card__edit:hover {
        color: #ea580c;
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .job-card__body {
        padding: 1rem 1.25rem 0;
        flex: 1;
    }
    .job-card__title {
        font-size: 1.0625rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 0.625rem;
    }
    .job-card__meta {
        list-style: none;
        margin: 0 0 0.875rem;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }
    .job-card__meta li {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #64748b;
    }
    .job-card__meta svg { flex-shrink: 0; opacity: 0.7; }
    .job-card__excerpt {
        font-size: 0.8125rem;
        line-height: 1.55;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 0.625rem;
        padding: 0.75rem 0.875rem;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .job-card__footer {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin: 1rem 1.25rem 0;
        padding: 0.875rem 1rem;
        background: #fafbfc;
        border: 1px solid #f1f5f9;
        border-radius: 0.75rem;
    }
    .job-card__stat-label {
        display: block;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }
    .job-card__stat-row {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 0.5rem;
    }
    .job-card__stat-value {
        font-size: 1.375rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }
    .job-card__stat-value--sm {
        font-size: 0.875rem;
        font-weight: 700;
    }
    .job-card__stat-link {
        font-size: 0.75rem;
        font-weight: 700;
        color: #ea580c;
        text-decoration: none;
    }
    .job-card__stat-link:hover { text-decoration: underline; }
    .job-card__stat-muted {
        font-size: 0.6875rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .job-card__stat--right { text-align: right; }

    .job-card__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 1rem 1.25rem 1.25rem;
        margin-top: auto;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity 0.2s, transform 0.2s;
    }
    .job-card:hover .job-card__actions,
    .job-card:focus-within .job-card__actions {
        opacity: 1;
        transform: translateY(0);
    }
    .job-card__btn {
        flex: 1;
        min-width: 0;
        text-align: center;
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 0.625rem;
        text-decoration: none;
        transition: all 0.15s;
    }
    .job-card__btn--ghost {
        color: #475569;
        background: #fff;
        border: 1px solid #e2e8f0;
    }
    .job-card__btn--ghost:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .job-card__btn--primary {
        color: #fff;
        background: #f97316;
        border: 1px solid #ea580c;
        box-shadow: 0 2px 8px rgb(249 115 22 / 0.25);
    }
    .job-card__btn--primary:hover { background: #ea580c; color: #fff; }

    @media (max-width: 640px) {
        .job-card__actions { opacity: 1; transform: none; }
    }

    .job-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem 1.5rem;
        background: #fff;
        border: 1px dashed #e2e8f0;
        border-radius: 1rem;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Job Postings</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage active vacancies and track applicant pools</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('hr.job-applications.index') }}" class="btn-secondary px-4 py-2.5 rounded-xl text-sm shadow-sm">
            View applications
        </a>
        <a href="{{ route('hr.job-postings.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
            Create posting
        </a>
    </div>
</div>

@if($postings->isNotEmpty())
    <div class="mb-5 flex flex-wrap gap-3">
        @php
            $openCount = $postings->where('status', 'open')->count();
            $totalApplicants = $postings->sum('applications_count');
        @endphp
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-bold text-emerald-700">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            {{ $openCount }} active {{ Str::plural('posting', $openCount) }}
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-xs font-bold text-orange-700">
            {{ $totalApplicants }} total {{ Str::plural('applicant', $totalApplicants) }}
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($postings as $job)
        <x-hr.job-posting-card :job="$job" />
    @empty
        <div class="job-empty">
            <div class="w-16 h-16 mx-auto bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mb-4 border border-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V96H216v40Z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">No job postings yet</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">Create your first vacancy to start receiving applications on the careers portal.</p>
            <a href="{{ route('hr.job-postings.create') }}" class="btn-primary px-6 py-2.5 rounded-xl font-bold text-sm">
                Create first posting
            </a>
        </div>
    @endforelse
    <x-panel.pagination :paginator="$postings" />
</div>
@endsection
