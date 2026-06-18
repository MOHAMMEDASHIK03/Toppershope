@props([
    'variant' => 'full',
    'context' => 'default',
    'class' => '',
])

@php
    $alt = "Topper's Hope";

    $sizes = [
        'full' => [
            'navbar'  => 'h-11 sm:h-12 md:h-[52px] w-auto max-w-[min(300px,58vw)] object-contain object-left shrink-0',
            'footer'  => 'h-12 sm:h-14 w-auto max-w-[280px] object-contain object-left shrink-0',
            'auth'    => 'h-16 sm:h-[4.5rem] w-auto max-w-[min(360px,85vw)] object-contain mx-auto',
            'sidebar' => 'h-9 w-auto max-w-[168px] object-contain object-left shrink-0',
            'default' => 'h-11 sm:h-12 w-auto max-w-[260px] object-contain object-left shrink-0',
        ],
        'icon' => [
            'navbar'  => 'h-11 w-11 sm:h-12 sm:w-12 object-contain shrink-0',
            'sidebar' => 'h-10 w-10 object-contain shrink-0',
            'default' => 'h-10 w-10 object-contain shrink-0',
        ],
    ];

    $src = $variant === 'icon'
        ? asset('images/brand/logo-icon.png')
        : asset('images/brand/logo-full.png');

    $defaultClass = $sizes[$variant][$context] ?? $sizes[$variant]['default'];
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => trim($defaultClass . ' ' . $class)]) }} loading="eager" decoding="async" />
