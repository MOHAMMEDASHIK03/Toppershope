@props([
    'title' => 'No records found',
    'description' => null,
])

<div class="py-12 px-6 text-center">
    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400">
        {{ $icon ?? '' }}
    </div>
    <h4 class="text-base font-semibold text-slate-800">{{ $title }}</h4>
    @if($description)
        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @if(isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
