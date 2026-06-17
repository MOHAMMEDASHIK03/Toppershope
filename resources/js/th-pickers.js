import '../css/th-pickers.css';

const MONTH_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const MONTH_LONG = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

function pad2(n) {
    return String(n).padStart(2, '0');
}

function parseYmd(value) {
    if (!value) return null;
    const m = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (!m) return null;
    return { y: +m[1], mo: +m[2] - 1, d: +m[3] };
}

function parseYm(value) {
    if (!value) return null;
    const m = String(value).match(/^(\d{4})-(\d{2})/);
    if (!m) return null;
    return { y: +m[1], mo: +m[2] - 1 };
}

function parseDatetimeLocal(value) {
    if (!value) return { date: null, time: '' };
    const m = String(value).match(/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2})/);
    if (!m) return { date: parseYmd(value), time: '' };
    return { date: parseYmd(m[1]), time: m[2] };
}

function formatYmd(y, mo, d) {
    return `${y}-${pad2(mo + 1)}-${pad2(d)}`;
}

function formatYm(y, mo) {
    return `${y}-${pad2(mo + 1)}`;
}

function formatDisplayDate(y, mo, d) {
    return `${MONTH_LONG[mo]} ${d}, ${y}`;
}

function formatDisplayMonth(y, mo) {
    return `${MONTH_LONG[mo]}, ${y}`;
}

function calendarIcon() {
    return `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Z"/></svg>`;
}

function closeAllPickers(except = null) {
    document.querySelectorAll('.th-picker.is-open').forEach((el) => {
        if (el !== except) el.classList.remove('is-open');
    });
}

class BasePicker {
    constructor(input) {
        this.input = input;
        this.input.dataset.thPickerInit = '1';
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'th-picker';
        if (input.classList.contains('w-auto') || input.closest('.flex')) {
            /* keep full width by default */
        }
        this.trigger = document.createElement('button');
        this.trigger.type = 'button';
        this.trigger.className = 'th-picker-trigger';
        this.popover = document.createElement('div');
        this.popover.className = 'th-picker-popover';
        this.popover.setAttribute('role', 'dialog');
        this.input.parentNode.insertBefore(this.wrapper, this.input);
        this.wrapper.appendChild(this.trigger);
        this.wrapper.appendChild(this.popover);
        this.wrapper.appendChild(this.input);
        this.input.classList.add('th-picker-native');

        this.trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasOpen = this.wrapper.classList.contains('is-open');
            closeAllPickers();
            if (!wasOpen) {
                if (typeof this.resetCalView === 'function') {
                    this.resetCalView();
                }
                this.wrapper.classList.add('is-open');
                this.alignPopover();
            }
        });

        this.wrapper.addEventListener('click', (e) => e.stopPropagation());
    }

    alignPopover() {
        const rect = this.wrapper.getBoundingClientRect();
        let spaceRight = window.innerWidth - rect.left;

        let container = this.wrapper.closest('.relative, .modal, [class*="overflow-"], [class*="max-w-"]');
        if (!container) {
            container = this.wrapper.closest('.bg-white.rounded-2xl.shadow-xl');
        }

        if (container) {
            const containerRect = container.getBoundingClientRect();
            spaceRight = containerRect.right - rect.left;
        }

        if (spaceRight < 300) {
            this.popover.classList.add('th-picker-popover--align-right');
        } else {
            this.popover.classList.remove('th-picker-popover--align-right');
        }
    }

    setNativeValue(value) {
        this.input.value = value ?? '';
        this.input.dispatchEvent(new Event('input', { bubbles: true }));
        this.input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    updateTriggerLabel(text, isPlaceholder = false) {
        this.trigger.innerHTML = `<span>${text}</span>${calendarIcon()}`;
        this.trigger.classList.toggle('is-placeholder', isPlaceholder);
    }
}

class ThMonthPicker extends BasePicker {
    constructor(input) {
        super(input);
        this.viewYear = new Date().getFullYear();
        this.buildMonthUi();
        this.syncFromInput();
    }

