<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Overview</p>
<a href="{{ route('ads.dashboard') }}" class="sidebar-link {{ request()->routeIs('ads.dashboard') ? 'active' : '' }}" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Campaigns</p>
<a href="{{ route('ads.campaigns.index') }}" class="sidebar-link {{ request()->routeIs('ads.campaigns.index') || request()->routeIs('ads.campaigns.edit') ? 'active' : '' }}" title="All campaigns">
    <i class="ph ph-megaphone"></i>
    <span class="sidebar-link-text truncate">All Campaigns</span>
</a>
<a href="{{ route('ads.campaigns.create') }}" class="sidebar-link {{ request()->routeIs('ads.campaigns.create') ? 'active' : '' }}" title="New campaign">
    <i class="ph ph-plus-circle"></i>
    <span class="sidebar-link-text truncate">New Campaign</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Marketing</p>
<a href="{{ route('ads.popups.index') }}" class="sidebar-link {{ request()->routeIs('ads.popups.*') ? 'active' : '' }}" title="Popup ads">
    <i class="ph ph-browser"></i>
    <span class="sidebar-link-text truncate">Popup Ads</span>
</a>
<a href="{{ route('ads.leads.index') }}" class="sidebar-link {{ request()->routeIs('ads.leads.*') ? 'active' : '' }}" title="Lead enquiries">
    <i class="ph ph-user-list"></i>
    <span class="sidebar-link-text truncate">Lead Enquiries</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
