@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-[#18181c] border border-gray-200 dark:border-[#2a2a32] rounded-xl shadow-sm dark:shadow-none overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-5 py-4 border-b border-gray-100 dark:border-[#2D2D35] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            @if($title)
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
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
