

<?php $__env->startPush('scripts'); ?>
<script>
    // Always open landing page from the top (hero section) on refresh/reload.
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    window.addEventListener('beforeunload', () => {
        window.scrollTo(0, 0);
    });
    window.addEventListener('load', () => {
        window.scrollTo(0, 0);
    });
    window.addEventListener('pageshow', () => {
        window.scrollTo(0, 0);
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<?php if(isset($globalPopup) && $globalPopup->is_active && $globalPopup->image): ?>
<div id="global-popup-overlay" style="display:none"
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
    <div class="relative max-w-sm w-full bg-white rounded-3xl overflow-hidden shadow-2xl animate-pulse-once">
        <button onclick="document.getElementById('global-popup-overlay').style.display='none'"
                class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/50 hover:bg-black/70 text-white flex items-center justify-center text-xl font-bold transition">
            &times;
        </button>
        <?php if($globalPopup->link_url): ?>
        <a href="<?php echo e($globalPopup->link_url); ?>" target="_blank" onclick="document.getElementById('global-popup-overlay').style.display='none'">
            <img src="<?php echo e(Storage::url($globalPopup->image)); ?>" alt="Special Offer" class="w-full object-cover">
        </a>
        <?php if($globalPopup->link_text): ?>
        <div class="p-4 text-center">
            <a href="<?php echo e($globalPopup->link_url); ?>" target="_blank"
               class="inline-block px-6 py-2.5 bg-primary text-white font-black rounded-xl text-sm transition hover:bg-blue-700">
                <?php echo e($globalPopup->link_text); ?>

            </a>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <img src="<?php echo e(Storage::url($globalPopup->image)); ?>" alt="Special Offer" class="w-full object-cover">
        <?php endif; ?>
    </div>
</div>
<script>
    setTimeout(function() {
        document.getElementById('global-popup-overlay').style.display = 'flex';
    }, <?php echo e(($globalPopup->delay_seconds ?? 3) * 1000); ?>);
</script>
<?php endif; ?>


<div x-data="{
    activeSlide: 0,
    progress: 0,
    timer: null,
    slides: [
        { color: 'from-[#1B2AFF] via-[#2048ff] to-[#0f1fcc]', badge: 'Limited Time Offer', title: 'UP TO 55% OFF', sub: 'On JEE & NEET flagship batches. Scholarship test active now.', cta: 'Grab Offer', href: '#scholarship-test' },
        { color: 'from-[#7B61FF] via-[#6f52f0] to-[#5035d5]', badge: 'Early Access 2026', title: 'NEW ACADEMIC BATCHES', sub: 'Live classes starting soon. Book a free demo and reserve your seat.', cta: 'Book Demo', href: '#demo-session' },
        { color: 'from-[#0ea5e9] via-[#0b87cf] to-[#0369a1]', badge: 'National Scholarship', title: 'SCHOLARSHIP TEST LIVE', sub: 'Get up to 90% fee waiver. Register free and prove your potential.', cta: 'Register', href: '<?php echo e(route('register')); ?>' }
    ],
    startProgress() {
        clearInterval(this.timer);
        this.progress = 0;
        this.timer = setInterval(() => {
            this.progress += 2;
            if (this.progress >= 100) {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                this.progress = 0;
            }
        }, 100);
    },
    goTo(i) {
        this.activeSlide = i;
        this.progress = 0;
    },
    init() { this.startProgress(); }
}" x-init="init()" @mouseenter="clearInterval(timer)" @mouseleave="startProgress()" class="relative overflow-hidden group w-full">
    <template x-for="(slide, i) in slides" :key="i">
        <div x-show="activeSlide === i"
            x-transition:enter="transition duration-700"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition duration-500 absolute inset-0"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="w-full h-20 sm:h-24 md:h-28 flex items-center justify-between px-5 sm:px-10 lg:px-24 bg-gradient-to-r absolute inset-0"
            :class="slide.color">
            <div class="flex-1 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-5">
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.18em] text-white/80 mb-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white/80"></span>
                            <span x-text="slide.badge"></span>
                        </span>
                        <h2 class="text-lg sm:text-2xl md:text-3xl font-black uppercase tracking-tight leading-none" x-text="slide.title"></h2>
                    </div>
                    <p class="text-xs sm:text-sm text-white/85 max-w-sm hidden md:block" x-text="slide.sub"></p>
                </div>
            </div>
            <a :href="slide.href" class="shrink-0 px-4 py-2 md:px-5 md:py-2.5 bg-white text-slate-900 font-extrabold rounded-full text-xs md:text-sm transition-all shadow-lg hover:-translate-y-0.5" x-text="slide.cta"></a>
        </div>
    </template>
    
    <div class="h-20 sm:h-24 md:h-28 pointer-events-none"></div>
    
    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
        <template x-for="(s, i) in slides">
            <button @click="goTo(i)" class="h-1.5 rounded-full bg-white/60 transition-all" :class="activeSlide===i ? 'w-5 bg-white' : 'w-1.5'"></button>
        </template>
    </div>
    <button @click="goTo((activeSlide-1+slides.length)%slides.length)" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/10 hover:bg-white/30 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all z-20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    <button @click="goTo((activeSlide+1)%slides.length)" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/10 hover:bg-white/30 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all z-20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
    <div class="absolute bottom-0 left-0 h-0.5 bg-white/30 w-full"></div>
    <div class="absolute bottom-0 left-0 h-0.5 bg-white transition-all duration-100" :style="`width: ${progress}%`"></div>
