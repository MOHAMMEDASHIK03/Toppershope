{{-- Themed confirm dialog for delete / destructive actions (all panels). Driven by resources/js/th-confirm.js --}}
<div
    id="th-confirm-modal"
    class="th-confirm-modal hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="th-confirm-title"
    aria-hidden="true"
>
    <div id="th-confirm-backdrop" class="th-confirm-backdrop" tabindex="-1"></div>
    <div class="th-confirm-card">
        <div class="th-confirm-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 256 256">
                <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"/>
            </svg>
        </div>
        <h2 id="th-confirm-title" class="th-confirm-title">Confirm deletion</h2>
        <p id="th-confirm-message" class="th-confirm-message"></p>
        <div class="th-confirm-actions">
            <button type="button" id="th-confirm-cancel" class="th-confirm-btn th-confirm-btn--cancel">
                Cancel
            </button>
            <button type="button" id="th-confirm-ok" class="th-confirm-btn th-confirm-btn--danger">
                Delete
            </button>
        </div>
    </div>
</div>
