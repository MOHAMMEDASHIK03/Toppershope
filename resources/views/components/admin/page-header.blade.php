@props([
    'title',
    'subtitle' => null,
])

<div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>
