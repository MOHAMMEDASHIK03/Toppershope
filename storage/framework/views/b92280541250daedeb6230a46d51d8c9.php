<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Topper\'s Hope'); ?> - <?php echo e(config('app.name', 'Topper\'s Hope')); ?></title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">

    <!-- Precompiled Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1B2AFF',
                        secondary: '#00E0FF',
                        accent: '#7B61FF',
                        background: '#FFFFFF',
                        surface: '#F8FAFC',
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
        body {
            background-color: #FFFFFF;
            color: #111827; /* gray-900 */
            overflow-x: hidden;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .text-gradient {
            background: linear-gradient(to right, #1B2AFF, #00E0FF, #7B61FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: gradient-x 5s ease infinite;
        }

        /* Pure CSS MegaMenu Dropdown */
        .mega-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .mega-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Light mode card */
        .light-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB; /* gray-200 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        .light-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #D1D5DB; /* gray-300 */
            transform: translateY(-2px);
        }

        /* Subtle PW style background pattern */
        .bg-pattern {
            background-image: radial-gradient(#E5E7EB 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .nav-shell {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.06);
        }

        .nav-chip {
            border-radius: 9999px;
            padding: 0.5rem 0.9rem;
            transition: all 0.2s ease;
        }

        .nav-chip:hover {
            background: #eef2ff;
            color: #1B2AFF;
        }

        .nav-cta-primary {
            background: linear-gradient(135deg, #1B2AFF, #4F46E5);
            box-shadow: 0 10px 20px rgba(27, 42, 255, 0.22);
        }

        .nav-cta-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(27, 42, 255, 0.3);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        ::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-pattern">
    
    <!-- White Premium Navbar -->
    <nav class="fixed w-full z-50 nav-shell transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false, coursesMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 10)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[78px]">
                
                <!-- Left: Logo -->
                <div class="flex items-center lg:flex-1 h-full shrink-0">
                    <a href="/" class="text-[1.35rem] font-black tracking-tight text-slate-900 flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-slate-900 rounded-2xl flex items-center justify-center text-white text-xs shadow-lg shadow-slate-400/20">TH</div>
                        <span class="leading-none whitespace-nowrap">TOPPER'S HOPE</span>
                    </a>
                </div>

                <!-- Center: Navigation -->
                <div class="hidden lg:flex items-center justify-center h-full gap-2 shrink-0">
                        <div class="has-megamenu h-full flex items-center px-1 gap-1" @keydown.escape.window="coursesMenuOpen = false">
                            <span class="nav-chip text-sm font-semibold text-gray-700 transition-colors">
                                All Courses
                            </span>
                            <button
                                type="button"
                                @click.stop="coursesMenuOpen = !coursesMenuOpen"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:text-primary hover:bg-indigo-50 transition-all"
                                :aria-expanded="coursesMenuOpen.toString()"
                                aria-label="Toggle all courses menu"
                            >
                                <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': coursesMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown Panel -->
                            <!-- Full-width Premium Dropdown -->
                            <div @click.outside="coursesMenuOpen = false" class="mega-menu absolute top-[78px] left-0 w-full bg-white shadow-2xl border-t-2 border-primary/20 z-50" :class="{ 'active': coursesMenuOpen }">
                                <div class="max-w-7xl mx-auto px-6 py-8">
                                    <?php $navChunks = ($navCategories ?? collect())->chunk(max(1, (int) ceil(max(1, ($navCategories ?? collect())->count()) / 3))); ?>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                                        <?php $__currentLoopData = $navChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="space-y-1">
                                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('category.show', $navCat)); ?>" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-blue-50 group/item transition-colors">
                                                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-primary flex items-center justify-center shrink-0">
                                                    <?php if($navCat->landing('icon_svg')): ?>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($navCat->landing('icon_svg')); ?>"></path></svg>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900 group-hover/item:text-primary transition-colors"><?php echo e($navCat->name); ?></p>
                                                    <p class="text-xs text-gray-400"><?php echo e($navCat->activeSubcategories->pluck('name')->take(3)->join(', ')); ?></p>
                                                </div>
                                            </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <!-- Footer -->
                                    <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
                                        <div class="flex items-center gap-6 text-xs text-gray-400">
                                            <span>&#127891; 1M+ Students</span>
                                            <span>&#11088; 4.9/5 Rating</span>
                                            <span>&#128218; <?php echo e(($navCategories ?? collect())->count()); ?> Course Categories</span>
                                        </div>
                                        <a href="<?php echo e(url('/')); ?>" class="text-sm font-bold text-primary hover:text-blue-700 flex items-center gap-1 transition-colors">View All Courses &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        
                        <!-- Simple Links -->
                        <a href="<?php echo e(route('about')); ?>" class="nav-chip text-sm font-semibold <?php echo e(request()->routeIs('about') ? 'text-primary bg-indigo-50' : 'text-gray-700'); ?> transition-colors">About Us</a>
                        <a href="<?php echo e(route('careers')); ?>" class="nav-chip text-sm font-semibold <?php echo e(request()->routeIs('careers*') ? 'text-primary bg-indigo-50' : 'text-gray-700'); ?> transition-colors">Careers</a>
                        <a href="<?php echo e(route('faq')); ?>" class="nav-chip text-sm font-semibold <?php echo e(request()->routeIs('faq') ? 'text-primary bg-indigo-50' : 'text-gray-700'); ?> transition-colors">FAQ</a>
                        <a href="<?php echo e(route('contact')); ?>" class="nav-chip text-sm font-semibold <?php echo e(request()->routeIs('contact') ? 'text-primary bg-indigo-50' : 'text-gray-700'); ?> transition-colors">Contact Us</a>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center justify-end gap-3 lg:flex-1 shrink-0">
                    <div class="hidden md:flex items-center gap-3">
                    <a href="tel:+919876543210" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100/80 px-4 py-2 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Support
                    </a>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('logout')); ?>" class="inline-block bg-white hover:bg-slate-100 text-gray-900 px-5 py-2.5 rounded-xl text-sm font-bold transition-all border border-slate-200">
                            Logout
                        </a>
                        <a href="<?php echo e(route('dashboard')); ?>" class="nav-cta-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                            Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="nav-cta-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all">
                            Login / Register
                        </a>
                    <?php endif; ?>
                </div>

                    </div>

                    <!-- Mobile Hamburger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden ml-4 p-2.5 text-gray-700 bg-white border border-gray-200 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition.opacity style="display: none;" @click.self="mobileMenuOpen = false" class="md:hidden fixed inset-0 z-[60] bg-slate-900/40 backdrop-blur-sm pt-[78px]">
            <div class="bg-white border-t border-gray-100 shadow-2xl w-full h-[calc(100vh-78px)] overflow-y-auto rounded-t-2xl">
            <div class="px-4 pt-4 pb-8 space-y-2" x-data="{ mobileCoursesOpen: false }">
                <a href="<?php echo e(url('/')); ?>" class="block px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">Home</a>
                
                <!-- Mobile Courses Accordion -->
                <div>
                    <button @click="mobileCoursesOpen = !mobileCoursesOpen" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">
                        All Courses
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="{'rotate-180': mobileCoursesOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Courses List -->
                    <div x-show="mobileCoursesOpen" x-transition.opacity style="display: none;" class="bg-gradient-to-b from-indigo-50/70 to-white rounded-2xl p-4 mt-2 mb-2 space-y-2 border border-indigo-100 max-h-[60vh] overflow-y-auto">
                        <?php $__currentLoopData = ($navCategories ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('category.show', $navCat)); ?>" class="flex items-center gap-3 p-2 rounded-xl active:bg-blue-100 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-xs font-black"><?php echo e(strtoupper(substr($navCat->name, 0, 2))); ?></div>
                            <div>
                                <p class="text-[13px] font-bold text-gray-900 leading-tight"><?php echo e($navCat->name); ?></p>
                                <?php if($navCat->activeSubcategories->isNotEmpty()): ?>
                                <p class="text-[10px] text-gray-500"><?php echo e($navCat->activeSubcategories->pluck('name')->take(3)->join(', ')); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/')); ?>#categories" class="block w-full text-center mt-4 text-xs font-bold text-primary hover:text-blue-700 transition-colors">View All Courses &rarr;</a>
                    </div>
                </div>

                <a href="<?php echo e(route('about')); ?>" class="block px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">About Us</a>
                <a href="<?php echo e(route('careers')); ?>" class="block px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">Careers</a>
                <a href="<?php echo e(route('faq')); ?>" class="block px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">FAQ</a>
                <a href="<?php echo e(route('contact')); ?>" class="block px-3 py-2.5 rounded-xl font-semibold text-gray-900 hover:bg-gray-50 hover:text-primary">Contact Us</a>
                
                <div class="pt-4 mt-3 border-t border-gray-100 space-y-2">
                    <a href="tel:+919876543210" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl font-semibold text-slate-700 bg-slate-100 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Support
                    </a>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="block px-3 py-2.5 rounded-xl font-bold text-white nav-cta-primary text-center">Dashboard</a>
                        <a href="<?php echo e(route('logout')); ?>" class="w-full text-center block px-3 py-2.5 rounded-xl font-bold text-red-600 bg-red-50 hover:bg-red-100">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="block px-3 py-2.5 rounded-xl font-bold text-white nav-cta-primary text-center">Login / Register</a>
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-24">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Rich Light Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <a href="/" class="text-2xl font-black tracking-tighter text-gray-900 flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center text-white text-xs">TH</div>
                        TOPPER'S HOPE
                    </a>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                        We understand that every student has different needs. With well-structured courses, we provide the best education and guide you toward success.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:text-primary hover:border-primary/30 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:text-primary hover:border-primary/30 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:text-primary hover:border-primary/30 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 tracking-wide uppercase text-sm">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('about')); ?>" class="text-sm text-gray-500 hover:text-primary transition-colors">About Us</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="text-sm text-gray-500 hover:text-primary transition-colors">Contact Us</a></li>
                        <li><a href="<?php echo e(route('careers')); ?>" class="text-sm text-gray-500 hover:text-primary transition-colors">Careers</a></li>
                        <li><a href="<?php echo e(route('privacy')); ?>" class="text-sm text-gray-500 hover:text-primary transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-gray-500 hover:text-primary transition-colors">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-4 tracking-wide uppercase text-sm">Popular Exams</h3>
                    <ul class="space-y-3">
                        <?php $__currentLoopData = ($navCategories ?? collect())->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footerCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('category.show', $footerCat)); ?>" class="text-sm text-gray-500 hover:text-primary transition-colors"><?php echo e($footerCat->name); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-4 tracking-wide uppercase text-sm">Download App</h3>
                    <p class="text-xs text-gray-500 mb-4">Learn on the go with the Topper's Hope app.</p>
                    <div class="space-y-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-10 opacity-80 hover:opacity-100 transition-opacity cursor-pointer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Play Store" class="h-10 opacity-80 hover:opacity-100 transition-opacity cursor-pointer">
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400">
                    &copy; <?php echo e(date('Y')); ?> Topper's Hope. All rights reserved.
                </p>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <a href="<?php echo e(route('privacy')); ?>" class="hover:text-primary transition-colors">Privacy Policy</a>
                    <span>·</span>
                    <a href="<?php echo e(route('terms')); ?>" class="hover:text-primary transition-colors">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <?php if (isset($component)) { $__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast-stack','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast-stack'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007)): ?>
<?php $attributes = $__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007; ?>
<?php unset($__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007)): ?>
<?php $component = $__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007; ?>
<?php unset($__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007); ?>
<?php endif; ?>
    <?php echo $__env->make('components.pickers.init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/layouts/public.blade.php ENDPATH**/ ?>