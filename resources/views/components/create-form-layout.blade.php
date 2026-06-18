@props([
    'backHref',
    'backLabel' => 'Back',
    'title',
    'subtitle' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Create',
    'cancelHref' => null,
    'maxWidth' => 'max-w-3xl',
    'layoutView' => null,
    'enctype' => null,
    'submitIcon' => 'ph-check-circle',
    'formId' => null,
    'deleteAction' => null,
    'deleteLabel' => 'Delete',
    'deleteConfirm' => 'Are you sure you want to delete this record?',
])

@php
    $cancelHref = $cancelHref ?? $backHref;
    $httpMethod = strtoupper($method);
    $backHoverClass = 'hover:text-primary-700';
    $formId = $formId ?? 'panel-create-form-' . substr(md5($action), 0, 8);
@endphp

<div class="{{ $maxWidth }}">
    <a href="{{ $backHref }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 {{ $backHoverClass }} transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        {{ $backLabel }}
    </a>

    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-slate-800">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-sm text-slate-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
        <form
            id="{{ $formId }}"
            action="{{ $action }}"
            method="POST"
            @if($enctype) enctype="{{ $enctype }}" @endif
            class="space-y-6"
        >
            @csrf
            @if(!in_array($httpMethod, ['GET', 'POST']))
                @method($httpMethod)
            @endif

            {{ $slot }}
        </form>

        @if(isset($footer))
            <div class="pt-2 border-t border-slate-100 mt-6">
                {{ $footer }}
            </div>
        @else
            <x-panel.form-footer
                :form-id="$formId"
                :submit-label="$submitLabel"
                :cancel-href="$cancelHref"
                :delete-action="$deleteAction"
                :delete-label="$deleteLabel"
                :delete-confirm="$deleteConfirm"
                class="!mt-6"
            />
        @endif
    </div>
</div>
