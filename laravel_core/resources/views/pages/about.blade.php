@extends('layouts.public')

@section('title', 'About Us')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
@endpush

@section('content')

<!-- ===== PREMIUM HERO ===== -->
<section id="about-hero" class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100">
    <div class="absolute -top-28 -left-16 w-72 h-72 rounded-full bg-blue-100/70 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 right-0 w-80 h-80 rounded-full bg-violet-100/70 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <span id="hero-badge" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9l9-5 9 5-9 5-9-5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 10.5v5l7 4 7-4v-5"></path></svg>
                Our Story &amp; Mission
            </span>
            <h1 id="hero-title" class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-5">
                About
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-violet-600">Topper's Hope</span>
            </h1>
            <p id="hero-sub" class="text-slate-600 text-lg leading-relaxed max-w-xl">
                A high-performance learning ecosystem built by educators, mentors, and engineers to make elite exam preparation accessible, structured, and measurable.
            </p>
            <div id="hero-btns" class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="px-7 py-3 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-[0_8px_24px_rgba(37,99,235,0.28)]">Start Learning</a>
                <a href="{{ route('contact') }}" class="px-7 py-3 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-all">Contact Team</a>
            </div>
        </div>

        <div class="md:w-1/2 w-full" id="hero-visual">
            <div class="grid grid-cols-2 gap-4">
                @foreach([['Mission','Make top-quality mentoring available at scale.'],['Vision','Help every student compete with confidence.'],['Method','Concept clarity, practice, and feedback loops.'],['Outcome','Consistent rank improvement across exams.']] as $card)
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all hero-card">
                    <div class="w-10 h-10 mb-3 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">{{ $card[0] }}</h4>
                    <p class="text-slate-500 text-xs leading-relaxed">{{ $card[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ===== INTRO SECTION ===== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <!-- Left: Story block -->
            <div class="lg:col-span-8 gsap-reveal">
                <!-- Accent border heading -->
                <div class="flex items-stretch gap-4 mb-8">
                    <div class="w-1.5 rounded-full bg-gradient-to-b from-primary to-purple-500 shrink-0"></div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900">
                        <span class="text-primary">How it</span> all started
                    </h2>
                </div>

                <div class="space-y-6 text-gray-600 text-lg leading-8 max-w-3xl">
                    <p>
                        <strong class="text-gray-900">Topper's Hope</strong> was born out of a simple realization — the best coaching in India was locked behind city-centric, overpriced classrooms. Brilliant students in smaller cities were working just as hard but had unequal access to quality mentorship.
                    </p>
                    <p>
                        Our founders — a group of <strong class="text-primary">IIT alumni, educators, and product engineers</strong> — decided to build something different. Not just another app with recorded videos, but a live, structured, community-driven learning platform that replicates the best of a physical institute, digitally.
                    </p>
                    <p>
                        Today, Topper's Hope serves students across <strong class="text-gray-900">500+ cities</strong> from Class 8 all the way to UPSC aspirants, connecting them with India's finest educators through beautifully built technology.
                    </p>
                </div>

                <!-- Quick stats row -->
                <div class="mt-10 grid grid-cols-3 gap-6">
                    @foreach([['val'=>'1M+','label'=>'Students Taught'],['val'=>'500+','label'=>'Cities Reached'],['val'=>'98%','label'=>'Student Satisfaction']] as $stat)
                    <div class="text-center bg-gradient-to-b from-blue-50 to-white border border-blue-100 rounded-2xl py-5 px-4">
                        <p class="text-2xl font-black text-primary">{{ $stat['val'] }}</p>
                        <p class="text-xs text-gray-500 font-semibold mt-1 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Sticky info card -->
            <div class="lg:col-span-4 gsap-reveal lg:sticky lg:top-28">
                <div class="relative bg-gradient-to-br from-primary via-[#2233ff] to-[#7B61FF] rounded-3xl p-8 text-white shadow-2xl overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5 border border-white/10"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white/5 border border-white/10"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-2">Quick Facts</h3>
                        <p class="text-blue-200 text-sm mb-6 leading-relaxed">Everything you should know about us at a glance.</p>
                        <div class="w-full h-px bg-white/20 mb-6"></div>
                        <ul class="space-y-4 text-sm mb-6">
                            @foreach([['Founded','2022'],['Headquarters','Tamil Nadu, India'],['Programs','JEE, NEET, UPSC, SSC, NDA & more'],['Faculty','50+ Expert Educators'],['Students','1 Million+ Enrolled'],['Top Results','AIR under 100 achieved']] as $f)
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-lg bg-white/15 border border-white/20 shrink-0 mt-0.5 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                                <div>
                                    <span class="text-blue-200 text-xs uppercase tracking-wide">{{ $f[0] }}</span>
                                    <p class="font-bold text-white">{{ $f[1] }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <div class="w-full h-px bg-white/20 mb-6"></div>
                        <a href="{{ route('contact') }}" class="block text-center py-3 bg-white text-primary font-black rounded-xl hover:bg-blue-50 transition-all">Get in Touch →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== NUMBERS (dark bar) ===== -->
<section class="py-16 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([['val'=>'1M+','label'=>'Students Taught'],['val'=>'500+','label'=>'Cities Reached'],['val'=>'50+','label'=>'Expert Faculty'],['val'=>'98%','label'=>'Success Rate']] as $s)
            <div class="gsap-reveal">
                <p class="text-4xl md:text-5xl font-black text-white mb-2">{{ $s['val'] }}</p>
                <p class="text-gray-400 font-semibold text-sm uppercase tracking-wider">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== TEAM ===== -->
<section class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 gsap-reveal">
            <div class="flex items-center justify-center gap-4 mb-4">
                <div class="h-0.5 w-16 bg-gradient-to-r from-transparent to-primary rounded"></div>
                <h2 class="text-3xl font-black text-gray-900">Meet our <span class="text-primary">Leadership Team</span></h2>
                <div class="h-0.5 w-16 bg-gradient-to-l from-transparent to-primary rounded"></div>
            </div>
            <p class="text-gray-500 max-w-xl mx-auto">IIT / AIIMS graduates + seasoned EdTech builders united by a love for teaching.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 gsap-stagger-parent">
@php $team = [['name'=>'Arjun Mehta','role'=>'Founder & CEO','bg'=>'bg-blue-100','letter'=>'AM','desc'=>'IIT Bombay, 7 yrs EdTech'],['name'=>'Priya Sharma','role'=>'Head of Academics','bg'=>'bg-red-100','letter'=>'PS','desc'=>'AIIMS Delhi, NEET AIR 12'],['name'=>'Rohan Das','role'=>'CTO & Product','bg'=>'bg-purple-100','letter'=>'RD','desc'=>'IIT Delhi, Ex-Google Eng'],['name'=>'Anika Singh','role'=>'Head of Faculty','bg'=>'bg-green-100','letter'=>'AS','desc'=>'IIT KGP, 10+ yrs teaching']]; @endphp
            @foreach($team as $person)
            <div class="gsap-stagger-child group text-center">
                <div class="relative mx-auto mb-5 w-24 h-24 {{ $person['bg'] }} rounded-3xl flex items-center justify-center text-2xl font-black text-gray-700 shadow-sm group-hover:shadow-lg group-hover:-translate-y-2 transition-all">
                    {{ $person['letter'] }}
                </div>
                <h3 class="font-black text-gray-900 text-lg">{{ $person['name'] }}</h3>
                <p class="text-primary font-bold text-sm">{{ $person['role'] }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ $person['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== VALUES ===== -->
<section class="py-20 bg-slate-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 gsap-reveal">
            <h2 class="text-3xl font-black text-gray-900 mb-3">Our <span class="text-primary">Core Values</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 gsap-stagger-parent">
            @php $values = [['title'=>'Scientific Approach','desc'=>'Every curriculum decision is data-driven, backed by research and student performance analytics.'],['title'=>'Student First','desc'=>'Every feature, class, and decision — we ask: does this help the student get a better rank?'],['title'=>'Radical Transparency','desc'=>'We publish results openly. Raw numbers, student outcomes — no cherry-picked testimonials.'],['title'=>'Continuous Improvement','desc'=>'Our faculty iterates weekly on student feedback and analytics. No stale content, ever.'],['title'=>'Trust & Safety','desc'=>'Academic integrity, content security, and student data privacy are non-negotiable foundations.'],['title'=>'Ambition','desc'=>'We set the bar high for ourselves and our students. Mediocrity in teaching is the one thing we do not tolerate.']]; @endphp
            @foreach($values as $v)
            <div class="gsap-stagger-child bg-white p-7 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <span class="w-11 h-11 mb-4 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                </span>
                <h3 class="font-black text-gray-900 mb-2">{{ $v['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<section class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 text-center gsap-reveal">
        <h2 class="text-3xl font-black text-gray-900 mb-4">Ready to join the <span class="text-primary">Topper's Hope</span> family?</h2>
        <p class="text-gray-500 mb-8">Start with a free 10-day demo session and experience the difference.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-10 py-4 bg-primary hover:bg-blue-700 text-white font-black rounded-xl transition-all shadow-[0_4px_14px_rgba(27,42,255,0.39)]">Start Free Today →</a>
            <a href="{{ route('faq') }}" class="px-10 py-4 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all">Read FAQs</a>
        </div>
    </div>
</section>

<script>
(function() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // Hero entrance
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.fromTo('#hero-badge',   { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 })
      .fromTo('#hero-title',   { opacity:0, y:40 }, { opacity:1, y:0, duration:.7 }, '-=.3')
      .fromTo('#hero-sub',     { opacity:0, y:30 }, { opacity:1, y:0, duration:.6 }, '-=.4')
      .fromTo('#hero-btns',    { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 }, '-=.3')
      .fromTo('.hero-card',    { opacity:0, y:30, scale:.9 }, { opacity:1, y:0, scale:1, duration:.6, stagger:.12 }, '-=.5');

    // Float effect on hero cards
    document.querySelectorAll('.hero-card').forEach((card, i) => {
        gsap.to(card, { y: i % 2 === 0 ? -10 : 10, duration: 2.5 + i * 0.3, repeat:-1, yoyo:true, ease:'sine.inOut' });
    });

    // Scroll reveals
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el, { opacity:0, y:40 }, { opacity:1, y:0, duration:.8, ease:'power2.out',
            scrollTrigger:{ trigger:el, start:'top 85%' }
        });
    });

    gsap.utils.toArray('.gsap-stagger-parent').forEach(p => {
        const kids = p.querySelectorAll('.gsap-stagger-child');
        ScrollTrigger.create({ trigger:p, start:'top 80%', onEnter: () =>
            gsap.fromTo(kids, { opacity:0, y:30 }, { opacity:1, y:0, duration:.55, stagger:.1, ease:'power2.out' })
        });
    });
})();
</script>
@endsection
