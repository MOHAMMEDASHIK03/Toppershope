@props([
    'formId' => null,
    'submitLabel' => 'Save',
    'cancelHref' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
    'stacked' => false,
])

@php
    $stacked = filter_var($stacked, FILTER_VALIDATE_BOOLEAN);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center ' . ($stacked ? 'flex-col' : 'justify-between') . ' gap-3 pt-6 mt-6 border-t border-slate-100']) }}>
    <div class="flex flex-wrap items-center gap-3 {{ $stacked ? 'w-full flex-col' : '' }}">
        <button
            type="submit"
            @if($formId) form="{{ $formId }}" @endif
            class="btn-primary px-5 py-2.5 rounded-xl font-bold text-sm focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ $stacked ? 'w-full' : '' }}"
        >
            {{ $submitLabel }}
        </button>
        @if($cancelHref)
            <a
                href="{{ $cancelHref }}"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-center {{ $stacked ? 'w-full' : '' }}"
            >
                Cancel
            </a>
        @endif
    </div>

    @if($deleteAction)
        <form
            action="{{ $deleteAction }}"
            method="POST"
            class="{{ $stacked ? 'w-full' : 'inline-flex' }}"
            data-confirm="{{ $deleteConfirm }}"
        >
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="btn-danger px-5 py-2.5 rounded-xl font-bold text-sm focus:ring-2 focus:ring-offset-2 focus:ring-red-500 {{ $stacked ? 'w-full' : '' }}"
            >
                {{ $deleteLabel }}
            </button>
        </form>
    @endif
</div>
