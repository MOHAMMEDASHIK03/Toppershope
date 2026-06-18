<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Topper\'s Hope') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Precompiled Tailwind via CDN for shared hosting without NPM -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1B2AFF',
                        secondary: '#00E0FF',
                        accent: '#7B61FF',
                        background: '#0F172A',
                    },
                    animation: {
                        'gradient-x': 'gradient-x 5s ease infinite',
                        'reveal': 'reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            },
                        },
                        'reveal': {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Glassmorphism & custom utility classes */
        body {
            background-color: #ffffff;
            color: #0f172a;
            overflow-x: hidden;
        }
        
        /* Dynamic gradient scroll illusion */
        .glass-panel {
            background: #ffffff;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        .glow-hover {
            transition: all 0.3s ease;
        }
        .glow-hover:hover {
            box-shadow: 0 0 20px rgba(27, 42, 255, 0.5), 0 0 40px rgba(0, 224, 255, 0.3);
            border-color: rgba(0, 224, 255, 0.5);
            transform: translateY(-2px);
        }

        .text-gradient {
            background: linear-gradient(to right, #1B2AFF, #00E0FF, #7B61FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradient-x 5s ease infinite;
        }

        /* Subtle Parallax Background Layer */
        .parallax-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: radial-gradient(circle at top right, rgba(123, 97, 255, 0.08), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(27, 42, 255, 0.08), transparent 40%);
        }

        /* Video/PDF Watermark */
        .watermark-overlay {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.15;
            pointer-events: none;
            z-index: 9999;
            font-size: 2rem;
            color: rgba(15,23,42,0.3);
            text-align: center;
            line-height: 1.5;
            white-space: nowrap;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans">
    <div class="parallax-bg"></div>
    
    <!-- Deterrent Watermark Overlay -->
    @auth
        <div class="watermark-overlay">
            {{ Auth::user()->name }}<br>
            {{ Auth::user()->email }}<br>
            <span id="wm-timestamp"></span>
        </div>
    @endauth

    <!-- Sticky Navbar -->
    <nav class="fixed w-full z-50 glass-panel border-b border-white/10 transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold tracking-tighter text-gradient">
                        TOPPER'S HOPE
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-6">
                        <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl transition-all group">
                            <svg class="w-5 h-5 mr-3 text-gray-500 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            My Courses
                        </a>
                        <a href="{{ route('student.doubts.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('student.doubts.*') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} rounded-xl transition-all group">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('student.doubts.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Doubt Resolution
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-xl transition-all group">
                            <svg class="w-5 h-5 mr-3 text-gray-500 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Performance
                        </a>
                        @auth
                            <a href="{{ route('logout') }}" class="glass-panel text-white px-5 py-2 rounded-full text-sm font-medium glow-hover inline-block">
                                Logout
                            </a>
                        @else
                            <a href="#" class="glass-panel text-white px-5 py-2 rounded-full text-sm font-medium glow-hover">Login</a>
                        @endauth
                    </div>
                </div>
                
                <!-- Mobile Hamburger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden ml-4 p-2 text-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition style="display: none;" class="md:hidden glass-panel border-t border-white/10 w-full shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="#" class="block px-3 py-2 rounded-md font-semibold text-white hover:bg-white/10">My Courses</a>
                <a href="{{ route('student.doubts.index') }}" class="block px-3 py-2 rounded-md font-semibold text-white hover:bg-white/10">Doubt Resolution</a>
                <a href="#" class="block px-3 py-2 rounded-md font-semibold text-white hover:bg-white/10">Performance</a>
                @auth
                    <a href="{{ route('logout') }}" class="mt-4 w-full text-center block px-3 py-2 rounded-md font-bold text-red-400 bg-white/5 hover:bg-red-500/20">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 mt-4 rounded-md font-bold text-white bg-white/10 hover:bg-white/20 text-center">Login / Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-white/10 mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} Topper's Hope. All rights reserved.
        </div>
    </footer>

    <!-- Watermark timestamp updater -->
    <script>
        // Update watermark timestamp dynamically
        @auth
        setInterval(() => {
            const wm = document.getElementById('wm-timestamp');
            if(wm) {
                wm.innerText = new Date().toLocaleString();
            }
        }, 1000);
        @endauth

    </script>
    <x-toast-stack />
    @stack('scripts')
</body>
</html>
