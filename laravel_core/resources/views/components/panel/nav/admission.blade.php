<p class="sidebar-section-label px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Overview</p>
<a href="{{ route('admission.dashboard') }}" class="sidebar-link {{ request()->routeIs('admission.dashboard') ? 'active' : '' }}" title="Dashboard">
    <i class="ph ph-squares-four"></i>
    <span class="sidebar-link-text truncate">Dashboard</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">CRM</p>
<a href="{{ route('admission.contacts.index', ['tab' => 'enrol_leads']) }}" class="sidebar-link {{ request()->routeIs('admission.contacts.*') && request('tab', 'enrol_leads') === 'enrol_leads' ? 'active' : '' }}" title="Enrol leads">
    <i class="ph ph-user-plus"></i>
    <span class="sidebar-link-text truncate">Enrol Leads</span>
</a>
<a href="{{ route('admission.contacts.index', ['tab' => 'interest_leads']) }}" class="sidebar-link {{ request()->routeIs('admission.contacts.*') && request('tab') === 'interest_leads' ? 'active' : '' }}" title="Interested leads">
    <i class="ph ph-heart"></i>
    <span class="sidebar-link-text truncate">Interested Leads</span>
</a>
<a href="{{ route('admission.contacts.index', ['tab' => 'new_users']) }}" class="sidebar-link {{ request()->routeIs('admission.contacts.*') && request('tab') === 'new_users' ? 'active' : '' }}" title="New registrations">
    <i class="ph ph-user-circle-plus"></i>
    <span class="sidebar-link-text truncate">New Registrations</span>
</a>
<a href="{{ route('admission.contacts.index', ['tab' => 'non_purchasers']) }}" class="sidebar-link {{ request()->routeIs('admission.contacts.*') && request('tab') === 'non_purchasers' ? 'active' : '' }}" title="Non-purchasers">
    <i class="ph ph-shopping-cart"></i>
    <span class="sidebar-link-text truncate">Non-Purchasers</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Trials</p>
<a href="{{ route('admission.trials.index') }}" class="sidebar-link {{ request()->routeIs('admission.trials.*') ? 'active' : '' }}" title="Active trials">
    <i class="ph ph-clock-countdown"></i>
    <span class="sidebar-link-text truncate">Active Trials</span>
</a>

<p class="sidebar-section-label px-3 pt-5 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</p>
<a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="sidebar-link" title="Visit our website">
    <i class="ph ph-globe"></i>
    <span class="sidebar-link-text truncate">Visit our website</span>
    <i class="ph ph-arrow-square-out sidebar-link-external ml-auto text-xs opacity-50"></i>
</a>