    buildMonthUi() {
        this.yearBar = document.createElement('div');
        this.yearBar.className = 'th-picker-year-bar';
        this.yearBar.innerHTML = `
            <button type="button" class="th-picker-nav-btn" data-year="-1" aria-label="Previous year">‹</button>
            <span class="th-picker-year-label"></span>
            <button type="button" class="th-picker-nav-btn th-picker-nav-btn--accent" data-year="1" aria-label="Next year">›</button>
        `;
        this.monthGrid = document.createElement('div');
        this.monthGrid.className = 'th-picker-month-grid';
        MONTH_SHORT.forEach((label, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'th-picker-month';
            btn.textContent = label;
            btn.dataset.month = String(idx);
            this.monthGrid.appendChild(btn);
        });
        const footer = document.createElement('div');
        footer.className = 'th-picker-footer';
        footer.innerHTML = `
            <button type="button" data-action="clear">Clear</button>
            <button type="button" data-action="this-month">This month</button>
        `;
        this.popover.append(this.yearBar, this.monthGrid, footer);

        this.yearBar.querySelector('[data-year="-1"]').addEventListener('click', () => {
            this.viewYear -= 1;
            this.renderMonths();
        });
        this.yearBar.querySelector('[data-year="1"]').addEventListener('click', () => {
            this.viewYear += 1;
            this.renderMonths();
        });
        this.monthGrid.addEventListener('click', (e) => {
            const btn = e.target.closest('.th-picker-month');
            if (!btn) return;
            const mo = +btn.dataset.month;
            this.selectMonth(this.viewYear, mo);
        });
        footer.querySelector('[data-action="clear"]').addEventListener('click', () => {
            this.setNativeValue('');
            this.updateTriggerLabel('Select month', true);
            this.wrapper.classList.remove('is-open');
            this.renderMonths();
        });
        footer.querySelector('[data-action="this-month"]').addEventListener('click', () => {
            const now = new Date();
            this.selectMonth(now.getFullYear(), now.getMonth());
        });
    }

    syncFromInput() {
        const parsed = parseYm(this.input.value);
        if (parsed) {
            this.viewYear = parsed.y;
            this.updateTriggerLabel(formatDisplayMonth(parsed.y, parsed.mo));
            this.renderMonths(parsed.y, parsed.mo);
        } else {
            this.updateTriggerLabel('Select month', true);
            this.renderMonths();
        }
    }

    selectMonth(y, mo) {
        this.setNativeValue(formatYm(y, mo));
        this.viewYear = y;
        this.updateTriggerLabel(formatDisplayMonth(y, mo));
        this.renderMonths(y, mo);
        this.wrapper.classList.remove('is-open');
    }

    renderMonths(selectedY = null, selectedMo = null) {
        const parsed = parseYm(this.input.value);
        const selY = selectedY ?? parsed?.y;
        const selMo = selectedMo ?? parsed?.mo;
        this.yearBar.querySelector('.th-picker-year-label').textContent = String(this.viewYear);
        this.monthGrid.querySelectorAll('.th-picker-month').forEach((btn) => {
            const mo = +btn.dataset.month;
            btn.classList.toggle('is-selected', selY === this.viewYear && selMo === mo);
        });
    }
}

class ThDatePicker extends BasePicker {
    constructor(input, options = {}) {
        super(input);
        this.includeTime = options.includeTime || false;
        const now = new Date();
        this.viewYear = now.getFullYear();
        this.viewMonth = now.getMonth();
        this.calView = 'days'; // days | months | years
        this.yearPageStart = Math.floor(this.viewYear / 12) * 12;
        this.buildCalendarUi();
        this.syncFromInput();
    }

    setCalView(view) {
        this.calView = view;
        this.calBody.classList.remove('th-picker-cal-body--days', 'th-picker-cal-body--months', 'th-picker-cal-body--years');
        this.calBody.classList.add(`th-picker-cal-body--${view}`);
        this.titleMonthBtn.classList.toggle('is-active', view === 'months');
        this.titleYearBtn.classList.toggle('is-active', view === 'years');
        this.renderCalendar();
    }

