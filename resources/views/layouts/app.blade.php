<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Topper\'s Hope') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #ffffff; color: #111827; overflow-x: hidden; font-family: 'Inter', sans-serif; }
        .glass-panel { background: #ffffff; border: 1px solid #E5E7EB; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08); }
        .text-gradient { color: #7723D6; }
        .watermark-overlay { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); opacity: 0.15; pointer-events: none; z-index: 9999; font-size: 2rem; color: rgba(119,35,214,0.3); text-align: center; line-height: 1.5; white-space: nowrap; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-white">
    @auth
        <div class="watermark-overlay">
            {{ Auth::user()->name }}<br>
            {{ Auth::user()->email }}<br>
            <span id="wm-timestamp"></span>
        </div>
    @endauth

    <nav class="fixed w-full z-50 glass-panel border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-2">
                    <x-brand.logo variant="full" context="navbar" />
                </a>
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('student.doubts.index') }}" class="text-sm font-semibold text-gray-600 hover:text-primary-600">Doubts</a>
                    <x-panel.theme-toggle />
                    @auth
                        <a href="{{ route('logout') }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg">Logout</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg">Login</a>
                    @endauth
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <div x-show="mobileMenuOpen" x-transition style="display: none;" class="md:hidden border-t border-gray-200 p-4 space-y-2">
            <a href="{{ route('student.doubts.index') }}" class="block px-3 py-2 font-semibold text-gray-700">Doubts</a>
            @auth
                <a href="{{ route('logout') }}" class="block px-3 py-2 font-bold text-red-600">Logout</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 font-bold text-primary-600">Login</a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Topper's Hope. All rights reserved.
        </div>
    </footer>

    <script>
        @auth
        setInterval(() => {
            const wm = document.getElementById('wm-timestamp');
            if (wm) wm.innerText = new Date().toLocaleString();
        }, 1000);
        @endauth
    </script>
    <x-toast-stack />
    @stack('scripts')
</body>
</html>
