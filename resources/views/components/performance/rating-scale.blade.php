@props([
    'name' => 'rating',
    'value' => 3,
    'required' => true,
])

@php
    $selected = (int) old($name, $value);
    $hints = [
        1 => 'Needs improvement',
        2 => 'Below expectations',
        3 => 'Meets expectations',
        4 => 'Exceeds expectations',
        5 => 'Outstanding',
    ];
@endphp

<div class="perf-rating" data-rating-root>
    <div class="perf-rating__track" role="radiogroup" aria-label="Performance rating from 1 to 5">
        @for($i = 1; $i <= 5; $i++)
            <label class="perf-rating__option">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $i }}"
                    class="perf-rating__input"
                    @if($required) required @endif
                    {{ $selected === $i ? 'checked' : '' }}
                    data-rating-input
                >
                <span class="perf-rating__num">{{ $i }}</span>
            </label>
        @endfor
    </div>
    <div class="perf-rating__labels">
        <span>Needs work</span>
        <span class="perf-rating__hint" data-rating-hint>{{ $hints[$selected] ?? $hints[3] }}</span>
        <span>Exceptional</span>
    </div>
</div>

@once
    @push('scripts')
    <script>
        document.querySelectorAll('[data-rating-root]').forEach((root) => {
            const hints = @json($hints);
            const hintEl = root.querySelector('[data-rating-hint]');
            root.querySelectorAll('[data-rating-input]').forEach((input) => {
                input.addEventListener('change', () => {
                    if (hintEl) hintEl.textContent = hints[input.value] || '';
                });
            });
        });
    </script>
    @endpush
@endonce
