@extends('hr.layouts.app')
@section('title', 'KPI Framework')
@section('page_title', 'Performance')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Key Performance Indicators</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Manage performance evaluation criteria</p>
    </div>
    <a href="{{ route('hr.kpis.create') }}" class="px-4 py-2.5 btn-primary font-semibold rounded-xl text-sm shadow-sm hover:bg-orange-600 focus:ring-4 focus:ring-orange-100 transition-colors inline-flex items-center gap-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"/></svg>
        Add KPI
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
    <div class="panel-list panel-list--kpis">
        <div class="panel-list__head">
            <span>KPI</span>
            <span>Description</span>
            <span>Department</span>
            <span>Designation</span>
            <span class="text-right">Actions</span>
        </div>

        @forelse($kpis as $kpi)
            <article class="panel-list__row">
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">KPI</span>
                    <p class="panel-list-row-title" title="{{ $kpi->title }}">{{ $kpi->title }}</p>
                </div>
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">Description</span>
                    <p class="panel-list-row-muted" title="{{ $kpi->description }}">{{ $kpi->description ?: '—' }}</p>
                </div>
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">Department</span>
                    @if($kpi->department_id)
                        <p class="text-sm font-semibold text-slate-700" title="{{ $kpi->department?->name }}">{{ $kpi->department?->name ?? '—' }}</p>
                    @else
                        <p class="text-xs font-bold text-orange-600">All departments</p>
                    @endif
                </div>
                <div class="panel-list__cell--clip min-w-0">
                    <span class="panel-list__cell-label">Designation</span>
                    @if($kpi->designation_id)
                        <p class="text-sm font-semibold text-slate-700" title="{{ $kpi->designation?->name }}">{{ $kpi->designation?->name ?? '—' }}</p>
                    @elseif(!$kpi->department_id)
                        <p class="text-xs font-bold text-orange-600">All roles</p>
                    @else
                        <p class="text-sm text-slate-500">All roles</p>
                    @endif
                </div>
                <div class="panel-list__cell--end">
                    <span class="panel-list__cell-label">Actions</span>
                    <x-hr.panel-list-actions :edit-route="route('hr.kpis.edit', $kpi)" />
                </div>
            </article>
        @empty
            <div class="px-6 py-12 text-center text-slate-500 font-medium text-sm">
                No KPIs defined.<br>
                <a href="{{ route('hr.kpis.create') }}" class="text-orange-600 hover:underline mt-2 inline-block">Create the first KPI</a>
            </div>
        @endforelse
    </div>
    <x-panel.pagination :paginator="$kpis" />
</div>
@endsection
