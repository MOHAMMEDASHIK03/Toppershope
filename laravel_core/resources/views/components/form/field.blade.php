@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'rows' => 3,
    'options' => [],
    'colSpan' => '',
])

@php
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all [&>option]:text-slate-900';
    $labelClass = 'block text-sm font-semibold text-slate-700 mb-1.5';
    $id = $attributes->get('id', $name);
@endphp

<div class="{{ $colSpan }}">
    <label for="{{ $id }}" class="{{ $labelClass }}">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $inputClass . ' resize-none']) }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => $inputClass]) }}
        >
            {{ $slot }}
        </select>
    @elseif(in_array($type, ['date', 'month', 'datetime-local'], true))
        <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $inputClass . ' th-picker-source']) }}
        />
    @else
        <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $inputClass]) }}
        />
    @endif

    @if($hint)
        <p class="text-xs text-slate-500 mt-1">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
    @enderror
</div>
