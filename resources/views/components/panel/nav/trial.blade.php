<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Trial</p>
<a href="{{ route('trial.dashboard') }}" class="sidebar-link {{ request()->routeIs('trial.dashboard') ? 'active' : '' }}" title="My trial">
    <i class="ph ph-play-circle"></i>
    <span class="sidebar-link-text truncate">My Trial Preview</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