</div>


<section id="hero" class="relative py-14 sm:py-20 lg:py-24 bg-[linear-gradient(180deg,#f8fbff_0%,#ffffff_45%,#f8faff_100%)] overflow-hidden">
    <div class="absolute top-0 right-0 w-[30rem] h-[30rem] rounded-full bg-blue-100/70 blur-3xl opacity-60 -translate-y-1/2 translate-x-1/3 z-0"></div>
    <div class="absolute bottom-0 left-0 w-[24rem] h-[24rem] rounded-full bg-purple-100/70 blur-3xl opacity-70 translate-y-1/3 -translate-x-1/4 z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            
            <div>
                <div id="hero-badge" class="inline-flex items-center gap-2 py-1.5 px-3.5 rounded-full bg-blue-50 border border-blue-100 text-xs sm:text-sm font-bold text-primary mb-6 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    India’s Trusted Exam Preparation Platform
                </div>

                <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl tracking-tight font-black text-gray-900 mb-5 leading-[1.04]">
                    Learn From Expert Educators &
                    <span class="bg-gradient-to-r from-[#1B2AFF] via-[#4f46e5] to-[#7B61FF] bg-clip-text text-transparent">
                        Achieve Your Dream Rank
                    </span>
                </h1>

                <p id="hero-sub" class="text-base sm:text-lg text-gray-600 mb-7 max-w-2xl leading-relaxed">
                    Join aspirants preparing for IIT JEE, NEET, UPSC, school boards, and government exams with live classes, structured study plans, mock tests, performance analytics, and personalized mentorship.
                </p>

                <div id="hero-btns" class="flex flex-col sm:flex-row gap-4">
                    <a href="#categories" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-white font-extrabold text-base bg-gradient-to-r from-[#1B2AFF] to-[#4f46e5] shadow-[0_8px_30px_rgba(27,42,255,0.30)] hover:shadow-[0_12px_34px_rgba(27,42,255,0.38)] hover:-translate-y-0.5 transition-all">
                        Explore Courses
                    </a>
                    <a href="#demo-session" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-gray-800 font-extrabold text-base border border-gray-200 bg-white hover:bg-gray-50 transition-all shadow-sm">
                        Watch Demo Class
                        <svg class="ml-2 w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                </div>

                <div id="hero-social" class="mt-7 flex flex-wrap items-center gap-4 sm:gap-6">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-xs font-bold text-blue-700">RS</div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 border-2 border-white flex items-center justify-center text-xs font-bold text-purple-700">MP</div>
                        <div class="w-10 h-10 rounded-full bg-green-100 border-2 border-white flex items-center justify-center text-xs font-bold text-green-700">AK</div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-orange-700">1M+</div>
                    </div>
                    <div class="text-sm font-semibold text-gray-500">Trusted by <span class="text-gray-900 font-black">1 Million+ Students</span></div>
                </div>

            </div>

            
            <div class="relative" id="hero-graphic">
                <div class="relative p-4 sm:p-5 rounded-[2rem] bg-white/70 border border-white/80 shadow-[0_20px_60px_rgba(30,41,59,0.12)] backdrop-blur-md">
                    <div class="rounded-[1.5rem] overflow-hidden border border-gray-200 bg-white shadow-sm">
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                </span>
                                <div>
                                    <p class="text-[11px] font-bold text-gray-500">Live Interactive Session</p>
                                    <p class="text-sm font-black text-gray-900">Physics + Chemistry Marathon</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Live
                            </span>
                        </div>

                        <div class="relative h-52 sm:h-60 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=900" alt="Expert faculty teaching class" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between text-white">
                                <div>
                                    <p class="text-xl font-black leading-none">Crack JEE / NEET</p>
                                    <p class="text-xs font-medium text-white/80 mt-1">Faculty-led concepts, tests, and doubt solving</p>
                                </div>
                                <button class="w-11 h-11 rounded-full bg-white/20 border border-white/30 backdrop-blur flex items-center justify-center hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5 ml-0.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-4 grid grid-cols-3 gap-3">
                            <div class="rounded-xl bg-blue-50 border border-blue-100 p-3">
                                <p class="text-xl font-black text-primary leading-none">98%</p>
                                <p class="text-[10px] font-bold uppercase tracking-wide text-blue-700 mt-1">Success Rate</p>
                            </div>
                            <div class="rounded-xl bg-purple-50 border border-purple-100 p-3">
                                <p class="text-xl font-black text-purple-600 leading-none">AIR 47</p>
                                <p class="text-[10px] font-bold uppercase tracking-wide text-purple-700 mt-1">Top Rank</p>
                            </div>
                            <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3">
                                <p class="text-xl font-black text-emerald-600 leading-none">12.8k</p>
                                <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-700 mt-1">Live Now</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<div class="relative py-6 bg-gradient-to-b from-slate-50 to-white border-y border-slate-200/80 overflow-x-auto">
    <div class="absolute inset-x-0 top-0 h-20 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.10),transparent_65%)] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex gap-2.5 sm:gap-3 items-center justify-start md:justify-center min-w-max mx-auto">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('category.show', $pillCategory)); ?>" class="px-4 sm:px-5 py-2 rounded-full <?php echo e($loop->first ? 'bg-gradient-to-r from-primary to-indigo-600 text-white border border-indigo-600 shadow-[0_6px_20px_rgba(79,70,229,0.35)]' : 'bg-white/90 backdrop-blur border border-slate-200 text-slate-600 hover:text-primary hover:border-indigo-200 hover:shadow-md'); ?> font-bold text-xs sm:text-sm transition-all whitespace-nowrap">
                <?php echo e($pillCategory->name); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<section id="categories" class="relative py-20 bg-[linear-gradient(180deg,#f8faff_0%,#ffffff_55%,#f8fbff_100%)] overflow-hidden">
    <div class="absolute top-0 right-0 w-[28rem] h-[28rem] bg-indigo-100/60 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[24rem] h-[24rem] bg-cyan-100/60 blur-3xl rounded-full translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-12 gsap-reveal">
            <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-[0.14em] text-primary bg-primary/10 border border-primary/20 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                Targeted Exam Preparation
            </p>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-3 tracking-tight">Explore Exam Categories</h2>
            <p class="text-slate-500 max-w-3xl mx-auto text-sm sm:text-base">Pick your goal and start a structured path with expert educators, live classes, test series, and personalized learning support.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 gsap-stagger-parent">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('category.show', $cat)); ?>" class="gsap-stagger-child group relative rounded-2xl p-[1px] bg-gradient-to-br from-white via-indigo-100/70 to-sky-100/80 hover:from-indigo-200 hover:to-cyan-200 transition-all duration-300">
                <div class="relative h-full min-h-[205px] rounded-2xl border border-white/80 bg-white/85 backdrop-blur-sm shadow-[0_12px_30px_rgba(15,23,42,0.06)] group-hover:shadow-[0_20px_35px_rgba(79,70,229,0.16)] group-hover:-translate-y-1 transition-all duration-300 p-5 flex flex-col">
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-gradient-to-br from-indigo-100/60 to-cyan-100/60 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?php echo e($cat->landing('icon_bg', 'from-indigo-500 to-blue-500')); ?> text-white shadow-md flex items-center justify-center shrink-0">
                            <?php if($cat->landing('icon_svg')): ?>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo e($cat->landing('icon_svg')); ?>"></path>
                            </svg>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-primary transition-colors leading-tight"><?php echo e($cat->name); ?></h3>
                    </div>

                    <div class="flex flex-wrap gap-1.5 mb-5">
                        <?php $__currentLoopData = $cat->activeSubcategories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold text-slate-600 bg-slate-50 border border-slate-200"><?php echo e($sub->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-auto inline-flex items-center justify-between">
                        <span class="inline-flex items-center gap-2 text-sm font-extrabold text-primary">
                            Explore Category
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="relative py-20 bg-[linear-gradient(180deg,#ffffff_0%,#f8fbff_100%)] border-t border-gray-100 overflow-hidden">
    <div class="absolute top-0 right-0 w-[26rem] h-[26rem] bg-indigo-100/60 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[22rem] h-[22rem] bg-cyan-100/60 blur-3xl rounded-full translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex items-end justify-between mb-10 gsap-reveal">
            <div>
                <p class="inline-flex items-center gap-2 text-[11px] font-black tracking-[0.14em] uppercase text-primary bg-primary/10 border border-primary/20 rounded-full px-3 py-1 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    Top Trending Batches
                </p>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Most Popular Live Programs</h2>
                <p class="text-slate-500 mt-2 text-sm sm:text-base">Explore high-conversion exam batches trusted by top rankers and serious aspirants.</p>
            </div>
            <a href="#" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-extrabold text-primary border border-indigo-200 bg-white hover:bg-indigo-50 transition-colors shadow-sm">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="gsap-stagger-parent flex md:grid md:grid-cols-2 xl:grid-cols-4 gap-5 overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none pb-2">
            <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="gsap-stagger-child w-[86%] sm:w-[58%] md:w-auto shrink-0 snap-start h-full">
                    <?php if (isset($component)) { $__componentOriginal5893207d9476437856f98ef73217a812 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5893207d9476437856f98ef73217a812 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.batch-card','data' => ['batch' => $batch]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('batch-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['batch' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($batch)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5893207d9476437856f98ef73217a812)): ?>
