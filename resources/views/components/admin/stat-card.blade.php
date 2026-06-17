@props([
    'label',
    'value',
    'hint' => null,
    'trend' => null,
    'trendUp' => true,
    'accent' => 'slate',
])

@php
    $accentMap = [
        'slate' => 'text-slate-900',
        'indigo' => 'text-indigo-600',
        'emerald' => 'text-emerald-600',
        'sky' => 'text-sky-600',
        'violet' => 'text-violet-600',
        'rose' => 'text-rose-600',
        'amber' => 'text-amber-600',
    ];
    $valueClass = $accentMap[$accent] ?? $accentMap['slate'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-xl p-5 shadow-sm']) }}>
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $label }}</p>
    <p class="text-3xl font-bold {{ $valueClass }} mt-2 tabular-nums">{{ $value }}</p>
    @if($trend !== null)
        <p class="text-xs font-semibold mt-2 flex items-center gap-1 {{ $trendUp ? 'text-emerald-600' : 'text-rose-600' }}">
            @if($trendUp)
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,117.66a8,8,0,0,1-11.32,0L136,59.31V216a8,8,0,0,1-16,0V59.31L61.66,117.66a8,8,0,0,1-11.32-11.32l72-72a8,8,0,0,1,11.32,0l72,72A8,8,0,0,1,205.66,117.66Z"/></svg>
            @else
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,149.66l-72,72a8,8,0,0,1-11.32,0l-72-72a8,8,0,0,1,11.32-11.32L120,196.69V40a8,8,0,0,1,16,0V196.69l58.34-58.35a8,8,0,0,1,11.32,11.32Z"/></svg>
            @endif
            {{ $trend }}
        </p>
    @elseif($hint)
        <p class="text-xs text-slate-500 mt-2">{{ $hint }}</p>
    @endif
</div>
