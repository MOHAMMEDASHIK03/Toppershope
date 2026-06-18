<style>
    :root {
        --fp-primary:      #7723D6;
        --fp-primary-dk:   #6B21C8;
        --fp-blue:         #7723D6;
        --fp-indigo:       #6B21C8;
        --fp-green:        #16a34a;
        --fp-red:          #dc2626;
        --fp-amber:        #d97706;
        --fp-purple:       #7723D6;
        --fp-radius:       1rem;
        --fp-radius-sm:    .625rem;
        --fp-radius-lg:    1.25rem;
    }

    .bg-primary               { background-color: var(--fp-primary) !important; }
    .text-primary             { color:            var(--fp-primary) !important; }
    .border-primary           { border-color:     var(--fp-primary) !important; }
    .bg-primary\/10           { background-color: rgba(119,35,214,0.10) !important; }
    .bg-primary\/20           { background-color: rgba(119,35,214,0.20) !important; }
    .shadow-primary\/20       { --tw-shadow-color: rgba(119,35,214,0.20); }
    .from-primary             { --tw-gradient-from: var(--fp-primary); }
    .to-primary-500            { --tw-gradient-to:   #7723D6; }
    .hover\:bg-primary\/90:hover { background-color: rgba(119,35,214,0.9) !important; }
    .focus\:ring-primary\/30:focus { --tw-ring-color: rgba(119,35,214,0.3); }
    .focus\:border-primary:focus   { border-color: var(--fp-primary) !important; }
    .accent-primary { accent-color: var(--fp-primary); }

    .fp-flash-success { background:#f0fdf4; border:1.5px solid #bbf7d0; color:#15803d; border-radius:var(--fp-radius); padding:1rem 1.25rem; display:flex; align-items:center; gap:.75rem; font-weight:600; margin-bottom:1rem; }
    .fp-flash-error   { background:#fef2f2; border:1.5px solid #fecaca; color:#dc2626; border-radius:var(--fp-radius); padding:1rem 1.25rem; display:flex; align-items:center; gap:.75rem; font-weight:600; margin-bottom:1rem; }

    .fp-banner-google { background:linear-gradient(135deg,#7723D6,#6B21C8); color:#fff; border-radius:var(--fp-radius); padding:1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; box-shadow:0 4px 24px rgba(119,35,214,.25); }
    .fp-banner-google .fp-banner-icon { width:3.5rem; height:3.5rem; background:rgba(255,255,255,.2); border-radius:var(--fp-radius); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.75rem; }
    .fp-banner-google h4 { margin:0 0 4px; font-size:1.05rem; font-weight:800; color:#fff; }
    .fp-banner-google p  { margin:0; font-size:.85rem; color:rgba(255,255,255,.88); }
    .fp-banner-btn { flex-shrink:0; background:#fff; color:#7723D6; font-weight:700; font-size:.875rem; padding:.65rem 1.25rem; border-radius:.75rem; text-decoration:none; white-space:nowrap; display:inline-block; }
    .fp-banner-btn:hover { background:#F5F0FF; color:#1e40af; }

    .fp-banner-success { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:var(--fp-radius); padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
    .fp-linked-icon { width:2.25rem; height:2.25rem; background:#22c55e; border-radius:.625rem; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; flex-shrink:0; }

    .fp-stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .fp-stat-card { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,.04); transition:box-shadow .2s,transform .2s; }
    .fp-stat-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); transform:translateY(-1px); }
    .fp-stat-icon { width:3rem; height:3rem; border-radius:.875rem; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; }
    .fp-stat-icon.purple { background:#F5F0FF; color:#6B21C8; }
    .fp-stat-icon.purple2   { background:#F5F0FF; color:#7723D6; }
    .fp-stat-icon.green  { background:#f0fdf4; color:#16a34a; }
    .fp-stat-icon.purple { background:#faf5ff; color:#7723D6; }
    .fp-stat-icon.amber  { background:#fffbeb; color:#d97706; }
    .fp-stat-icon.red    { background:#fef2f2; color:#dc2626; }
    .fp-stat-val  { font-size:1.75rem; font-weight:800; color:#0f172a; line-height:1; }
    .fp-stat-lbl  { font-size:.8rem; color:#64748b; margin-top:2px; font-weight:600; }

    .fp-page-header    { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; }
    .fp-page-title     { font-size:1.5rem; font-weight:900; color:#0f172a; margin:0; }
    .fp-page-subtitle  { font-size:.825rem; color:#64748b; margin-top:2px; }

    .fp-btn-primary { display:inline-flex; align-items:center; gap:.5rem; background:var(--fp-primary); color:#fff; font-weight:700; font-size:.875rem; padding:.65rem 1.25rem; border-radius:.75rem; border:none; cursor:pointer; text-decoration:none; transition:background .15s,box-shadow .15s,transform .15s; box-shadow:0 2px 8px rgba(119,35,214,.3); }
    .fp-btn-primary:hover { background:var(--fp-primary-dk); box-shadow:0 4px 16px rgba(119,35,214,.35); transform:translateY(-1px); color:#fff; }
    .fp-btn-primary:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    .fp-btn-purple { display:inline-flex; align-items:center; gap:.5rem; background:linear-gradient(135deg,#7723D6,#6B21C8); color:#fff; font-weight:700; font-size:.875rem; padding:.65rem 1.25rem; border-radius:.75rem; border:none; cursor:pointer; text-decoration:none; transition:opacity .15s,transform .15s; box-shadow:0 2px 8px rgba(119,35,214,.25); }
    .fp-btn-purple:hover { opacity:.9; transform:translateY(-1px); color:#fff; }
    .fp-btn-purple:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    .fp-btn-ghost { display:inline-flex; align-items:center; gap:.5rem; background:#fff; color:#334155; font-weight:700; font-size:.875rem; padding:.65rem 1.25rem; border-radius:.75rem; border:1.5px solid #e2e8f0; cursor:pointer; text-decoration:none; transition:border-color .15s,background .15s; }
    .fp-btn-ghost:hover { border-color:#94a3b8; background:#f8fafc; color:#334155; }

    .fp-btn-danger { display:inline-flex; align-items:center; gap:.4rem; background:#fef2f2; color:#dc2626; font-weight:700; font-size:.875rem; padding:.4rem .75rem; border-radius:.625rem; border:1.5px solid #fecaca; cursor:pointer; text-decoration:none; transition:background .15s; }
    .fp-btn-danger:hover { background:#fee2e2; color:#dc2626; }
    .btn-danger, .fp-btn-danger-solid {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 700;
        color: #fff; background: #dc2626; border-radius: 0.75rem;
        border: none; cursor: pointer; text-decoration: none;
        box-shadow: 0 1px 2px rgb(220 38 38 / 0.25);
        transition: background 0.15s;
    }
    .btn-danger:hover, .fp-btn-danger-solid:hover { background: #b91c1c; color: #fff; }

    .fp-card       { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; transition:box-shadow .2s; }
    .fp-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08); }
    .fp-card-body  { padding:1.5rem; }

    .fp-meeting-card       { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; transition:box-shadow .2s; }
    .fp-meeting-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.1); }
    .fp-meeting-card-bar   { height:6px; background:linear-gradient(90deg,#7723D6,#9F7AEA); }
    .fp-meeting-card-body  { padding:1.25rem; }

    .fp-badge           { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:700; padding:2px 8px; border-radius:9999px; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; }
    .fp-badge.active    { background:#dcfce7; color:#15803d; }
    .fp-badge.upcoming  { background:#fefce8; color:#a16207; }
    .fp-badge.draft     { background:#fef3c7; color:#b45309; }
    .fp-badge.published { background:#dcfce7; color:#15803d; }
    .fp-badge.closed    { background:#fee2e2; color:#dc2626; }
    .fp-badge.cancelled { background:#fee2e2; color:#dc2626; }
    .fp-badge.batch     { background:#E9D8FD; color:#7723D6; }
    .fp-badge.one-on-one { background:#F5F0FF; color:#7723D6; }
    .fp-badge.online    { background:#f0fdf4; color:#16a34a; }
    .fp-badge.offline   { background:#fffbeb; color:#d97706; }

    .fp-table-wrap { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .fp-table { width:100%; border-collapse:collapse; font-size:.875rem; }
    .fp-table thead th { background:#f8fafc; padding:.75rem 1.25rem; text-align:left; font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#64748b; border-bottom:1.5px solid #e2e8f0; }
    .fp-table tbody td { padding:1rem 1.25rem; color:#334155; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .fp-table tbody tr:last-child td { border-bottom:none; }
    .fp-table tbody tr:hover { background:#f8fafc; }

    .fp-filter-bar    { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); padding:1rem 1.25rem; display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .fp-filter-label  { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#64748b; white-space:nowrap; }
    .fp-filter-select,.fp-filter-input { border:1.5px solid #e2e8f0; background:#f8fafc; border-radius:var(--fp-radius-sm); padding:.45rem .875rem; font-size:.875rem; color:#1e293b; outline:none; transition:border-color .15s; min-width:155px; }
    .fp-filter-select:focus,.fp-filter-input:focus { border-color:var(--fp-primary); background:#fff; }

    .fp-modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.55); backdrop-filter:blur(4px); z-index:50; display:flex; align-items:center; justify-content:center; padding:1rem; }
    .fp-modal         { background:#fff; border-radius:1.5rem; box-shadow:0 25px 60px rgba(0,0,0,.2); width:100%; max-width:560px; max-height:90vh; overflow-y:auto; }
    .fp-modal-header  { display:flex; align-items:center; justify-content:space-between; padding:1.5rem; border-bottom:1.5px solid #f1f5f9; }
    .fp-modal-title   { font-size:1.2rem; font-weight:900; color:#0f172a; margin:0; }
    .fp-modal-subtitle { font-size:.8rem; color:#64748b; margin-top:2px; }
    .fp-modal-close   { width:2.25rem; height:2.25rem; border-radius:.625rem; background:#f1f5f9; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1rem; color:#64748b; transition:background .15s; }
    .fp-modal-close:hover { background:#e2e8f0; }
    .fp-modal-body    { padding:1.5rem; }

    .fp-label    { display:block; font-size:.825rem; font-weight:700; color:#374151; margin-bottom:.375rem; }
    .fp-input,.fp-select,.fp-textarea { width:100%; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:.75rem; padding:.6rem .875rem; font-size:.875rem; color:#1e293b; outline:none; transition:border-color .15s,background .15s; font-family:inherit; box-sizing:border-box; }
    .fp-input:focus,.fp-select:focus,.fp-textarea:focus { border-color:var(--fp-primary); background:#fff; box-shadow:0 0 0 3px rgba(119,35,214,.12); }
    .fp-textarea { resize:vertical; min-height:80px; }
    .fp-type-option { display:flex; align-items:center; gap:.75rem; border:1.5px solid #e2e8f0; border-radius:.875rem; padding:.875rem; cursor:pointer; transition:border-color .15s,background .15s; }
    .fp-type-option.selected-primary { border-color:var(--fp-primary); background:#F5F0FF; }
    .fp-type-option.selected-purple    { border-color:#7723D6; background:#F5F0FF; }

    .fp-empty       { background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--fp-radius-lg); padding:3.5rem 2rem; text-align:center; }
    .fp-empty-icon  { width:3.5rem; height:3.5rem; background:#f1f5f9; border-radius:1rem; display:flex; align-items:center; justify-content:center; font-size:1.75rem; color:#94a3b8; margin:0 auto 1rem; }
    .fp-empty h3    { font-size:1.1rem; font-weight:800; color:#1e293b; margin:0 0 6px; }
    .fp-empty p     { font-size:.875rem; color:#64748b; margin:0 auto; max-width:320px; }

    .fp-fill-bar-wrap { background:#e2e8f0; border-radius:9999px; height:6px; overflow:hidden; }
    .fp-fill-bar      { height:100%; border-radius:9999px; transition:width .4s; }
    .fp-fill-bar.low  { background:#22c55e; }
    .fp-fill-bar.mid  { background:#f59e0b; }
    .fp-fill-bar.high { background:#ef4444; }
</style>