<?php $attributes = $__attributesOriginal5893207d9476437856f98ef73217a812; ?>
<?php unset($__attributesOriginal5893207d9476437856f98ef73217a812); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5893207d9476437856f98ef73217a812)): ?>
<?php $component = $__componentOriginal5893207d9476437856f98ef73217a812; ?>
<?php unset($__componentOriginal5893207d9476437856f98ef73217a812); ?>
<?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="w-full md:col-span-2 xl:col-span-4 py-16 text-center border-2 border-dashed border-gray-200 rounded-2xl bg-white/80">
                    <p class="text-lg font-bold text-gray-500">New batches arriving soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php if(isset($upcomingBatches) && $upcomingBatches->count() > 0): ?>
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 gsap-reveal">
            <span class="inline-block bg-amber-50 border border-amber-200 text-amber-800 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-4">🚀 Coming Soon</span>
            <h2 class="text-3xl font-black text-gray-900 mb-2">Upcoming Batch Programs</h2>
            <p class="text-gray-500 text-sm">Register your interest — early access slots fill fast!</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 gsap-stagger-parent">
            <?php $__currentLoopData = $upcomingBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="gsap-stagger-child relative bg-white rounded-2xl border-2 border-amber-200 p-6 shadow-sm hover:shadow-lg transition-all flex flex-col gap-3 overflow-hidden group">
                <div class="absolute top-3 right-3 bg-amber-400 text-amber-900 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase animate-pulse">Coming Soon</div>
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-2xl">🚀</div>
                <div>
                    <a href="<?php echo e(route('course.detail', $ub->course->slug)); ?>" class="font-bold text-gray-900 text-base hover:text-primary transition-colors block mb-0.5"><?php echo e($ub->name); ?></a>
                    <?php if($ub->batch_description): ?>
                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($ub->batch_description); ?></p>
                    <?php elseif($ub->course->description): ?>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?php echo e($ub->course->description); ?></p>
                    <?php endif; ?>
                </div>
                <?php if($ub->start_date): ?>
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Starting <?php echo e($ub->start_date->format('d M Y')); ?>

                </div>
                <?php endif; ?>
                <?php if($ub->mode): ?>
                <span class="text-[10px] font-bold bg-blue-50 text-primary border border-blue-100 px-2 py-0.5 rounded-full self-start"><?php echo e($ub->mode); ?></span>
                <?php endif; ?>
                <div class="flex items-center justify-between mt-auto border-t border-gray-100 pt-3">
                    <p class="text-lg font-black text-gray-900">₹<?php echo e(number_format($ub->price)); ?></p>
                    <a href="<?php echo e(route('contact')); ?>" class="px-4 py-2 text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-colors">
                        Register Interest
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section id="scholarship-test" class="py-12 bg-slate-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="gsap-reveal relative rounded-3xl overflow-hidden border border-cyan-200/70 bg-gradient-to-r from-cyan-50 via-blue-50 to-indigo-50 shadow-[0_14px_35px_rgba(14,116,144,0.14)]">
            <div class="absolute -top-20 -right-12 w-64 h-64 bg-cyan-200/40 rounded-full blur-3xl"></div>
            <div class="grid md:grid-cols-2 items-center gap-8 px-6 py-8 md:px-10 md:py-10 relative">
                <div>
                    <span class="inline-flex items-center gap-2 bg-cyan-100 text-cyan-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 border border-cyan-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z"></path></svg>
                        Limited Seats
                    </span>
                    <h3 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-3">Scholarship Admission Test<br><span class="text-primary">Up To 90% Scholarship</span></h3>
                    <p class="text-slate-600 mb-6 max-w-lg">Take our nationwide test and unlock major fee waivers for premium programs. Prove your potential and begin your success journey.</p>
                    <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center gap-2 px-7 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-[0_8px_22px_rgba(15,23,42,0.30)]">
                        Enroll Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden border border-white/70 shadow-xl">
                        <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&q=80&w=1200" alt="Students taking scholarship exam" loading="lazy" class="w-full h-64 md:h-72 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-slate-900/10 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="demo-session" class="py-12 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="gsap-reveal relative rounded-3xl overflow-hidden border border-indigo-200/70 bg-gradient-to-r from-indigo-50 via-blue-50 to-violet-50 shadow-[0_14px_35px_rgba(79,70,229,0.16)]">
            <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-indigo-200/40 rounded-full blur-3xl"></div>
            <div class="grid md:grid-cols-2 items-center gap-8 px-6 py-8 md:px-10 md:py-10 relative">
                <div class="order-2 md:order-1 relative">
                    <div class="rounded-2xl overflow-hidden border border-white/70 shadow-xl">
                        <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&q=80&w=1200" alt="Demo class preview" loading="lazy" class="w-full h-64 md:h-72 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/45 via-slate-900/10 to-transparent"></div>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 border border-indigo-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Risk Free Trial
                    </span>
                    <h3 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-3">Book Your <span class="text-indigo-600">10-Day Demo Session</span></h3>
                    <p class="text-slate-600 mb-6 max-w-lg">Experience our live classes, teaching quality, and practice system before enrollment. Get full confidence before your decision.</p>
                    <a href="<?php echo e(route('contact')); ?>" class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-[0_8px_22px_rgba(99,102,241,0.35)]">
                        Book Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-12 bg-slate-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="gsap-reveal relative rounded-3xl overflow-hidden border border-fuchsia-200/70 bg-gradient-to-r from-fuchsia-50 via-purple-50 to-pink-50 shadow-[0_14px_35px_rgba(168,85,247,0.16)]">
            <div class="absolute -top-24 -right-14 w-64 h-64 bg-fuchsia-200/40 rounded-full blur-3xl"></div>
            <div class="grid md:grid-cols-2 items-center gap-8 px-6 py-8 md:px-10 md:py-10 relative">
                <div>
                    <span class="inline-flex items-center gap-2 bg-fuchsia-100 text-fuchsia-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 border border-fuchsia-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Instant Support
                    </span>
                    <h3 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-3">Talk to Our <span class="text-fuchsia-600">Academic Experts</span></h3>
                    <p class="text-slate-600 mb-6 max-w-lg">Not sure which batch is best for your exam target? Connect with our counselors for a personalized learning roadmap.</p>
                    <a href="<?php echo e(route('contact')); ?>" class="inline-flex items-center gap-2 px-7 py-3 bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-bold rounded-xl transition-all shadow-[0_8px_22px_rgba(192,38,211,0.35)]">
                        Request Callback
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden border border-white/70 shadow-xl">
                        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=1200" alt="Expert mentoring support session" loading="lazy" class="w-full h-64 md:h-72 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/45 via-slate-900/10 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="relative py-20 bg-[linear-gradient(180deg,#ffffff_0%,#f8fbff_100%)] border-t border-gray-100 overflow-hidden">
    <div class="absolute top-0 right-0 w-[24rem] h-[24rem] bg-indigo-100/60 blur-3xl rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto mb-12 gsap-reveal">
            <span class="inline-flex items-center gap-2 text-[11px] font-black tracking-[0.14em] uppercase text-primary bg-primary/10 border border-primary/20 rounded-full px-3 py-1 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                Why Students Trust Topper's Hope
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight">Built For Serious Results</h2>
            <p class="text-slate-500 text-base md:text-lg">A complete ecosystem combining expert mentorship, high-quality content, and real-time performance analytics.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 gsap-stagger-parent">
            <?php
            $features = [
                ['icon'=>'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z','title'=>'Live Interactive Classes','desc'=>'Learn directly from expert educators with classroom-like engagement and doubt solving.','color'=>'from-rose-500 to-orange-500'],
                ['icon'=>'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Video Solutions','desc'=>'Detailed step-by-step explanations for assignments, tests, and practice sheets.','color'=>'from-blue-500 to-indigo-500'],
                ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Affordable Premium','desc'=>'Top-tier educational quality at a student-friendly fee structure.','color'=>'from-emerald-500 to-teal-500'],
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'Trusted Curriculum','desc'=>'Exam-focused content aligned with latest standards and official patterns.','color'=>'from-indigo-500 to-violet-500'],
                ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','title'=>'Performance Tracking','desc'=>'Track strengths, weak topics, and progress with smart performance insights.','color'=>'from-amber-500 to-orange-500'],
                ['icon'=>'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z','title'=>'24×7 Doubt Desk','desc'=>'Get academic support quickly whenever you are stuck during preparation.','color'=>'from-fuchsia-500 to-purple-500'],
            ];
            ?>

            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="gsap-stagger-child group rounded-2xl p-[1px] bg-gradient-to-br from-slate-100 via-indigo-100/70 to-sky-100/80 hover:from-indigo-200 hover:to-cyan-200 transition-all">
                <div class="h-full rounded-2xl bg-white/90 border border-white/80 backdrop-blur-sm p-6 shadow-[0_10px_25px_rgba(15,23,42,0.06)] group-hover:shadow-[0_16px_30px_rgba(79,70,229,0.15)] group-hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?php echo e($f['color']); ?> text-white flex items-center justify-center mb-4 shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?php echo e($f['icon']); ?>"></path></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 mb-2"><?php echo e($f['title']); ?></h3>
                    <p class="text-sm text-slate-500 leading-relaxed"><?php echo e($f['desc']); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section x-data="{
    paused: false,
    showModal: false,
    selectedReview: { name: '', period: '', rating: 0, feedback: '' },
    init() {
        const track = this.$refs.testimonialTrack;
        if (!track) return;
        this.$nextTick(() => {
            track.scrollLeft = track.scrollWidth / 2;
            const step = () => {
                if (!this.paused) {
                    track.scrollLeft -= 1.5;
                    if (track.scrollLeft <= 0) {
                        track.scrollLeft = track.scrollWidth / 2;
                    }
                }
                requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        });
    },
    openReview(name, period, rating, feedback) {
        this.selectedReview = { name, period, rating, feedback };
        this.showModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeReview() {
        this.showModal = false;
        this.paused = false;
        document.body.style.overflow = '';
    }
}" class="relative py-20 bg-[linear-gradient(180deg,#f9f7ff_0%,#f8fbff_100%)] border-t border-gray-100 overflow-hidden">
    <div class="absolute bottom-0 left-0 w-[22rem] h-[22rem] bg-purple-100/50 blur-3xl rounded-full translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 gsap-reveal">
            <div>
                <span class="inline-flex items-center gap-2 text-[11px] font-black tracking-[0.14em] uppercase text-indigo-700 bg-indigo-100 border border-indigo-200 rounded-full px-3 py-1 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Student Testimonials
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">What Students Say</h2>
                <p class="text-slate-500 mt-2">Real feedback from learners preparing for competitive exams across India.</p>
            </div>
            <div class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-full px-4 py-2 shadow-sm">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.286 3.957c.3.922-.755 1.688-1.54 1.118l-3.366-2.446a1 1 0 00-1.176 0l-3.366 2.446c-.785.57-1.84-.196-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.465 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path></svg>
                <?php echo e($testimonialAverageRating > 0 ? number_format($testimonialAverageRating, 1) : '0.0'); ?>/5 from <?php echo e(number_format($testimonialCount ?? 0)); ?> reviews
            </div>
        </div>

        <?php if(($testimonialCount ?? 0) > 0): ?>
        <div class="relative">
            <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-[#f9f7ff] to-transparent z-10"></div>
            <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-[#f8fbff] to-transparent z-10"></div>

            <div x-ref="testimonialTrack" class="flex gap-4 overflow-x-auto scroll-smooth py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <?php $__currentLoopData = [1,2]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $copy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $reviewerName = trim((string)($review->reviewer->name ?? ''));
                        $employeeName = $reviewerName !== '' ? $reviewerName : trim(($review->employee->first_name ?? '') . ' ' . ($review->employee->last_name ?? ''));
                        $employeeName = $employeeName !== '' ? $employeeName : 'Reviewer';
                        $courseName = $review->review_period ?: 'Topper\'s Hope Learner';
                        $rankOrScore = 'Rating ' . ((int) ($review->rating ?? 0)) . '/5';
                        $feedbackText = $review->feedback ?: 'Great learning experience with strong guidance and support.';
                        $isLongText = \Illuminate\Support\Str::length($feedbackText) > 120;
                        $initial = strtoupper(substr($employeeName, 0, 1));
                        $avatarPalette = ['bg-blue-100', 'bg-pink-100', 'bg-green-100', 'bg-purple-100', 'bg-amber-100', 'bg-cyan-100'];
                        $avatarClass = $avatarPalette[$loop->index % count($avatarPalette)];
                    ?>
                    <div class="shrink-0 w-[88%] sm:w-[72%] md:w-[48%] lg:w-[31%] gsap-stagger-child group rounded-2xl p-[1px] bg-gradient-to-br from-slate-100 via-indigo-100/60 to-violet-100/70 hover:from-indigo-200 hover:to-violet-200 transition-all" @mouseenter="paused = true" @mouseleave="paused = false" @mousedown="paused = true" @mouseup="paused = false">
                        <div class="h-full rounded-2xl bg-white/92 border border-white/80 backdrop-blur p-5 shadow-[0_10px_25px_rgba(15,23,42,0.06)] group-hover:shadow-[0_16px_32px_rgba(99,102,241,0.16)] group-hover:-translate-y-1 transition-all flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 <?php echo e($avatarClass); ?> rounded-full flex items-center justify-center font-black text-xs text-slate-700"><?php echo e($initial); ?></div>
                                    <div>
                                        <h4 class="font-black text-slate-900 text-sm"><?php echo e($employeeName); ?></h4>
                                        <p class="text-[11px] text-emerald-600 font-bold"><?php echo e($courseName); ?></p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100"><?php echo e($rankOrScore); ?></span>
                            </div>

                            <div>
                                <p class="text-[13px] text-slate-600 leading-relaxed border-l-4 border-primary pl-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    "<?php echo e($feedbackText); ?>"
                                </p>
                                <?php if($isLongText): ?>
                                    <button type="button" class="mt-1.5 text-[11px] font-bold text-primary hover:text-indigo-700" @click="paused = true; openReview(<?php echo \Illuminate\Support\Js::from($employeeName)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($courseName)->toHtml() ?>, <?php echo e((int)($review->rating ?? 0)); ?>, <?php echo \Illuminate\Support\Js::from($feedbackText)->toHtml() ?>)">
                                        Read more
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center gap-1">
                                <?php for($i=0;$i<5;$i++): ?>
                                    <svg class="w-4 h-4 <?php echo e($i < (int)($review->rating ?? 0) ? 'text-amber-500' : 'text-slate-300'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.196-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-12 rounded-2xl border border-dashed border-slate-200 bg-white/70">
            <p class="text-slate-500 font-semibold">No testimonials available yet.</p>
        </div>
        <?php endif; ?>
    </div>

    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-[120] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" @click.self="closeReview()" style="display:none;">
        <div x-transition class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-2xl p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-black text-slate-900" x-text="selectedReview.name"></h3>
                    <p class="text-xs font-bold text-emerald-600 mt-1" x-text="selectedReview.period"></p>
                </div>
                <button type="button" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600" @click="closeReview()">×</button>
            </div>
            <div class="mb-4 flex items-center gap-1">
                <template x-for="i in 5" :key="i">
                    <svg class="w-4 h-4" :class="i <= selectedReview.rating ? 'text-amber-500' : 'text-slate-300'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.196-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </template>
            </div>
            <p class="text-slate-600 leading-relaxed" x-text="selectedReview.feedback"></p>
        </div>
    </div>
</section>


<script>
(function() {
    gsap.registerPlugin(ScrollTrigger);

    // ---- Hero entrance (using a short timeline) ----
    const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    heroTl
        .fromTo('#hero-badge',   { opacity:0, y:20 }, { opacity:1, y:0, duration:.6 })
        .fromTo('#hero-title',   { opacity:0, y:40 }, { opacity:1, y:0, duration:.7 }, '-=.3')
        .fromTo('#hero-sub',     { opacity:0, y:30 }, { opacity:1, y:0, duration:.6 }, '-=.4')
        .fromTo('#hero-btns',    { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 }, '-=.3')
        .fromTo('#hero-social',  { opacity:0 },       { opacity:1, duration:.4 }, '-=.2')
        .fromTo('#hero-graphic', { opacity:0, scale:.92 }, { opacity:1, scale:1, duration:.8 }, '-=.6');

    // ---- Generic section reveal utility ----
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el,
            { opacity:0, y:50 },
            { opacity:1, y:0, duration:.8, ease:'power2.out',
              scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' }
            }
        );
    });

    // ---- Staggered grid children ----
    gsap.utils.toArray('.gsap-stagger-parent').forEach(parent => {
        const children = parent.querySelectorAll('.gsap-stagger-child');
        ScrollTrigger.create({
            trigger: parent,
            start: 'top 80%',
            onEnter: () => {
                gsap.fromTo(children,
                    { opacity:0, y:40, scale:.95 },
                    { opacity:1, y:0, scale:1, duration:.6, ease:'power2.out', stagger:.1 }
                );
            }
        });
    });
})();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/welcome.blade.php ENDPATH**/ ?>