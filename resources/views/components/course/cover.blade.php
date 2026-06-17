@props([
    'course',
    'height' => 'h-40',
])

@php
    $imagePath = $course->hero_image ?: $course->thumbnail;
    $imageUrl = $imagePath ? asset('storage/' . ltrim($imagePath, '/')) : null;
    $initial = strtoupper(mb_substr(trim($course->name ?: 'C'), 0, 1));

    $palettes = [
        ['from' => 'from-orange-400', 'to' => 'to-rose-500', 'text' => 'text-white'],
        ['from' => 'from-sky-400', 'to' => 'to-blue-600', 'text' => 'text-white'],
        ['from' => 'from-emerald-400', 'to' => 'to-teal-600', 'text' => 'text-white'],
        ['from' => 'from-violet-400', 'to' => 'to-purple-600', 'text' => 'text-white'],
        ['from' => 'from-amber-400', 'to' => 'to-orange-500', 'text' => 'text-white'],
        ['from' => 'from-pink-400', 'to' => 'to-fuchsia-600', 'text' => 'text-white'],
        ['from' => 'from-cyan-400', 'to' => 'to-indigo-500', 'text' => 'text-white'],
        ['from' => 'from-lime-500', 'to' => 'to-green-600', 'text' => 'text-white'],
    ];
    $palette = $palettes[abs((int) ($course->id ?? crc32($course->name ?? ''))) % count($palettes)];
@endphp

<div {{ $attributes->merge(['class' => $height . ' relative overflow-hidden bg-slate-100']) }}>
    @if($imageUrl)
        <img
            src="{{ $imageUrl }}"
            alt="{{ $course->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
            onerror="this.remove(); this.parentElement.querySelector('[data-course-letter]')?.classList.remove('hidden');"
        >
    @endif

    <div
        data-course-letter
        @class([
            'absolute inset-0 flex items-center justify-center bg-gradient-to-br',
            $palette['from'],
            $palette['to'],
            $imageUrl ? 'hidden' : '',
        ])
    >
        <span class="text-5xl font-bold {{ $palette['text'] }} select-none drop-shadow-sm">{{ $initial }}</span>
    </div>
</div>
