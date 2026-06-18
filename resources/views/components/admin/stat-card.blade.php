@props([
    'label',
    'value',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
    'accent' => 'primary',
])

@php
    $accentMap = [
        'primary' => 'text-primary-600 dark:text-primary-400',
        'slate' => 'text-gray-900 dark:text-white',
        'p50' => 'text-primary-500',
        'p60' => 'text-primary-600',
        'p70' => 'text-primary-700',
        'p80' => 'text-primary-800',
        /* legacy aliases */
        'indigo' => 'text-primary-600',
        'emerald' => 'text-primary-500',
        'sky' => 'text-primary-400',
        'violet' => 'text-primary-700',
        'rose' => 'text-primary-800',
        'amber' => 'text-primary-600',
    ];
    $valueClass = match ($accent) {
        'primary', 'indigo', 'amber' => 'text-primary-600 dark:text-primary-400',
        'slate' => 'text-gray-900 dark:text-white',
        'emerald' => 'text-emerald-600 dark:text-emerald-400',
        'sky' => 'text-sky-600 dark:text-sky-400',
        'violet' => 'text-primary-700 dark:text-primary-300',
        'rose' => 'text-rose-600 dark:text-rose-400',
        default => $accentMap[$accent] ?? 'text-primary-600 dark:text-primary-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-[#18181c] border border-gray-200 dark:border-[#2a2a32] rounded-xl p-5 shadow-sm dark:shadow-none']) }}>
    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $label }}</p>
    <p class="text-3xl font-bold {{ $valueClass }} mt-2 tabular-nums">{{ $value }}</p>
    @if($trend !== null)
        <p class="text-xs font-semibold mt-2 flex items-center gap-1 {{ $trendUp ? 'text-green-600 dark:text-emerald-400' : 'text-red-600 dark:text-rose-400' }}">
            @if($trendUp)
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,117.66a8,8,0,0,1-11.32,0L136,59.31V216a8,8,0,0,1-16,0V59.31L61.66,117.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0l72,72A8,8,0,0,1,205.66,117.66Z"/></svg>
            @else
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,149.66l-72,72a8,8,0,0,1-11.32,0l-72-72a8,8,0,0,1,11.32-11.32L120,196.69V40a8,8,0,0,1,16,0V196.69l58.34-58.35a8,8,0,0,1,11.32,11.32Z"/></svg>
            @endif
            {{ $trend }}
        </p>
    @elseif($hint)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $hint }}</p>
    @endif
</div>
