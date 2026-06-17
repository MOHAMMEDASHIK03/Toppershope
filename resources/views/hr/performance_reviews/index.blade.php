@extends('hr.layouts.app')
@section('title', 'Performance Reviews')
@section('page_title', 'Performance')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Performance Reviews</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Track and evaluate employee parameters over time</p>
    </div>
    <a href="{{ route('hr.performance-reviews.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
        Submit Review
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--performance-reviews">
        <div class="panel-list__head">
            <span>Employee</span>
            <span>Period</span>
            <span>KPI</span>
            <span>Rating</span>
            <span>Status</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($reviews as $review)
            <article class="panel-list__row">
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Employee</span>
                    <x-hr.panel-list-employee :employee="$review->employee" />
                </div>
                <div>
                    <span class="panel-list__cell-label">Period</span>
                    <span class="inline-flex bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold whitespace-nowrap">{{ $review->review_period }}</span>
                </div>
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">KPI</span>
                    <p class="text-sm font-bold text-slate-700" title="{{ $review->kpi->title ?? 'N/A' }}">
                        {{ $review->kpi->title ?? 'Deleted KPI' }}
                    </p>
                </div>
                <div>
                    <span class="panel-list__cell-label">Rating</span>
                    <div class="panel-list-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="16" class="{{ $i <= $review->rating ? 'text-amber-400 shrink-0' : 'text-slate-200 shrink-0' }}" viewBox="0 0 256 256" aria-hidden="true"><path d="M239.2,97.29a16,16,0,0,0-13.81-11L166,81.17,142.72,25.81h0a15.95,15.95,0,0,0-29.44,0h0L90.07,81.17,30.61,86.32a16,16,0,0,0-9.11,28.06L66.61,153.8,53.09,212.34a16,16,0,0,0,23.84,17.34l51.11-31,51.11,31a16,16,0,0,0,23.84-17.34l-13.51-58.6,45.1-39.36A16,16,0,0,0,239.2,97.29Z"/></svg>
                        @endfor
                        <span class="font-bold text-slate-600 text-[11px]">({{ $review->rating }}/5)</span>
                    </div>
                </div>
                <div class="min-w-0">
                    <span class="panel-list__cell-label">Status</span>
                    @if($review->status === 'published')
                        <span class="panel-list-status-pill bg-emerald-50 border border-emerald-100 text-emerald-700 capitalize">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span> {{ $review->status }}
                        </span>
                    @elseif($review->status === 'draft')
                        <span class="panel-list-status-pill bg-amber-50 border border-amber-100 text-amber-700 capitalize">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> {{ $review->status }}
                        </span>
                    @elseif($review->status === 'acknowledged')
                        <span class="panel-list-status-pill bg-sky-50 border border-sky-100 text-sky-700 capitalize">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 shrink-0"></span> {{ $review->status }}
                        </span>
                    @else
                        <span class="panel-list-status-pill bg-slate-100 border border-slate-200 text-slate-600 capitalize">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span> {{ $review->status }}
                        </span>
                    @endif
                </div>
                <div class="panel-list__cell--end">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.performance-reviews.edit', $review)" edit-label="Details" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No performance reviews submitted yet.<br>
                <a href="{{ route('hr.performance-reviews.create') }}" class="text-orange-600 hover:underline mt-2 inline-block">Create the first review</a>
            </div>
        @endforelse
    </div>

    <x-panel.pagination :paginator="$reviews" />
</div>
@endsection
