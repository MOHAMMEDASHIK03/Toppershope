@props(['job'])

@php
    $statusKey = $job->status ?? 'closed';
    $statusMap = [
        'open' => ['label' => 'Active', 'tone' => 'open'],
        'draft' => ['label' => 'Draft', 'tone' => 'draft'],
        'closed' => ['label' => 'Closed', 'tone' => 'closed'],
    ];
    $status = $statusMap[$statusKey] ?? $statusMap['closed'];
    $employmentLabel = ucwords(str_replace(['_', '-'], ' ', (string) ($job->employment_type ?? '')));
    $applicantsUrl = route('hr.job-applications.index', ['job' => $job->id]);
@endphp

<article class="job-card job-card--{{ $status['tone'] }} group">
    <div class="job-card__accent" aria-hidden="true"></div>

    <div class="job-card__top">
        <div class="job-card__icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V96H216v40Z"/></svg>
        </div>
        <div class="job-card__badges">
            <span class="job-card__status job-card__status--{{ $status['tone'] }}">
                <span class="job-card__status-dot"></span>
                {{ $status['label'] }}
            </span>
            @if($employmentLabel)
                <span class="job-card__type">{{ $employmentLabel }}</span>
            @endif
        </div>
        <a href="{{ route('hr.job-postings.edit', $job) }}" class="job-card__edit" title="Edit posting">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M227.31,73.37,182.63,28.68a16,16,0,0,0-22.63,0L36.69,152A15.86,15.86,0,0,0,32,163.31V208a16,16,0,0,0,16,16H92.69A15.86,15.86,0,0,0,104,219.31L227.31,96a16,16,0,0,0,0-22.63ZM92.69,208H48V163.31l88-88L180.69,120ZM192,108.68,147.31,64l24-24L216,84.68Z"/></svg>
        </a>
    </div>

    <div class="job-card__body">
        <h3 class="job-card__title">
            <a href="{{ route('hr.job-postings.edit', $job) }}" class="hover:text-orange-600 transition-colors">{{ $job->title }}</a>
        </h3>

        <ul class="job-card__meta">
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32Z"/></svg>
                {{ $job->department?->name ?? 'No department' }}
            </li>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M128,16a88.1,88.1,0,0,0-88,88c0,75.29,80.67,135.16,84.09,137.57a8,8,0,0,0,7.82,0C136.33,239.16,216,179.29,216,104A88.1,88.1,0,0,0,128,16Zm0,120a32,32,0,1,1,32-32A32,32,0,0,1,128,136Z"/></svg>
                {{ $job->location ?: 'Location TBD' }}
            </li>
        </ul>

        <p class="job-card__excerpt">{{ Str::limit(strip_tags($job->description), 140) }}</p>
    </div>

    <div class="job-card__footer">
        <div class="job-card__stat">
            <span class="job-card__stat-label">Applicants</span>
            <div class="job-card__stat-row">
                <span class="job-card__stat-value">{{ $job->applications_count }}</span>
                @if($job->applications_count > 0)
                    <a href="{{ $applicantsUrl }}" class="job-card__stat-link">View pipeline</a>
                @else
                    <span class="job-card__stat-muted">No applications yet</span>
                @endif
            </div>
        </div>
        <div class="job-card__stat job-card__stat--right">
            <span class="job-card__stat-label">Posted</span>
            <span class="job-card__stat-value job-card__stat-value--sm">{{ $job->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <div class="job-card__actions">
        <a href="{{ route('hr.job-postings.edit', $job) }}" class="job-card__btn job-card__btn--ghost">Manage posting</a>
        @if($job->applications_count > 0)
            <a href="{{ $applicantsUrl }}" class="job-card__btn job-card__btn--primary">Review applicants</a>
        @endif
    </div>
</article>
