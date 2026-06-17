/**
 * Themed confirm dialog for panel delete / destructive actions.
 * - Forms with data-confirm are intercepted before submit.
 * - Legacy onsubmit/onclick confirm(...) is upgraded on load.
 * - DELETE forms without a message get a sensible default.
 */

let pendingForm = null;
let pendingTrigger = null;

function byId(id) {
    return document.getElementById(id);
}

function extractConfirmMessage(source) {
    if (!source) {
        return null;
    }

    const patterns = [
        /confirm\s*\(\s*'((?:\\'|[^'])*)'\s*\)/,
        /confirm\s*\(\s*"((?:\\"|[^"])*)"\s*\)/,
        /confirm\s*\(\s*`((?:\\`|[^`])*)`\s*\)/,
    ];

    for (const pattern of patterns) {
        const match = source.match(pattern);
        if (match) {
            return match[1]
                .replace(/\\'/g, "'")
                .replace(/\\"/g, '"')
                .replace(/\\n/g, '\n');
        }
    }

    return null;
}

function isDeleteForm(form) {
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput?.value?.toUpperCase() === 'DELETE') {
        return true;
    }

    return form.getAttribute('method')?.toUpperCase() === 'DELETE';
}

function confirmActionLabel(message) {
    if (/delete|remove|revoke|permanently/i.test(message || '')) {
        return 'Delete';
    }

    return 'Confirm';
}

function confirmTitle(message) {
    if (/delete|remove|revoke|permanently/i.test(message || '')) {
        return 'Confirm deletion';
    }

    return 'Please confirm';
}

function openModal(message) {
    const modal = byId('th-confirm-modal');
    if (!modal) {
        return false;
    }

    const msg = message || 'Are you sure you want to continue?';
    const destructive = confirmActionLabel(msg) === 'Delete';

    byId('th-confirm-message').textContent = msg;
    byId('th-confirm-title').textContent = confirmTitle(msg);

    const okBtn = byId('th-confirm-ok');
    okBtn.textContent = confirmActionLabel(msg);
    okBtn.classList.toggle('th-confirm-btn--danger', destructive);
    okBtn.classList.toggle('th-confirm-btn--primary', !destructive);

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    byId('th-confirm-cancel')?.focus();

    return true;
}

function closeModal() {
    const modal = byId('th-confirm-modal');
    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
    pendingForm = null;
    pendingTrigger = null;
}

function approveAndSubmit() {
    if (pendingForm) {
        pendingForm.dataset.thConfirmApproved = '1';
        if (typeof pendingForm.requestSubmit === 'function') {
            pendingForm.requestSubmit();
        } else {
            pendingForm.submit();
        }
    } else if (pendingTrigger) {
        pendingTrigger.dataset.thConfirmApproved = '1';
        pendingTrigger.click();
    }

    closeModal();
}

function upgradeLegacyConfirms() {
    document.querySelectorAll('form[onsubmit]').forEach((form) => {
        const message = extractConfirmMessage(form.getAttribute('onsubmit'));
        if (!message) {
            return;
        }

        form.dataset.confirm = message;
        form.removeAttribute('onsubmit');
    });

    document.querySelectorAll('[onclick]').forEach((el) => {
        const onclick = el.getAttribute('onclick');
        const message = extractConfirmMessage(onclick);
        if (!message) {
            return;
        }

        const form = el.closest('form');
        if (form && (el.type === 'submit' || el.tagName === 'BUTTON')) {
            form.dataset.confirm = message;
            el.removeAttribute('onclick');
            return;
        }

        el.dataset.confirm = message;
        el.dataset.confirmTrigger = '1';
        el.removeAttribute('onclick');
    });
}

function initThConfirm() {
    const modal = byId('th-confirm-modal');
    if (!modal) {
        return;
    }

    upgradeLegacyConfirms();

    byId('th-confirm-cancel')?.addEventListener('click', closeModal);
    byId('th-confirm-backdrop')?.addEventListener('click', closeModal);
    byId('th-confirm-ok')?.addEventListener('click', approveAndSubmit);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (form.dataset.thConfirmApproved === '1') {
                delete form.dataset.thConfirmApproved;
                return;
            }

            let message = form.dataset.confirm;
            if (!message && isDeleteForm(form)) {
                message =
                    form.dataset.confirmDefault ||
                    'Are you sure you want to delete this? This cannot be undone.';
            }

            if (!message) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            pendingForm = form;
            openModal(message);
        },
        true,
    );

    document.addEventListener(
        'click',
        (event) => {
            const el = event.target.closest('[data-confirm-trigger]');
            if (!el) {
                return;
            }

            if (el.dataset.thConfirmApproved === '1') {
                delete el.dataset.thConfirmApproved;
                return;
            }

            const message = el.dataset.confirm;
            if (!message) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            pendingTrigger = el;
            openModal(message);
        },
        true,
    );
}

document.addEventListener('DOMContentLoaded', initThConfirm);