    buildCalendarUi() {
        this.calHeader = document.createElement('div');
        this.calHeader.className = 'th-picker-cal-header';
        this.calHeader.innerHTML = `
            <button type="button" class="th-picker-nav-btn" data-nav="prev" aria-label="Previous">‹</button>
            <div class="th-picker-cal-title">
                <button type="button" class="th-picker-title-part" data-part="month"></button>
                <span class="th-picker-title-sep" aria-hidden="true"> </span>
                <button type="button" class="th-picker-title-part" data-part="year"></button>
            </div>
            <button type="button" class="th-picker-nav-btn th-picker-nav-btn--accent" data-nav="next" aria-label="Next">›</button>
        `;
        this.titleMonthBtn = this.calHeader.querySelector('[data-part="month"]');
        this.titleYearBtn = this.calHeader.querySelector('[data-part="year"]');
        this.navPrev = this.calHeader.querySelector('[data-nav="prev"]');
        this.navNext = this.calHeader.querySelector('[data-nav="next"]');

        this.calBody = document.createElement('div');
        this.calBody.className = 'th-picker-cal-body th-picker-cal-body--days';

        this.weekdays = document.createElement('div');
        this.weekdays.className = 'th-picker-weekdays';
        ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach((d) => {
            const el = document.createElement('span');
            el.className = 'th-picker-weekday';
            el.textContent = d;
            this.weekdays.appendChild(el);
        });
        this.daysGrid = document.createElement('div');
        this.daysGrid.className = 'th-picker-days';

        this.inlineMonthGrid = document.createElement('div');
        this.inlineMonthGrid.className = 'th-picker-inline-month-grid';
        MONTH_SHORT.forEach((label, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'th-picker-month';
            btn.textContent = label;
            btn.dataset.month = String(idx);
            this.inlineMonthGrid.appendChild(btn);
        });

        this.yearGrid = document.createElement('div');
        this.yearGrid.className = 'th-picker-year-grid';

        this.calBody.append(this.weekdays, this.daysGrid, this.inlineMonthGrid, this.yearGrid);
        this.popover.append(this.calHeader, this.calBody);

        if (this.includeTime) {
            this.timeRow = document.createElement('div');
            this.timeRow.className = 'th-picker-time-row';
            this.timeRow.innerHTML = `<label>Time</label><input type="time" class="th-picker-time-input" />`;
            this.popover.appendChild(this.timeRow);
            this.timeInput = this.timeRow.querySelector('.th-picker-time-input');
            this.timeInput.addEventListener('change', () => {
                this.applyDatetimeFromParts();
                this.wrapper.classList.remove('is-open');
            });
        }

        this.titleMonthBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.setCalView('months');
        });
        this.titleYearBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.yearPageStart = Math.floor(this.viewYear / 12) * 12;
            this.setCalView('years');
        });

        this.navPrev.addEventListener('click', () => this.navigate(-1));
        this.navNext.addEventListener('click', () => this.navigate(1));

        this.daysGrid.addEventListener('click', (e) => {
            const btn = e.target.closest('.th-picker-day');
            if (!btn || btn.disabled) return;
            const y = +btn.dataset.y;
            const mo = +btn.dataset.mo;
            const d = +btn.dataset.d;
            if (this.includeTime) {
                this.selectedDate = { y, mo, d };
                if (!this.timeInput.value) {
                    this.timeInput.value = '09:00';
                }
                this.applyDatetimeFromParts();
            } else {
                this.selectDate(y, mo, d);
            }
        });

        this.inlineMonthGrid.addEventListener('click', (e) => {
            const btn = e.target.closest('.th-picker-month');
            if (!btn) return;
            this.viewMonth = +btn.dataset.month;
            this.setCalView('days');
        });

        this.yearGrid.addEventListener('click', (e) => {
            const btn = e.target.closest('.th-picker-year');
            if (!btn) return;
            this.viewYear = +btn.dataset.year;
            this.setCalView('months');
        });
    }

    resetCalView() {
        this.calView = 'days';
        this.yearPageStart = Math.floor(this.viewYear / 12) * 12;
        this.calBody.classList.remove('th-picker-cal-body--months', 'th-picker-cal-body--years');
        this.calBody.classList.add('th-picker-cal-body--days');
        this.titleMonthBtn?.classList.remove('is-active');
        this.titleYearBtn?.classList.remove('is-active');
    }

    navigate(direction) {
        if (this.calView === 'days') {
            this.viewMonth += direction;
            if (this.viewMonth < 0) {
                this.viewMonth = 11;
                this.viewYear -= 1;
            } else if (this.viewMonth > 11) {
                this.viewMonth = 0;
                this.viewYear += 1;
            }
        } else if (this.calView === 'months') {
            this.viewYear += direction;
        } else {
            this.yearPageStart += direction * 12;
        }
        this.renderCalendar();
    }

    getMinMax() {
        const min = this.input.getAttribute('min');
        const max = this.input.getAttribute('max');
        return {
            min: this.includeTime ? parseDatetimeLocal(min) : { date: parseYmd(min), time: '' },
            max: this.includeTime ? parseDatetimeLocal(max) : { date: parseYmd(max), time: '' },
        };
    }

    isBefore(date, limit) {
        if (!limit?.date) return false;
        const a = date.y * 10000 + date.mo * 100 + date.d;
        const b = limit.date.y * 10000 + limit.date.mo * 100 + limit.date.d;
        return a < b;
    }

    isAfter(date, limit) {
        if (!limit?.date) return false;
        const a = date.y * 10000 + date.mo * 100 + date.d;
        const b = limit.date.y * 10000 + limit.date.mo * 100 + limit.date.d;
        return a > b;
    }

    syncFromInput() {
        if (this.includeTime) {
            const { date, time } = parseDatetimeLocal(this.input.value);
            if (date) {
                this.viewYear = date.y;
                this.viewMonth = date.mo;
                this.selectedDate = date;
                this.timeInput.value = time || '09:00';
                this.updateTriggerLabel(
                    `${formatDisplayDate(date.y, date.mo, date.d)}${time ? ` · ${time}` : ''}`
                );
            } else {
                this.updateTriggerLabel('Select date & time', true);
                this.timeInput.value = '09:00';
            }
            this.renderCalendar();
            return;
        }
        const parsed = parseYmd(this.input.value);
        if (parsed) {
            this.viewYear = parsed.y;
            this.viewMonth = parsed.mo;
            this.updateTriggerLabel(formatDisplayDate(parsed.y, parsed.mo, parsed.d));
        } else {
            this.updateTriggerLabel('Select date', true);
        }
        this.renderCalendar();
    }

    selectDate(y, mo, d) {
        this.setNativeValue(formatYmd(y, mo, d));
        this.updateTriggerLabel(formatDisplayDate(y, mo, d));
        this.viewYear = y;
        this.viewMonth = mo;
        this.resetCalView();
        this.renderCalendar();
        this.wrapper.classList.remove('is-open');
    }

    applyDatetimeFromParts() {
        if (!this.selectedDate) return;
        const { y, mo, d } = this.selectedDate;
        const time = this.timeInput.value || '00:00';
        const value = `${formatYmd(y, mo, d)}T${time}`;
        this.setNativeValue(value);
        this.updateTriggerLabel(`${formatDisplayDate(y, mo, d)} · ${time}`);
        this.renderCalendar();
    }

    renderCalendar() {
        this.updateHeaderLabels();

        if (this.calView === 'months') {
            this.renderMonthsPanel();
            return;
        }
        if (this.calView === 'years') {
            this.renderYearsPanel();
            return;
        }

        this.renderDaysPanel();
    }

    updateHeaderLabels() {
        const sep = this.calHeader.querySelector('.th-picker-title-sep');
        if (this.calView === 'days') {
            this.titleMonthBtn.textContent = MONTH_LONG[this.viewMonth];
            this.titleYearBtn.textContent = String(this.viewYear);
            this.titleMonthBtn.style.display = '';
            this.titleYearBtn.style.display = '';
            sep.style.display = '';
            this.titleMonthBtn.disabled = false;
            this.titleYearBtn.disabled = false;
            this.navPrev.setAttribute('aria-label', 'Previous month');
            this.navNext.setAttribute('aria-label', 'Next month');
        } else if (this.calView === 'months') {
            this.titleMonthBtn.textContent = MONTH_LONG[this.viewMonth];
            this.titleYearBtn.textContent = String(this.viewYear);
            this.titleMonthBtn.style.display = 'none';
            this.titleYearBtn.style.display = '';
            sep.style.display = 'none';
            this.titleYearBtn.disabled = false;
            this.navPrev.setAttribute('aria-label', 'Previous year');
            this.navNext.setAttribute('aria-label', 'Next year');
        } else {
            const end = this.yearPageStart + 11;
            this.titleMonthBtn.textContent = `${this.yearPageStart} – ${end}`;
            this.titleYearBtn.textContent = '';
            this.titleMonthBtn.style.display = '';
            this.titleYearBtn.style.display = 'none';
            sep.style.display = 'none';
            this.titleMonthBtn.disabled = true;
            this.navPrev.setAttribute('aria-label', 'Previous years');
            this.navNext.setAttribute('aria-label', 'Next years');
        }
    }

    renderMonthsPanel() {
        const parsed = this.includeTime
            ? parseDatetimeLocal(this.input.value).date
            : parseYmd(this.input.value);
        this.inlineMonthGrid.querySelectorAll('.th-picker-month').forEach((btn) => {
            const mo = +btn.dataset.month;
            btn.classList.toggle(
                'is-selected',
                parsed && parsed.y === this.viewYear && parsed.mo === mo
            );
        });
    }

    renderYearsPanel() {
        const parsed = this.includeTime
            ? parseDatetimeLocal(this.input.value).date
            : parseYmd(this.input.value);
        const todayY = new Date().getFullYear();
        this.yearGrid.innerHTML = '';
        for (let y = this.yearPageStart; y < this.yearPageStart + 12; y++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'th-picker-year';
            btn.textContent = String(y);
            btn.dataset.year = String(y);
            if (parsed && parsed.y === y) {
                btn.classList.add('is-selected');
            }
            if (y === todayY) {
                btn.classList.add('is-current');
            }
            this.yearGrid.appendChild(btn);
        }
    }

    renderDaysPanel() {
        const { min, max } = this.getMinMax();
        const parsed = this.includeTime
            ? parseDatetimeLocal(this.input.value).date
            : parseYmd(this.input.value);
        const today = new Date();
        const todayY = today.getFullYear();
        const todayMo = today.getMonth();
        const todayD = today.getDate();

        this.daysGrid.innerHTML = '';
        const first = new Date(this.viewYear, this.viewMonth, 1);
        const startDay = first.getDay();
        const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
        const daysInPrev = new Date(this.viewYear, this.viewMonth, 0).getDate();

        const cells = [];
        for (let i = startDay - 1; i >= 0; i--) {
            const d = daysInPrev - i;
            const mo = this.viewMonth - 1;
            const y = mo < 0 ? this.viewYear - 1 : this.viewYear;
            const m = (mo + 12) % 12;
            cells.push({ y, mo: m, d, other: true });
        }
        for (let d = 1; d <= daysInMonth; d++) {
            cells.push({ y: this.viewYear, mo: this.viewMonth, d, other: false });
        }
        let nextD = 1;
        while (cells.length % 7 !== 0) {
            const mo = this.viewMonth + 1;
            const y = mo > 11 ? this.viewYear + 1 : this.viewYear;
            const m = mo % 12;
            cells.push({ y, mo: m, d: nextD++, other: true });
        }

        cells.forEach(({ y, mo, d, other }) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'th-picker-day';
            btn.textContent = String(d);
            btn.dataset.y = String(y);
            btn.dataset.mo = String(mo);
            btn.dataset.d = String(d);
            if (other) btn.classList.add('is-other-month');
            const date = { y, mo, d };
            if (this.isBefore(date, min) || this.isAfter(date, max)) {
                btn.disabled = true;
            }
            if (parsed && parsed.y === y && parsed.mo === mo && parsed.d === d) {
                btn.classList.add('is-selected');
            }
            if (y === todayY && mo === todayMo && d === todayD) {
                btn.classList.add('is-today');
            }
            this.daysGrid.appendChild(btn);
        });
    }
}

export function initThPickers(root = document) {
    root.querySelectorAll('input[type="month"]:not([data-th-picker-init])').forEach((input) => {
        if (input.disabled || input.readOnly || input.dataset.thPicker === 'skip') return;
        new ThMonthPicker(input);
    });

    root.querySelectorAll('input[type="date"]:not([data-th-picker-init])').forEach((input) => {
        if (input.disabled || input.readOnly || input.dataset.thPicker === 'skip') return;
        new ThDatePicker(input);
    });

    root.querySelectorAll('input[type="datetime-local"]:not([data-th-picker-init])').forEach((input) => {
        if (input.disabled || input.readOnly || input.dataset.thPicker === 'skip') return;
        new ThDatePicker(input, { includeTime: true });
    });
}

document.addEventListener('click', () => closeAllPickers());

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAllPickers();
});

document.addEventListener('DOMContentLoaded', () => {
    initThPickers();
    setTimeout(() => initThPickers(), 300);
});

let pickerObserveTimer;
const pickerObserver = new MutationObserver(() => {
    clearTimeout(pickerObserveTimer);
    pickerObserveTimer = setTimeout(() => initThPickers(), 150);
});
pickerObserver.observe(document.documentElement, { childList: true, subtree: true });
