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
    $userEmail = $userEmail ?? null;
    if ($panelUser && !$userEmail) {
        $panelProfile = config("panel-profiles.panels.{$panelKey}");
        $emailField = $panelProfile['email_attribute'] ?? 'email';
        $userEmail = (string) ($panelUser->{$emailField} ?? $panelUser->email ?? '');
    }
    $userInitial = strtoupper(substr($userName, 0, 1));
    $companyName = $companyName ?? "Topper's Hope";
    $profileRoute = $profileRoute ?? config("panel-profiles.panels.{$panelKey}.profile_route");

    $panelBrandName = $panelBrandName ?? null;
    if (!$panelBrandName) {
        if (strcasecmp((string) $consoleSubtitle, "Topper's Hope") !== 0) {
            $panelBrandName = $consoleSubtitle;
        } elseif (strcasecmp((string) $consoleTitle, "Topper's Hope") !== 0) {
            $panelBrandName = $consoleTitle;
        } else {
            $panelBrandName = $userRole ?: 'Dashboard';
        }
    }
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
<body class="h-full overflow-hidden bg-white dark:bg-[#0F0F12] text-gray-900 dark:text-white flex">

    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/40 z-40 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>

    <aside id="sidebar"
           class="panel-sidebar fixed lg:sticky top-0 h-screen z-50 flex flex-col bg-white dark:bg-[#17171C] border-r border-gray-200 dark:border-[#2D2D35] -translate-x-full lg:translate-x-0 shrink-0">

        <div class="sidebar-brand-row px-3 py-3 min-h-[4.5rem] flex items-center gap-2.5 border-b border-gray-100 dark:border-[#2D2D35] shrink-0">
            <div class="sidebar-brand-logo shrink-0" aria-hidden="true">
                <img src="{{ asset('images/brand/logo-icon.png') }}"
                     data-fallback="{{ asset('images/brand/logo-icon.jpg') }}"
                     alt=""
                     class="sidebar-brand-icon"
                     width="44"
                     height="44"
                     loading="eager"
                     decoding="async"
                     onerror="if (this.dataset.fallback && this.src !== this.dataset.fallback) { this.src = this.dataset.fallback; }" />
            </div>
            <div class="sidebar-brand-text min-w-0 flex-1 leading-snug">
                <p class="sidebar-brand-company text-[15px] font-bold leading-tight truncate">{{ $companyName }}</p>
                <p class="sidebar-brand-panel text-[11px] font-semibold truncate">{{ $panelBrandName }}</p>
            </div>
            <button type="button"
                    id="sidebar-collapse-btn"
                    class="sidebar-collapse-btn hidden lg:flex shrink-0"
                    onclick="toggleSidebarCollapse()"
                    aria-label="Collapse sidebar"
                    title="Collapse sidebar">
                <i class="ph ph-caret-left text-sm" id="sidebar-collapse-icon"></i>
            </button>
        </div>

        <nav id="sidebar-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 space-y-0.5">
            @yield('sidebar-nav')
        </nav>

        <div class="p-3 border-t border-gray-100 dark:border-[#2D2D35] shrink-0">
            @php
                $profileUrl = $profileRoute && \Illuminate\Support\Facades\Route::has($profileRoute)
                    ? route($profileRoute)
                    : null;
            @endphp
            @if($profileUrl)
                <a href="{{ $profileUrl }}"
                   class="sidebar-footer-user sidebar-footer-profile flex items-center gap-2.5 px-2 py-2 mb-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                   title="View profile">
                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300 flex items-center justify-center text-xs font-bold shrink-0">
                        {{ $userInitial }}
                    </div>
                    <div class="sidebar-footer-text min-w-0 flex-1">
                        @if($userRole)
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $userRole }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail ?: $userName }}</p>
                        @else
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $userName }}</p>
                            @if($userEmail)
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                            @endif
                        @endif
                    </div>
                </a>
            @else
                <div class="sidebar-footer-user flex items-center gap-2.5 px-2 py-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300 flex items-center justify-center text-xs font-bold shrink-0">
                        {{ $userInitial }}
                    </div>
                    <div class="sidebar-footer-text min-w-0 flex-1">
                        @if($userRole)
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $userRole }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail ?: $userName }}</p>
                        @else
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $userName }}</p>
                            @if($userEmail)
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
            <a href="{{ route($logoutRoute) }}" class="sidebar-signout w-full sidebar-link text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" title="Sign out">
                <i class="ph ph-sign-out"></i>
                <span class="sidebar-link-text">Sign out</span>
            </a>
        </div>

        <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden absolute top-4 right-3 p-1.5 text-gray-400 hover:text-gray-600 rounded-lg" aria-label="Close menu">
            <i class="ph ph-x text-lg"></i>
        </button>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 min-h-0 h-full overflow-hidden">
        <header class="panel-header h-14 bg-white dark:bg-[#17171C] border-b border-gray-200 dark:border-[#2D2D35] flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 -ml-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg" aria-label="Open menu">
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
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">{!! html_entity_decode($panelPageTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-panel.theme-toggle />
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
