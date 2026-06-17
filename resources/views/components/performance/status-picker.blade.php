@props(['name' => 'status', 'value' => 'published'])

@php
    $current = old($name, $value);
    $options = [
        'draft' => [
            'label' => 'Draft',
            'desc' => 'Visible only to HR until you publish.',
            'dot' => 'bg-amber-500',
            'variant' => 'draft',
        ],
        'published' => [
            'label' => 'Published',
            'desc' => 'Employee can view this review.',
            'dot' => 'bg-emerald-500',
            'variant' => 'published',
        ],
        'acknowledged' => [
            'label' => 'Acknowledged',
            'desc' => 'Employee has read and acknowledged.',
            'dot' => 'bg-orange-500',
            'variant' => 'acknowledged',
        ],
    ];
@endphp

<div class="perf-status-grid" role="radiogroup" aria-label="Record visibility">
    @foreach($options as $key => $opt)
        <label class="perf-status-card">
            <input
                type="radio"
                name="{{ $name }}"
                value="{{ $key }}"
                class="perf-status-card__input"
                {{ $current === $key ? 'checked' : '' }}
                required
            >
            <div class="perf-status-card__body perf-status-card__body--{{ $opt['variant'] }}">
                <span class="perf-status-card__check" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M173.66,98.34a8,8,0,0,1,0,11.32l-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35A8,8,0,0,1,173.66,98.34Z"/></svg>
                    Selected
                </span>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-slate-800">
                    <span class="w-2 h-2 rounded-full {{ $opt['dot'] }}"></span>
                    {{ $opt['label'] }}
                </span>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $opt['desc'] }}</p>
            </div>
        </label>
    @endforeach
</div>
