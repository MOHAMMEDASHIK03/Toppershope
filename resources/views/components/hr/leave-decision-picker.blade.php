@props(['name' => 'status', 'value' => 'pending'])

@php
    $current = old($name, $value);
    $options = [
        'approved' => [
            'label' => 'Approve request',
            'desc' => 'Grant this time off.',
            'variant' => 'approved',
        ],
        'pending' => [
            'label' => 'Keep pending',
            'desc' => 'No decision yet — stays in the queue.',
            'variant' => 'pending',
        ],
        'rejected' => [
            'label' => 'Reject request',
            'desc' => 'Decline with optional remarks below.',
            'variant' => 'rejected',
        ],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 overflow-visible" role="radiogroup" aria-label="Leave decision">
    @foreach($options as $key => $opt)
        <label class="panel-radio-card">
            <input
                type="radio"
                name="{{ $name }}"
                value="{{ $key }}"
                class="panel-radio-card__input"
                {{ $current === $key ? 'checked' : '' }}
                required
            >
            <div class="panel-radio-card__face panel-radio-card__face--{{ $opt['variant'] }}">
                {{ $opt['label'] }}
            </div>
        </label>
    @endforeach
</div>
