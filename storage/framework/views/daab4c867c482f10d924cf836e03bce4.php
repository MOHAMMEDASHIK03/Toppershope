<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Topper\'s Hope'); ?> - <?php echo e(config('app.name', 'Topper\'s Hope')); ?></title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #FFFFFF;
            color: #111827;
            overflow-x: hidden;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
        }

        .text-gradient {
            background: linear-gradient(to right, #7723D6, #9F7AEA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Pure CSS MegaMenu Dropdown — see .mega-menu below nav-shell */
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
            overflow: visible;
        }

        .nav-shell-inner,
        .nav-shell-row {
            overflow: visible;
        }

        :root {
            --th-public-nav-h: 78px;
        }

        @media (max-width: 639px) {
            :root {
                --th-public-nav-h: 72px;
            }
        }

        /* Mega menu — fixed full-width panel below navbar */
        .mega-menu {
            position: fixed;
            left: 0;
            right: 0;
            top: var(--th-public-nav-h);
            z-index: 60;
            max-height: calc(100vh - var(--th-public-nav-h));
            overflow-y: auto;
            background: #fff;
            border-top: 1px solid #E9D8FD;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
            pointer-events: none;
        }

        .mega-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .nav-brand-link {
            display: flex;
            align-items: center;
            min-height: 48px;
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
        }

        .nav-brand-link img {
            display: block;
        }

        .nav-links {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 0.125rem;
        }

        @media (min-width: 1024px) {
            .nav-links {
                gap: 0.25rem;
            }
        }

        @media (min-width: 1280px) {
            .nav-links {
                gap: 0.5rem;
            }
        }

        .nav-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1.25rem;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        @media (min-width: 1280px) {
            .nav-chip {
                padding: 0.625rem 1.125rem;
                font-size: 0.875rem;
            }
        }

        .nav-chip:hover {
            background: #F5F0FF;
            color: #7723D6;
        }

        .nav-chip.is-active {
            background: #F5F0FF;
            color: #7723D6;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        @media (min-width: 1280px) {
            .nav-actions {
                gap: 1rem;
            }
        }

        .nav-cta-primary {
            background: #7723D6;
            box-shadow: 0 10px 20px rgba(119, 35, 214, 0.22);
        }

        .nav-cta-primary:hover {
            transform: translateY(-1px);
            background: #6B21C8;
            box-shadow: 0 12px 24px rgba(119, 35, 214, 0.3);
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
    
    <!-- Public Navbar -->
    <nav class="fixed w-full z-50 nav-shell transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false, coursesMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 10)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 nav-shell-inner">
            <div class="flex items-center justify-between gap-4 min-h-[72px] sm:min-h-[78px] py-2 nav-shell-row">

                <!-- Logo -->
                <div class="flex items-center shrink-0 min-w-0 lg:flex-1">
                    <a href="/" class="nav-brand-link">
                        <?php if (isset($component)) { $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand.logo','data' => ['variant' => 'full','context' => 'navbar']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'full','context' => 'navbar']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $attributes = $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $component = $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
                    </a>
                </div>

                <!-- Desktop navigation -->
                <div class="hidden lg:flex items-center justify-center flex-1 px-4 xl:px-8 min-w-0 overflow-visible">
                    <div class="nav-links" @keydown.escape.window="coursesMenuOpen = false">
                        <button
                            type="button"
                            @click.stop="coursesMenuOpen = !coursesMenuOpen"
                            class="nav-chip text-gray-700 <?php echo e(request()->routeIs('category.*') ? 'is-active' : ''); ?>"
                            :class="{ 'is-active': coursesMenuOpen }"
                            :aria-expanded="coursesMenuOpen.toString()"
                        >
                            <span>All Courses</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': coursesMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <a href="<?php echo e(route('about')); ?>" class="nav-chip text-gray-700 <?php echo e(request()->routeIs('about') ? 'is-active' : ''); ?>">About Us</a>
                        <a href="<?php echo e(route('careers')); ?>" class="nav-chip text-gray-700 <?php echo e(request()->routeIs('careers*') ? 'is-active' : ''); ?>">Careers</a>
                        <a href="<?php echo e(route('faq')); ?>" class="nav-chip text-gray-700 <?php echo e(request()->routeIs('faq') ? 'is-active' : ''); ?>">FAQ</a>
                        <a href="<?php echo e(route('contact')); ?>" class="nav-chip text-gray-700 <?php echo e(request()->routeIs('contact') ? 'is-active' : ''); ?>">Contact Us</a>
                    </div>
                </div>

                <!-- Right actions -->
                <div class="flex items-center justify-end gap-2 sm:gap-3 shrink-0 lg:flex-1">
                    <div class="hidden lg:flex nav-actions">
                        <?php if (isset($component)) { $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $attributes = $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $component = $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
                        <a href="tel:+919876543210" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-50 px-4 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Support
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('logout')); ?>" class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-900 px-4 xl:px-5 py-2.5 rounded-xl text-sm font-bold transition-all border border-gray-200 whitespace-nowrap">Logout</a>
                            <a href="<?php echo e(route('dashboard')); ?>" class="nav-cta-primary inline-flex items-center justify-center text-white px-4 xl:px-5 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="nav-cta-primary inline-flex items-center justify-center text-white px-5 xl:px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap">Login / Register</a>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile menu toggle -->
                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen; coursesMenuOpen = false"
                        class="lg:hidden p-2.5 text-gray-700 bg-white border border-gray-200 hover:text-primary-600 hover:border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-600/20 rounded-xl shadow-sm shrink-0"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-label="Toggle menu"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- All Courses mega menu (full-width panel below navbar) -->
        <div
            @click.outside="coursesMenuOpen = false"
            @keydown.escape.window="coursesMenuOpen = false"
            class="mega-menu hidden lg:block"
            :class="{ 'active': coursesMenuOpen }"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <?php $navChunks = ($navCategories ?? collect())->chunk(max(1, (int) ceil(max(1, ($navCategories ?? collect())->count()) / 3))); ?>
                <?php if(($navCategories ?? collect())->isEmpty()): ?>
                    <p class="text-sm text-gray-500 text-center py-6">No course categories available yet.</p>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-1 mb-6">
                    <?php $__currentLoopData = $navChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="space-y-1">
                        <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('category.show', $navCat)); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-primary-50 group/item transition-colors" @click="coursesMenuOpen = false">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                <?php if($navCat->landing('icon_svg')): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($navCat->landing('icon_svg')); ?>"></path></svg>
                                <?php else: ?>
                                <span class="text-xs font-bold"><?php echo e(strtoupper(substr($navCat->name, 0, 2))); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 group-hover/item:text-primary-600 transition-colors"><?php echo e($navCat->name); ?></p>
                                <?php if($navCat->activeSubcategories->isNotEmpty()): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e($navCat->activeSubcategories->pluck('name')->take(3)->join(', ')); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
                <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-xs text-gray-500">
                        <span>1M+ Students</span>
                        <span>4.9/5 Rating</span>
                        <span><?php echo e(($navCategories ?? collect())->count()); ?> Course Categories</span>
                    </div>
                    <a href="<?php echo e(url('/')); ?>#categories" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors shrink-0" @click="coursesMenuOpen = false">View All Courses &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Mega menu backdrop -->
        <div
            x-show="coursesMenuOpen"
            x-transition.opacity
            @click="coursesMenuOpen = false"
            class="fixed inset-x-0 bottom-0 z-[55] bg-gray-900/25 hidden lg:block"
            style="display: none; top: var(--th-public-nav-h);"
        ></div>

        <!-- Mobile menu -->
        <div
            x-show="mobileMenuOpen"
            x-transition.opacity
            style="display: none;"
            @click.self="mobileMenuOpen = false"
            class="lg:hidden fixed inset-0 z-[60] bg-gray-900/40 backdrop-blur-sm"
        >
            <div class="absolute top-[72px] sm:top-[78px] left-0 right-0 bottom-0 bg-white border-t border-gray-100 shadow-2xl overflow-y-auto">
                <div class="px-4 sm:px-6 pt-4 pb-8 space-y-1 max-w-lg mx-auto" x-data="{ mobileCoursesOpen: false }">
                    <a href="<?php echo e(url('/')); ?>" class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors">Home</a>

                    <button
                        type="button"
                        @click="mobileCoursesOpen = !mobileCoursesOpen"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors"
                        :aria-expanded="mobileCoursesOpen.toString()"
                    >
                        <span>All Courses</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 shrink-0" :class="{'rotate-180': mobileCoursesOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="mobileCoursesOpen" x-transition.opacity style="display: none;" class="bg-primary-50/50 rounded-2xl p-3 mb-2 space-y-1 border border-primary-100 max-h-[50vh] overflow-y-auto">
                        <?php $__currentLoopData = ($navCategories ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navCat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('category.show', $navCat)); ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white active:bg-primary-100 transition-colors">
                            <div class="w-9 h-9 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 text-xs font-bold"><?php echo e(strtoupper(substr($navCat->name, 0, 2))); ?></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 leading-tight truncate"><?php echo e($navCat->name); ?></p>
                                <?php if($navCat->activeSubcategories->isNotEmpty()): ?>
                                <p class="text-xs text-gray-500 truncate"><?php echo e($navCat->activeSubcategories->pluck('name')->take(3)->join(', ')); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('/')); ?>#categories" class="block w-full text-center py-3 text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">View All Courses &rarr;</a>
                    </div>

                    <a href="<?php echo e(route('about')); ?>" class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors">About Us</a>
                    <a href="<?php echo e(route('careers')); ?>" class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors">Careers</a>
                    <a href="<?php echo e(route('faq')); ?>" class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors">FAQ</a>
                    <a href="<?php echo e(route('contact')); ?>" class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-gray-900 hover:bg-primary-50 hover:text-primary-600 transition-colors">Contact Us</a>

                    <div class="pt-4 mt-2 border-t border-gray-100 space-y-2">
                        <div class="flex justify-center py-2">
                            <?php if (isset($component)) { $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $attributes = $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $component = $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
                        </div>
                        <a href="tel:+919876543210" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-gray-700 bg-gray-100 border border-gray-200 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            Support
                        </a>
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-3 rounded-xl font-bold text-white nav-cta-primary text-center">Dashboard</a>
                            <a href="<?php echo e(route('logout')); ?>" class="block px-4 py-3 rounded-xl font-bold text-center text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Logout</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="block px-4 py-3 rounded-xl font-bold text-white nav-cta-primary text-center">Login / Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-[72px] sm:pt-[78px]">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Rich Light Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <a href="/" class="flex items-center mb-6">
                        <?php if (isset($component)) { $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand.logo','data' => ['variant' => 'full','context' => 'footer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'full','context' => 'footer']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $attributes = $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $component = $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
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
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/layouts/public.blade.php ENDPATH**/ ?>