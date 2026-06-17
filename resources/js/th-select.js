import '../css/th-select.css';

function chevronSvg() {
    return `<svg class="th-select-chevron" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"/></svg>`;
}

function shouldSkip(select) {
    if (!select || select.tagName !== 'SELECT') return true;
    if (select.dataset.thSelectInit === '1') return true;
    if (select.dataset.thSelect === 'skip') return true;
    if (select.multiple || select.size > 1) return true;
    if (select.closest('[data-th-select-skip]')) return true;
    return false;
}

function closeAllThSelects(exceptWrapper = null) {
    document.querySelectorAll('select[data-th-select-init="1"]').forEach((select) => {
        const instance = select._thSelectInstance;
        if (!instance?.wrapper?.classList.contains('is-open')) {
            return;
        }
        if (exceptWrapper && instance.wrapper === exceptWrapper) {
            return;
        }
        instance.close();
    });
}

class ThSelect {
    constructor(select) {
        this.select = select;
        select.dataset.thSelectInit = '1';
        select._thSelectInstance = this;
        this.optionEls = [];
        this.focusIndex = -1;
        this.build();
        this.syncFromNative();
        select.addEventListener('change', () => this.syncFromNative());

        this.observer = new MutationObserver((mutations) => {
            let optionsChanged = false;
            let disabledChanged = false;
            mutations.forEach((m) => {
                if (m.type === 'childList') {
                    optionsChanged = true;
                } else if (m.type === 'attributes' && m.attributeName === 'disabled') {
                    disabledChanged = true;
                }
            });

            if (optionsChanged || disabledChanged) {
                this.updateOptions();
            }
        });
        this.observer.observe(this.select, {
            childList: true,
            attributes: true,
            attributeFilter: ['disabled']
        });
    }

    updateOptions() {
        if (!this.wrapper) return;

        // Sync disabled state
        this.wrapper.classList.toggle('is-disabled', this.select.disabled);

        // Rebuild list items
        this.list.innerHTML = '';
        this.optionEls = [];

        Array.from(this.select.options).forEach((opt) => {
            const li = document.createElement('li');
            li.className = 'th-select-option';
            li.dataset.value = opt.value;
            li.textContent = opt.text;
            li.setAttribute('role', 'option');
            if (opt.disabled) li.classList.add('is-disabled');
            li.addEventListener('click', (e) => {
                e.stopPropagation();
                if (opt.disabled) return;
                this.choose(opt.value);
            });
            this.list.appendChild(li);
            this.optionEls.push({ opt, li });
        });

        // Re-append empty state
        this.emptyState = document.createElement('li');
        this.emptyState.className = 'th-select-empty';
        this.emptyState.textContent = 'No matches found';
        this.emptyState.hidden = true;
        this.list.appendChild(this.emptyState);

        // Re-evaluate search wrap
        const optionCount = this.select.options.length;
        const existingSearchWrap = this.popover.querySelector('.th-select-search-wrap');
        if (optionCount > 7) {
            if (!existingSearchWrap) {
                const searchWrap = document.createElement('div');
                searchWrap.className = 'th-select-search-wrap';
                this.search = document.createElement('input');
                this.search.type = 'text';
                this.search.className = 'th-select-search';
                this.search.placeholder = 'Search options…';
                this.search.setAttribute('autocomplete', 'off');
                searchWrap.appendChild(this.search);
                this.popover.insertBefore(searchWrap, this.list);
                this.search.addEventListener('input', () => this.filterOptions());
                this.search.addEventListener('click', (e) => e.stopPropagation());
            }
        } else {
            if (existingSearchWrap) {
                existingSearchWrap.remove();
                this.search = null;
            }
        }

        this.syncFromNative();
    }

