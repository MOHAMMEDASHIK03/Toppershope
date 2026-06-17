@extends('layouts.public')

@section('title', 'Careers & Job Opportunities')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
@endpush

@section('content')

<!-- ===== PREMIUM HERO SECTION ===== -->
<section id="careers-hero" class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100">
    <!-- Fluid background glow spheres -->
    <div class="absolute -top-28 -left-16 w-80 h-80 rounded-full bg-blue-100/60 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 right-0 w-96 h-96 rounded-full bg-indigo-100/60 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center">
        <span id="hero-badge" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6 shadow-sm">
            <svg class="w-3.5 h-3.5 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            We are hiring!
        </span>
        
        <h1 id="hero-title" class="text-4xl sm:text-5xl md:text-7xl font-black text-slate-900 leading-tight mb-6">
            Build the Future of <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-indigo-600 to-violet-600">EdTech Mentorship</span>
        </h1>
        
        <p id="hero-sub" class="text-slate-600 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto mb-8 font-medium">
            Join our fully remote team of top-tier educators, designers, and software engineers dedicated to making elite exam prep accessible to everyone, everywhere.
        </p>

        <div id="hero-btns" class="flex flex-wrap gap-4 justify-center">
            <a href="#open-positions" class="px-8 py-4 bg-primary hover:bg-blue-700 text-white font-extrabold rounded-xl transition-all shadow-[0_8px_24px_rgba(27,42,255,0.3)]">
                Explore Open Roles &darr;
            </a>
            <a href="{{ route('about') }}" class="px-8 py-4 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-all">
                Learn About Our Culture
            </a>
        </div>
    </div>
</section>

<!-- ===== CORE VALUES & PERKS ===== -->
<section class="py-20 bg-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 gsap-reveal">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Why You'll Love Working With Us</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">We provide the environment, tools, and freedom you need to do the best work of your life.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 gsap-stagger-parent">
            <!-- Perk 1 -->
            <div class="gsap-stagger-child bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6 border border-indigo-100">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">100% Remote &amp; Flexible</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Work from anywhere in India. Manage your own hours as long as you deliver results and collaborate effectively.</p>
            </div>
            <!-- Perk 2 -->
            <div class="gsap-stagger-child bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-6 border border-violet-100">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Competitive Compensation</h3>
                <p class="text-slate-500 text-sm leading-relaxed">We respect top talent and pay competitive industry rates alongside comprehensive health benefits and annual bonuses.</p>
            </div>
            <!-- Perk 3 -->
            <div class="gsap-stagger-child bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 border border-emerald-100">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Continuous Learning</h3>
                <p class="text-slate-500 text-sm leading-relaxed">We support your personal development with generous budgets for books, professional courses, and tech gear.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== OPEN POSITIONS EXPLORER ===== -->
<section id="open-positions" class="py-24 bg-white" x-data="{ activeDept: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 gsap-reveal">
            <div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Current Job Openings</h2>
                <p class="text-slate-500 font-semibold mt-2">Filter vacancies and apply immediately to start the review process.</p>
            </div>
            
            <!-- Quick Category Filter list -->
            @if($jobs->isNotEmpty())
            @php
                $depts = $jobs->pluck('department')->unique('id')->filter();
            @endphp
            <div class="flex flex-wrap gap-2">
                <button @click="activeDept = 'all'" :class="activeDept === 'all' ? 'bg-primary text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all">
                    All Departments ({{ $jobs->count() }})
                </button>
                @foreach($depts as $dept)
                <button @click="activeDept = 'dept-{{ $dept->id }}'" :class="activeDept === 'dept-{{ $dept->id }}' ? 'bg-primary text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all">
                    {{ $dept->name }}
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Jobs Listing Grid -->
        <div class="space-y-6">
            @forelse($jobs as $job)
            <div x-show="activeDept === 'all' || activeDept === 'dept-{{ $job->department_id }}'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="group relative bg-white border border-slate-200 hover:border-slate-300 rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <!-- Main detail column -->
                <div class="space-y-3 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="px-3 py-1 bg-blue-50 border border-blue-100 text-primary rounded-full text-[11px] font-black uppercase tracking-wider">
                            {{ $job->department->name ?? 'General' }}
                        </span>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-black uppercase tracking-wider capitalize">
                            {{ $job->employment_type }}
                        </span>
                        @if($job->vacancies > 1)
                        <span class="px-3 py-1 bg-orange-50 border border-orange-100 text-orange-600 rounded-full text-[11px] font-black uppercase tracking-wider">
                            {{ $job->vacancies }} Openings
                        </span>
                        @endif
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-primary transition-colors">
                        {{ $job->title }}
                    </h3>
                    
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-slate-500 font-medium">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $job->location ?? 'Remote' }}
                        </span>
                        @if($job->salary_range)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $job->salary_range }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- CTA Action Button -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('careers.apply', $job) }}" class="w-full md:w-auto text-center px-6 py-3.5 bg-slate-900 group-hover:bg-primary text-white font-extrabold text-xs uppercase tracking-widest rounded-xl transition-all shadow-md group-hover:shadow-lg group-hover:shadow-primary/20 flex items-center justify-center gap-2">
                        View &amp; Apply
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            @empty
            <!-- Gorgeous Animated Empty State -->
            <div class="border-2 border-dashed border-slate-200 bg-slate-50/50 rounded-3xl p-12 text-center max-w-xl mx-auto flex flex-col items-center justify-center shadow-inner">
                <div class="w-20 h-20 bg-blue-50 border border-blue-100 text-primary rounded-full flex items-center justify-center mb-6 shadow-sm animate-bounce">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2">No Openings Right Now</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">
                    Our team is currently fully staffed, but we are always on the lookout for elite talent. Connect with us via social channels or email to share your resume for future opportunities.
                </p>
                <a href="{{ route('contact') }}" class="px-6 py-3 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-all shadow-sm">
                    Get in Touch
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<script>
(function() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // Hero timeline
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.fromTo('#hero-badge',   { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 })
      .fromTo('#hero-title',   { opacity:0, y:40 }, { opacity:1, y:0, duration:.7 }, '-=.3')
      .fromTo('#hero-sub',     { opacity:0, y:30 }, { opacity:1, y:0, duration:.6 }, '-=.4')
      .fromTo('#hero-btns',    { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 }, '-=.3');

    // Scroll reveals
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el, { opacity:0, y:35 }, { opacity:1, y:0, duration:.8, ease:'power2.out',
            scrollTrigger:{ trigger:el, start:'top 85%' }
        });
    });

    gsap.utils.toArray('.gsap-stagger-parent').forEach(p => {
        const kids = p.querySelectorAll('.gsap-stagger-child');
        ScrollTrigger.create({ trigger:p, start:'top 80%', onEnter: () =>
            gsap.fromTo(kids, { opacity:0, y:30 }, { opacity:1, y:0, duration:.6, stagger:.12, ease:'power2.out' })
        });
    });
})();
</script>
@endsection
