@php
    $panelKey = $panelKey ?? 'panel';
    $consoleTitle = $consoleTitle ?? 'Dashboard';
    $consoleSubtitle = $consoleSubtitle ?? "Topper's Hope";
    $guard = $guard ?? null;
    $userRole = $userRole ?? '';
    $logoutRoute = $logoutRoute ?? 'logout';

    $panelUser = null;
    if ($guard) {
        $panelUser = Auth::guard($guard)->user();
    } elseif (auth()->check()) {
        $panelUser = auth()->user();
    }

    $userName = $userName ?? ($panelUser->name ?? 'User');
    $userInitial = strtoupper(substr($userName, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $consoleTitle) | Topper's Hope</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js" type="module"></script>
    @include('components.panel.styles')
    @stack('panel-extra-styles')
    @stack('styles')
</head>
<body class="h-full overflow-hidden bg-[#f4f6f8] text-slate-800 flex">

    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 z-40 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>

    <aside id="sidebar"
           class="panel-sidebar fixed lg:sticky top-0 h-screen z-50 flex flex-col bg-white border-r border-slate-200 -translate-x-full lg:translate-x-0 shrink-0">

        <div class="sidebar-brand-row px-3 h-16 flex items-center gap-2 border-b border-slate-100 shrink-0 min-w-0">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white font-bold text-sm shrink-0">TH</div>
            <div class="sidebar-brand-text min-w-0 flex-1 leading-tight">
                <p class="font-bold text-sm text-slate-900 truncate">{{ $consoleTitle }}</p>
                <p class="text-[10px] text-slate-500 font-medium truncate">{{ $consoleSubtitle }}</p>
            </div>
            <button type="button"
                    id="sidebar-collapse-btn"
                    class="sidebar-collapse-btn hidden lg:flex"
                    onclick="toggleSidebarCollapse()"
                    aria-label="Collapse sidebar"
                    title="Collapse sidebar">
                <i class="ph ph-caret-left text-sm" id="sidebar-collapse-icon"></i>
            </button>
        </div>

        <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-0.5">
            @yield('sidebar-nav')
        </nav>

        <div class="p-3 border-t border-slate-100 shrink-0">
            <div class="sidebar-footer-user flex items-center gap-2.5 px-2 py-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold shrink-0">
                    {{ $userInitial }}
                </div>
                <div class="sidebar-footer-text min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $userName }}</p>
                    @if($userRole)
                        <p class="text-[10px] text-slate-500 truncate">{{ $userRole }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route($logoutRoute) }}" class="sidebar-signout w-full sidebar-link text-rose-600 hover:bg-rose-50 hover:text-rose-700" title="Sign out">
                <i class="ph ph-sign-out"></i>
                <span class="sidebar-link-text">Sign out</span>
            </a>
        </div>

        <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden absolute top-4 right-3 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg" aria-label="Close menu">
            <i class="ph ph-x text-lg"></i>
        </button>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 min-h-0 h-full overflow-hidden">
        <header class="h-14 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 -ml-1 text-slate-500 hover:bg-slate-100 rounded-lg" aria-label="Open menu">
                    <i class="ph ph-list text-xl"></i>
                </button>
                @php
                    $panelPageTitle = trim($__env->yieldContent('page_title'));
                    if ($panelPageTitle === '') {
                        $panelPageTitle = trim($__env->yieldContent('page_header'));
                    }
                    if ($panelPageTitle === '') {
                        $panelPageTitle = 'Dashboard';
                    }
                @endphp
                <p class="text-sm font-semibold text-slate-800 truncate">{!! html_entity_decode($panelPageTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</p>
            </div>
            <div class="flex items-center gap-2">
                @stack('header-actions')
            </div>
        </header>

        <main class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8">
            @stack('before-content')
            @yield('content')
        </main>
    </div>

    @include('components.panel.scripts', ['panelKey' => $panelKey])
    <x-panel.confirm-modal />
    <x-toast-stack />
    @stack('scripts')
</body>
</html>
