@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => ' ',
    'hint' => null,
    'rows' => 3,
    'options' => [],
    'colSpan' => '',
    'floating' => false,
])

@php
    $inputClass = 'w-full rounded-xl border border-gray-200 dark:border-[#2a2a32] bg-gray-50 dark:bg-[#141418] px-4 py-2.5 text-sm text-gray-900 dark:text-zinc-100 placeholder:text-gray-400 dark:placeholder:text-zinc-500 focus:bg-white dark:focus:bg-[#18181c] focus:ring-4 focus:ring-primary-600/15 focus:border-primary-600 transition-all outline-none [&>option]:text-gray-900 dark:[&>option]:text-zinc-100 dark:[&>option]:bg-[#18181c]';
    $labelClass = 'block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5';
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div class="{{ $colSpan }} @if($floating) th-field-floating @if($hasError) is-error @endif @endif">
    @if($floating)
        @if($type === 'textarea')
            <textarea
                id="{{ $id }}"
                name="{{ $name }}"
                rows="{{ $rows }}"
                @if($required) required @endif
                placeholder="{{ $placeholder }}"
                {{ $attributes->merge(['class' => 'th-field-input resize-none']) }}
            >{{ old($name, $value) }}</textarea>
        @else
            <input
                id="{{ $id }}"
                type="{{ $type }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                @if($required) required @endif
                placeholder="{{ $placeholder }}"
                {{ $attributes->merge(['class' => 'th-field-input' . (in_array($type, ['date', 'month', 'datetime-local'], true) ? ' th-picker-source' : '')]) }}
            />
        @endif
        <label for="{{ $id }}" class="th-field-label">
            {{ $label }}@if($required)<span class="text-red-500">*</span>@endif
        </label>
    @else
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
                placeholder="{{ $placeholder !== ' ' ? $placeholder : '' }}"
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
                placeholder="{{ $placeholder !== ' ' ? $placeholder : '' }}"
                {{ $attributes->merge(['class' => $inputClass . ' th-picker-source']) }}
            />
        @else
            <input
                id="{{ $id }}"
                type="{{ $type }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                @if($required) required @endif
                placeholder="{{ $placeholder !== ' ' ? $placeholder : '' }}"
                {{ $attributes->merge(['class' => $inputClass]) }}
            />
        @endif
    @endif

    @if($hint)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
    @enderror
</div>
