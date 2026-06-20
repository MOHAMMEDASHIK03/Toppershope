<style>
    .payslip-doc {
        --ps-primary: #1e3a8a;
        --ps-text: #111827;
        --ps-muted: #6b7280;
        --ps-border: #d1d5db;
        --ps-soft: #f9fafb;
        font-family: 'DejaVu Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--ps-text);
        font-size: 8.5pt;
        line-height: 1.3;
        background: #fff;
    }

    .payslip-doc * { box-sizing: border-box; }

    .payslip-doc__sheet {
        width: 100%;
        max-width: 190mm;
        margin: 0 auto;
        padding: 0;
        background: #fff;
        page-break-inside: avoid;
        break-inside: avoid-page;
    }

    .payslip-doc__header {
        display: table;
        width: 100%;
        border-bottom: 2px solid var(--ps-primary);
        padding-bottom: 6px;
        margin-bottom: 6px;
    }

    .payslip-doc__header-left,
    .payslip-doc__header-right {
        display: table-cell;
        vertical-align: top;
    }

    .payslip-doc__header-right {
        text-align: right;
        width: 36%;
    }

    .payslip-doc__logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        display: block;
        margin-bottom: 4px;
        border-radius: 6px;
    }

    .payslip-doc__company-name {
        font-size: 13pt;
        font-weight: 800;
        color: var(--ps-primary);
        margin: 0 0 1px;
        line-height: 1.15;
    }

    .payslip-doc__company-tag {
        font-size: 8pt;
        color: var(--ps-muted);
        margin: 0 0 3px;
    }

    .payslip-doc__company-meta {
        font-size: 7pt;
        color: var(--ps-muted);
        line-height: 1.35;
    }

    .payslip-doc__title {
        font-size: 14pt;
        font-weight: 800;
        color: var(--ps-primary);
        letter-spacing: 0.1em;
        margin: 0 0 2px;
        line-height: 1.1;
    }

    .payslip-doc__period {
        font-size: 10pt;
        font-weight: 700;
        color: var(--ps-text);
        margin: 0 0 4px;
    }

    .payslip-doc__meta-line {
        font-size: 7pt;
        color: var(--ps-muted);
        margin: 1px 0;
    }

    .payslip-doc__badge {
        display: inline-block;
        margin-top: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 7pt;
        font-weight: 800;
        letter-spacing: 0.05em;
    }

    .payslip-doc__badge--paid {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .payslip-doc__badge--pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .payslip-doc__section-title {
        font-size: 7.5pt;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ps-primary);
        margin: 0 0 4px;
        padding-bottom: 2px;
        border-bottom: 1px solid var(--ps-border);
    }

    .payslip-doc__employee-grid {
        display: table;
        width: 100%;
        border: 1px solid var(--ps-border);
        margin-bottom: 6px;
        background: var(--ps-soft);
        page-break-inside: avoid;
    }

    .payslip-doc__employee-row {
        display: table-row;
    }

    .payslip-doc__employee-cell {
        display: table-cell;
        width: 25%;
        padding: 4px 6px;
        border-bottom: 1px solid var(--ps-border);
        border-right: 1px solid var(--ps-border);
        vertical-align: top;
    }

    .payslip-doc__employee-cell:last-child {
        border-right: none;
    }

    .payslip-doc__employee-row:last-child .payslip-doc__employee-cell {
        border-bottom: none;
    }

    .payslip-doc__label {
        font-size: 6.5pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--ps-muted);
        margin-bottom: 1px;
    }

    .payslip-doc__value {
        font-size: 8pt;
        font-weight: 600;
        color: var(--ps-text);
        line-height: 1.25;
    }

    .payslip-doc__tables {
        display: table;
        width: 100%;
        border-collapse: separate;
        border-spacing: 5px 0;
        margin-bottom: 6px;
        page-break-inside: avoid;
    }

    .payslip-doc__table-col {
        display: table-cell;
        width: 50%;
        vertical-align: top;
    }

    .payslip-doc__table {
        width: 100%;
        border: 1px solid var(--ps-border);
        border-collapse: collapse;
    }

    .payslip-doc__table thead th {
        background: var(--ps-primary);
        color: #fff;
        font-size: 7.5pt;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 6px;
        text-align: left;
    }

    .payslip-doc__table thead th:last-child {
        text-align: right;
    }

    .payslip-doc__table tbody td {
        padding: 3px 6px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 7.5pt;
    }

    .payslip-doc__table tbody td:last-child {
        text-align: right;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }

    .payslip-doc__table tfoot td {
        padding: 4px 6px;
        font-weight: 800;
        font-size: 7.5pt;
        background: var(--ps-soft);
        border-top: 1px solid var(--ps-border);
    }

    .payslip-doc__table tfoot td:last-child {
        text-align: right;
        color: var(--ps-primary);
    }

    .payslip-doc__table--deductions thead th {
        background: #991b1b;
    }

    .payslip-doc__bottom {
        page-break-inside: avoid;
        break-inside: avoid-page;
    }

    .payslip-doc__net-bar {
        display: table;
        width: 100%;
        background: var(--ps-primary);
        color: #fff;
        margin-bottom: 5px;
    }

    .payslip-doc__net-label,
    .payslip-doc__net-value {
        display: table-cell;
        padding: 6px 10px;
        vertical-align: middle;
    }

    .payslip-doc__net-label {
        font-size: 9pt;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .payslip-doc__net-value {
        text-align: right;
        font-size: 14pt;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        width: 45%;
    }

    .payslip-doc__words {
        padding: 5px 8px;
        background: var(--ps-soft);
        border: 1px solid var(--ps-border);
        margin-bottom: 5px;
        font-size: 7.5pt;
        line-height: 1.35;
    }

    .payslip-doc__words strong {
        color: var(--ps-primary);
    }

    .payslip-doc__payment {
        display: table;
        width: 100%;
        border: 1px solid var(--ps-border);
        margin-bottom: 5px;
    }

    .payslip-doc__payment-cell {
        display: table-cell;
        width: 25%;
        padding: 4px 6px;
        border-right: 1px solid var(--ps-border);
        vertical-align: top;
    }

    .payslip-doc__payment-cell:last-child {
        border-right: none;
    }

    .payslip-doc__footer {
        border-top: 1px solid var(--ps-border);
        padding-top: 5px;
        text-align: center;
        font-size: 6.5pt;
        color: var(--ps-muted);
        line-height: 1.35;
    }

    .payslip-doc__footer p { margin: 2px 0; }

    /* PDF export (DomPDF) */
    body.payslip-pdf-body {
        margin: 0;
        padding: 0;
        background: #fff;
    }

    body.payslip-pdf-body .payslip-doc__sheet {
        max-width: 100%;
        padding: 4mm 2mm;
    }

    @media screen {
        .payslip-screen-wrap .payslip-doc__sheet {
            padding: 10mm 8mm;
            border: 1px solid var(--ps-border);
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08);
            border-radius: 4px;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 6mm;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            height: auto !important;
            overflow: visible !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Hide all page chrome — only the payslip document should print */
        body * {
            visibility: hidden;
        }

        .payslip-doc,
        .payslip-doc * {
            visibility: visible;
        }

        .payslip-doc {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        aside,
        #sidebar,
        #sidebar-backdrop,
        .panel-header,
        header.panel-header,
        [data-theme-toggle],
        #th-confirm-modal,
        #th-toast-root,
        .no-print {
            display: none !important;
            visibility: hidden !important;
        }

        body,
        body > div,
        main,
        .payslip-print-root,
        .payslip-screen-wrap {
            display: block !important;
            width: 100% !important;
            min-height: 0 !important;
            height: auto !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #fff !important;
            max-width: none !important;
        }

        .payslip-doc__sheet {
            border: none;
            box-shadow: none;
            max-width: 100%;
            padding: 0;
            margin: 0;
            page-break-inside: avoid;
            break-inside: avoid-page;
        }

        .payslip-doc__badge--paid,
        .payslip-doc__table thead th,
        .payslip-doc__net-bar {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
