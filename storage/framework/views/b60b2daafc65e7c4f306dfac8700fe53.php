<style>
    body { font-family: 'Inter', sans-serif; }
    .panel-sidebar {
        width: 240px;
        transition: width 0.2s ease;
    }
    .panel-sidebar.is-collapsed {
        width: 72px;
    }
    .panel-sidebar.is-collapsed .sidebar-brand-text,
    .panel-sidebar.is-collapsed .sidebar-section-label,
    .panel-sidebar.is-collapsed .sidebar-link-text,
    .panel-sidebar.is-collapsed .sidebar-link-external,
    .panel-sidebar.is-collapsed .sidebar-footer-text {
        display: none;
    }
    .panel-sidebar.is-collapsed .sidebar-brand-row {
        justify-content: center;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    .panel-sidebar.is-collapsed .sidebar-collapse-btn { margin-left: 0; }
    .panel-sidebar.is-collapsed .sidebar-link {
        justify-content: center;
        padding: 10px;
    }
    .panel-sidebar.is-collapsed .sidebar-link i.ph { margin: 0; }
    .panel-sidebar.is-collapsed .sidebar-footer-user { justify-content: center; }
    .panel-sidebar.is-collapsed .sidebar-signout .sidebar-link-text { display: none; }
    .sidebar-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 8px;
        font-size: 0.875rem; font-weight: 500; color: #64748b;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
        scroll-margin-top: 8px;
        scroll-margin-bottom: 8px;
    }
    .sidebar-link:hover { background: #f1f5f9; color: #0f172a; }
    .sidebar-link.active {
        background: #fff7ed; color: #ea580c; font-weight: 600;
        box-shadow: inset 3px 0 0 0 #f97316;
    }
    .panel-sidebar.is-collapsed .sidebar-link.active {
        box-shadow: inset 0 0 0 2px #f97316;
    }
    .sidebar-link i.ph { width: 18px; height: 18px; flex-shrink: 0; font-size: 18px; }
    .sidebar-collapse-btn {
        display: flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 6px;
        color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0;
        flex-shrink: 0; transition: all 0.15s;
    }
    .sidebar-collapse-btn:hover {
        background: #f1f5f9; color: #334155; border-color: #cbd5e1;
    }
    .admin-table thead th {
        font-size: 0.6875rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: #64748b; background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
    }
    .admin-table tbody td {
        padding: 0.875rem 1rem; color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .admin-table tbody tr:hover { background: #f8fafc; }
    .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600;
        color: #fff; background: #f97316; border-radius: 0.5rem;
        transition: background 0.15s; border: none; cursor: pointer; text-decoration: none;
        box-shadow: 0 1px 2px rgb(249 115 22 / 0.2);
    }
    .btn-primary:hover { background: #ea580c; color: #fff; }
    .btn-danger {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 700;
        color: #fff; background: #dc2626; border-radius: 0.75rem;
        transition: background 0.15s; border: none; cursor: pointer; text-decoration: none;
        box-shadow: 0 1px 2px rgb(220 38 38 / 0.25);
    }
    .btn-danger:hover { background: #b91c1c; color: #fff; }
    .btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600;
        color: #475569; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem;
        transition: all 0.15s; text-decoration: none;
    }
    .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; color: #475569; }
    .badge-active {
        display: inline-flex; align-items: center; gap: 0.25rem;
        padding: 0.125rem 0.5rem; font-size: 0.625rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em;
        color: #c2410c; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 9999px;
    }
    .badge-inactive {
        display: inline-flex; align-items: center;
        padding: 0.125rem 0.5rem; font-size: 0.625rem; font-weight: 700;
        text-transform: uppercase; color: #64748b;
        background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 9999px;
    }
    .admin-toggle {
        position: relative;
        width: 2.75rem;
        height: 1.5rem;
        flex-shrink: 0;
        border-radius: 9999px;
        background: #cbd5e1;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .admin-toggle:hover { background: #94a3b8; }
    .admin-toggle.is-on {
        background: #f97316;
    }
    .admin-toggle.is-on:hover { background: #ea580c; }
    .admin-toggle:focus { outline: none; }
    .admin-toggle:focus-visible {
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px rgb(249 115 22 / 0.35);
    }
    .admin-toggle__thumb {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        background: #fff;
        box-shadow: 0 1px 2px rgb(15 23 42 / 0.12);
        transition: transform 0.2s ease;
        pointer-events: none;
    }
    .admin-toggle.is-on .admin-toggle__thumb {
        transform: translateX(1.25rem);
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .admin-panel, .glass-panel {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.04);
    }
    .admin-input {
        width: 100%; padding: 0.625rem 1rem; font-size: 0.875rem;
        color: #0f172a; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem;
        outline: none; transition: border-color 0.15s, box-shadow 0.15s;
    }
    .admin-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgb(249 115 22 / 0.15); }
    .admin-input::placeholder { color: #94a3b8; }
    .admin-tab-active { border-color: #f97316; color: #ea580c; background: #fff7ed; }
    .admin-tab { border-color: transparent; color: #64748b; }
    .admin-tab:hover { color: #334155; border-color: #cbd5e1; }
    .filter-chip {
        display: inline-flex; align-items: center;
        padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600;
        color: #475569; background: #fff; border: 1px solid #e2e8f0;
        border-radius: 0.75rem; text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .filter-chip:hover { border-color: #fdba74; color: #ea580c; background: #fff7ed; }
    .filter-chip.active {
        color: #fff; background: #f97316; border-color: #f97316;
        box-shadow: 0 1px 3px rgb(249 115 22 / 0.25);
    }
    .filter-chip-sm {
        padding: 0.375rem 0.75rem; font-size: 0.75rem; border-radius: 0.5rem;
    }
    [x-cloak] { display: none !important; }
    .panel-pagination {
        position: relative;
        z-index: 5;
    }
    .panel-pagination-per-page-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        min-width: 5.5rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .panel-pagination-per-page-trigger:hover {
        border-color: #fdba74;
        background: #fff7ed;
    }
    .panel-pagination-per-page-trigger[aria-expanded="true"] {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgb(249 115 22 / 0.15);
        color: #ea580c;
    }
    .panel-pagination-per-page-menu {
        padding: 0.25rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.625rem;
        box-shadow: 0 10px 25px rgb(15 23 42 / 0.12);
    }
    .panel-pagination-per-page-option {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: left;
        text-decoration: none;
        color: #334155;
        background: transparent;
        border: none;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: background 0.12s, color 0.12s;
    }
    .panel-pagination-per-page-option:hover {
        background: #fff7ed;
        color: #ea580c;
    }
    .panel-pagination-per-page-option.is-active {
        background: #ffedd5;
        color: #c2410c;
    }

    /* Panel tables — fit width, no horizontal scrollbar */
    .panel-table-wrap {
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }
    .panel-table,
    .panel-table-wrap > table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    .panel-table th,
    .panel-table td,
    .panel-table-wrap > table th,
    .panel-table-wrap > table td {
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        vertical-align: middle;
    }

    /* Panel grid list (stacked on mobile, columns on desktop — no table scroll) */
    .panel-list__head {
        display: none;
        gap: 0.75rem 1rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    @media (min-width: 1024px) {
        .panel-list__head { display: grid; }
    }
    .panel-list__row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.65rem 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        transition: background 0.15s;
    }
    .panel-list__row:last-child { border-bottom: none; }
    .panel-list__row:hover { background: #fafbfc; }
    .panel-list__cell-label {
        display: block;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.2rem;
    }
    @media (min-width: 1024px) {
        .panel-list__cell-label { display: none; }
    }
    .panel-list--leaves .panel-list__head,
    .panel-list--leaves .panel-list__row {
        grid-template-columns: minmax(0, 1.35fr) minmax(0, 0.7fr) minmax(0, 1fr) minmax(0, 5.5rem) minmax(0, 0.8fr) minmax(0, 5.25rem);
    }
    .panel-list--employees .panel-list__head,
    .panel-list--employees .panel-list__row {
        grid-template-columns: minmax(0, 1.45fr) minmax(0, 1fr) minmax(0, 7rem) minmax(0, 5.5rem) minmax(0, 5.5rem);
    }
    .panel-emp-name {
        font-weight: 800;
        font-size: 0.875rem;
        color: #0f172a;
        line-height: 1.3;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .panel-emp-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem 0.5rem;
        margin-top: 0.25rem;
    }
    .panel-emp-id {
        font-family: ui-monospace, monospace;
        font-size: 0.625rem;
        font-weight: 800;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        white-space: nowrap;
    }
    .panel-list--master-5 .panel-list__head,
    .panel-list--master-5 .panel-list__row {
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.35fr) minmax(0, 6.5rem) minmax(0, 5.25rem) minmax(0, 5.5rem);
    }
    .panel-list--master-4 .panel-list__head,
    .panel-list--master-4 .panel-list__row {
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 1.2fr) minmax(0, 5.5rem) minmax(0, 5.5rem);
    }
    .panel-list--kpis .panel-list__head,
    .panel-list--kpis .panel-list__row {
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1.15fr) minmax(0, 0.9fr) minmax(0, 0.9fr) minmax(0, 5.75rem);
    }
    .panel-list--performance-reviews .panel-list__head,
    .panel-list--performance-reviews .panel-list__row {
        grid-template-columns: minmax(0, 1.35fr) minmax(0, 5rem) minmax(0, 1.05fr) minmax(0, 6.5rem) minmax(0, 7.25rem) minmax(0, 6.75rem);
    }
    .panel-list--announcements .panel-list__head,
    .panel-list--announcements .panel-list__row {
        grid-template-columns: minmax(0, 0.95fr) minmax(0, 7.5rem) minmax(0, 1.4fr) minmax(0, 5.25rem) minmax(0, 5.25rem) minmax(0, 6rem);
    }
    .panel-list--payroll .panel-list__head,
    .panel-list--payroll .panel-list__row {
        column-gap: 1.15rem;
        grid-template-columns:
            minmax(0, 1.35fr)
            minmax(5.25rem, 0.68fr)
            minmax(5.25rem, 0.68fr)
            minmax(5.25rem, 0.68fr)
            minmax(6.5rem, 0.82fr)
            minmax(5.5rem, 6.75rem)
            minmax(7.5rem, 8rem);
    }
    @media (min-width: 1280px) {
        .panel-list--payroll .panel-list__head,
        .panel-list--payroll .panel-list__row {
            column-gap: 1.35rem;
            grid-template-columns:
                minmax(0, 1.4fr)
                minmax(5.75rem, 0.72fr)
                minmax(5.75rem, 0.72fr)
                minmax(5.75rem, 0.72fr)
                minmax(7rem, 0.88fr)
                minmax(5.75rem, 7rem)
                minmax(8rem, 8.5rem);
        }
    }
    .panel-list--payroll .panel-list__cell--net-pay {
        padding-right: 0.65rem;
    }
    .panel-list--payroll .panel-list__cell--status {
        padding-left: 0.5rem;
    }
    .panel-list--payroll .panel-list__head > span:nth-child(5) {
        padding-right: 0.65rem;
    }
    .panel-list--payroll .panel-list__head > span:nth-child(6) {
        padding-left: 0.5rem;
    }
    .panel-list-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.375rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
        border-radius: 0.5rem;
        border: 1px solid #fed7aa;
        background: #fff;
        color: #ea580c;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
        width: 100%;
        max-width: 100%;
    }
    @media (min-width: 1024px) {
        .panel-list-action-btn { width: auto; }
    }
    .panel-list-action-btn:hover {
        background: #fff7ed;
        border-color: #fdba74;
        color: #c2410c;
    }
    .panel-list-action-btn svg {
        flex-shrink: 0;
    }
    .panel-list-action-btn span {
        white-space: nowrap;
    }
    .panel-list__cell--money {
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #475569;
    }
    .panel-list__head .panel-list__cell--money {
        text-align: right;
    }
    .panel-list__cell--money.is-positive { color: #059669; }
    .panel-list__cell--money.is-negative { color: #dc2626; }
    .panel-list__cell--money.is-net {
        font-size: 0.875rem;
        font-weight: 800;
        color: #0f172a;
    }
    .panel-list__cell--clip {
        min-width: 0;
        overflow: hidden;
    }
    .panel-list__cell--clip .panel-list-row-title,
    .panel-list__cell--clip .panel-list-row-muted {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }
    .panel-list-employee-block {
        min-width: 0;
    }
    .panel-list-employee-block .panel-emp-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: normal;
        overflow-wrap: normal;
    }
    .panel-list-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        max-width: 100%;
        white-space: nowrap;
        padding: 0.2rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .panel-list__head > span.text-right,
    .panel-list__row > .panel-list__cell--end {
        justify-self: end;
        text-align: right;
    }
    .panel-list-rating {
        display: inline-flex;
        align-items: center;
        gap: 0.125rem;
        white-space: nowrap;
        flex-wrap: nowrap;
    }
    .panel-list-row-title {
        font-weight: 800;
        font-size: 0.875rem;
        color: #0f172a;
        line-height: 1.35;
        word-break: break-word;
    }
    .panel-list-row-muted {
        font-size: 0.8125rem;
        color: #64748b;
        line-height: 1.45;
        word-break: break-word;
        margin-top: 0.2rem;
    }
    .panel-list-avatar {
        width: 2.25rem;
        height: 2.25rem;
        flex-shrink: 0;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.75rem;
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ffedd5;
    }

    /* Card-style radio options (input overlays card — prevents scroll jump from sr-only) */
    .panel-radio-card {
        position: relative;
        display: block;
        cursor: pointer;
    }
    .panel-radio-card__input {
        position: absolute;
        inset: 0;
        z-index: 2;
        width: 100%;
        height: 100%;
        margin: 0;
        opacity: 0;
        cursor: pointer;
        scroll-margin: 0;
    }
    main {
        overflow-anchor: none;
    }
    .panel-radio-card__face {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        transition: border-color 0.15s, background 0.15s, color 0.15s, box-shadow 0.15s;
        pointer-events: none;
    }
    .panel-radio-card__input:focus-visible + .panel-radio-card__face {
        box-shadow: 0 0 0 3px rgb(249 115 22 / 0.2);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--open {
        border-color: #34d399;
        background: #ecfdf5;
        color: #047857;
        box-shadow: 0 0 0 3px rgb(16 185 129 / 0.15);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--draft {
        border-color: #fbbf24;
        background: #fffbeb;
        color: #b45309;
        box-shadow: 0 0 0 3px rgb(245 158 11 / 0.15);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--closed {
        border-color: #94a3b8;
        background: #f1f5f9;
        color: #334155;
        box-shadow: 0 0 0 3px rgb(148 163 184 / 0.2);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--approved {
        border-color: #34d399;
        background: #ecfdf5;
        color: #047857;
        box-shadow: 0 0 0 3px rgb(16 185 129 / 0.15);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--pending {
        border-color: #fbbf24;
        background: #fffbeb;
        color: #b45309;
        box-shadow: 0 0 0 3px rgb(245 158 11 / 0.15);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face--rejected {
        border-color: #fb7185;
        background: #fff1f2;
        color: #be123c;
        box-shadow: 0 0 0 3px rgb(244 63 94 / 0.15);
    }
    .panel-radio-card__input:checked + .panel-radio-card__face:not([class*="panel-radio-card__face--"]) {
        border-color: #fb923c;
        background: #fff7ed;
        color: #ea580c;
        box-shadow: 0 0 0 3px rgb(249 115 22 / 0.15);
    }
    .panel-radio-card:hover .panel-radio-card__face {
        border-color: #cbd5e1;
    }

    /* Themed confirm dialog (th-confirm.js) */
    .th-confirm-modal {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .th-confirm-modal.hidden { display: none; }
    .th-confirm-backdrop {
        position: absolute;
        inset: 0;
        background: rgb(15 23 42 / 0.45);
        backdrop-filter: blur(2px);
    }
    .th-confirm-card {
        position: relative;
        width: 100%;
        max-width: 22rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgb(15 23 42 / 0.12);
        padding: 1.5rem;
        text-align: center;
    }
    .th-confirm-icon {
        width: 3rem;
        height: 3rem;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: #fff1f2;
        color: #e11d48;
    }
    .th-confirm-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.5rem;
    }
    .th-confirm-message {
        font-size: 0.875rem;
        line-height: 1.5;
        color: #64748b;
        margin: 0 0 1.25rem;
    }
    .th-confirm-actions {
        display: flex;
        gap: 0.625rem;
        justify-content: center;
    }
    .th-confirm-btn {
        flex: 1;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 700;
        border-radius: 0.75rem;
        border: none;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .th-confirm-btn--cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .th-confirm-btn--cancel:hover { background: #e2e8f0; color: #334155; }
    .th-confirm-btn--danger {
        background: #dc2626;
        color: #fff;
        box-shadow: 0 1px 2px rgb(220 38 38 / 0.25);
    }
    .th-confirm-btn--danger:hover { background: #b91c1c; }
    .th-confirm-btn--primary {
        background: #f97316;
        color: #fff;
        box-shadow: 0 1px 2px rgb(249 115 22 / 0.2);
    }
    .th-confirm-btn--primary:hover { background: #ea580c; }
</style>
<?php /**PATH D:\Lama Projects\Toppershope\laravel_core\resources\views/components/panel/styles.blade.php ENDPATH**/ ?>