    build() {
        const wrapper = document.createElement('div');
        wrapper.className = 'th-select';
        if (this.select.disabled) wrapper.classList.add('is-disabled');

        this.trigger = document.createElement('button');
        this.trigger.type = 'button';
        this.trigger.className = 'th-select-trigger';
        this.trigger.setAttribute('aria-haspopup', 'listbox');
        this.trigger.setAttribute('aria-expanded', 'false');

        this.labelEl = document.createElement('span');
        this.labelEl.className = 'th-select-value';

        this.popover = document.createElement('div');
        this.popover.className = 'th-select-dropdown';
        this.popover.setAttribute('role', 'listbox');

        const optionCount = this.select.options.length;
        if (optionCount > 7) {
            const searchWrap = document.createElement('div');
            searchWrap.className = 'th-select-search-wrap';
            this.search = document.createElement('input');
            this.search.type = 'text';
            this.search.className = 'th-select-search';
            this.search.placeholder = 'Search options…';
            this.search.setAttribute('autocomplete', 'off');
            searchWrap.appendChild(this.search);
            this.popover.appendChild(searchWrap);
            this.search.addEventListener('input', () => this.filterOptions());
            this.search.addEventListener('click', (e) => e.stopPropagation());
        }

        this.list = document.createElement('ul');
        this.list.className = 'th-select-list';
        this.popover.appendChild(this.list);

        this.emptyState = document.createElement('li');
        this.emptyState.className = 'th-select-empty';
        this.emptyState.textContent = 'No matches found';
        this.emptyState.hidden = true;

        Array.from(this.select.options).forEach((opt) => {
            const li = document.createElement('li');
            li.className = 'th-select-option';
            li.dataset.value = opt.value;
            li.textContent = opt.text;
            li.setAttribute('role', 'option');
            if (opt.disabled) li.classList.add('is-disabled');
            li.addEventListener('click', (e) => {
                e.stopPropagation();
                if (opt.disabled) return;
                this.choose(opt.value);
            });
            this.list.appendChild(li);
            this.optionEls.push({ opt, li });
        });

        this.list.appendChild(this.emptyState);

        this.trigger.appendChild(this.labelEl);
        this.trigger.insertAdjacentHTML('beforeend', chevronSvg());

        const parent = this.select.parentNode;
        parent.insertBefore(wrapper, this.select);
        wrapper.appendChild(this.trigger);
        wrapper.appendChild(this.popover);
        wrapper.appendChild(this.select);
        this.select.classList.add('th-select-native');

        this.wrapper = wrapper;

        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (this.select.disabled) return;
            this.toggle();
        });

        this.trigger.addEventListener('keydown', (e) => this.onTriggerKeydown(e));
    }

    positionDropdown() {
        const rect = this.trigger.getBoundingClientRect();
        const width = Math.max(rect.width, 160);

        if (this.popover.parentElement !== document.body) {
            document.body.appendChild(this.popover);
        }

        this.popover.classList.add('is-floating');
        this.popover.style.width = `${width}px`;

        let top = rect.bottom + 6;
        let left = rect.left;

        this.popover.style.top = `${top}px`;
        this.popover.style.left = `${left}px`;

        const menuRect = this.popover.getBoundingClientRect();
        const menuHeight = menuRect.height || this.popover.scrollHeight;
        if (top + menuHeight > window.innerHeight - 8) {
            top = Math.max(8, rect.top - menuHeight - 6);
            this.popover.style.top = `${top}px`;
        }
        if (left + width > window.innerWidth - 8) {
            left = Math.max(8, window.innerWidth - width - 8);
            this.popover.style.left = `${left}px`;
        }
    }

    restoreDropdown() {
        this.popover.classList.remove('is-open-menu', 'is-floating');
        this.popover.style.top = '';
        this.popover.style.left = '';
        this.popover.style.width = '';

        if (this.wrapper && this.popover.parentElement === document.body) {
            this.wrapper.appendChild(this.popover);
        }
    }

    toggle() {
        const isOpen = this.wrapper.classList.contains('is-open');
        if (isOpen) {
            this.close();
        } else {
            closeAllThSelects(this.wrapper);
            this.wrapper.classList.add('is-open');
            this.popover.classList.add('is-open-menu');
            this.trigger.setAttribute('aria-expanded', 'true');
            this.positionDropdown();
            if (this.search) {
                this.search.value = '';
                this.filterOptions();
                setTimeout(() => this.search.focus(), 0);
            }
        }
    }

    close() {
        this.wrapper.classList.remove('is-open');
        this.popover.classList.remove('is-open-menu');
        this.restoreDropdown();
        this.trigger.setAttribute('aria-expanded', 'false');
        this.focusIndex = -1;
        this.clearFocus();
    }

    choose(value) {
        this.select.value = value;
        this.select.dispatchEvent(new Event('change', { bubbles: true }));
        this.syncFromNative();
        this.close();
    }

    syncFromNative() {
        const opt = this.select.options[this.select.selectedIndex];
        const text = opt ? opt.text : '';
        const isPlaceholder = opt && opt.value === '' && text !== '';

        this.labelEl.textContent = text || 'Select…';
        this.labelEl.classList.toggle('is-placeholder', !opt || opt.value === '');

        this.optionEls.forEach(({ opt: o, li }) => {
            li.classList.toggle('is-selected', o.value === this.select.value);
        });
    }

    filterOptions() {
        const q = (this.search?.value || '').trim().toLowerCase();
        let visible = 0;

        this.optionEls.forEach(({ opt, li }) => {
            const match = !q || opt.text.toLowerCase().includes(q);
            li.hidden = !match;
            if (match) visible += 1;
        });

        this.emptyState.hidden = visible > 0;
    }

    onTriggerKeydown(e) {
        if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            if (!this.wrapper.classList.contains('is-open')) this.toggle();
        }
        if (e.key === 'Escape') this.close();
    }

    clearFocus() {
        this.optionEls.forEach(({ li }) => li.classList.remove('is-focused'));
    }
}

export function initThSelects(root = document) {
    root.querySelectorAll('select').forEach((select) => {
        if (!shouldSkip(select)) {
            new ThSelect(select);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => initThSelects());
document.addEventListener('th-select:scan', (e) => {
    initThSelects(e.detail?.root || document);
});

document.addEventListener('th-select:refresh', (e) => {
    const select = e.detail?.select;
    if (select?._thSelectInstance) {
        select._thSelectInstance.updateOptions();
    }
});

document.addEventListener('click', () => closeAllThSelects());
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAllThSelects();
});

let selectObserveTimer;
const selectObserver = new MutationObserver(() => {
    clearTimeout(selectObserveTimer);
    selectObserveTimer = setTimeout(() => initThSelects(), 150);
});
selectObserver.observe(document.documentElement, { childList: true, subtree: true });
