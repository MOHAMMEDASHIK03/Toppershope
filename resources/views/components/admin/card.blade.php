@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            @if($title)
                <h3 class="font-semibold text-slate-900">{{ $title }}</h3>
            @endif
            @isset($header)
                {{ $header }}
            @endisset
        </div>
    @endif
    <div @class([$padding ? 'p-5' : ''])>
        {{ $slot }}
    </div>
</div>
