<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ Str::limit($campaign->subtitle ?? $campaign->description, 160) }}">
    <title>{{ $campaign->title }} — Topper's Hope</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, body { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; margin:0; padding:0; }
        :root {
            --c-primary:   {{ $campaign->primary_color   ?: '#1B2AFF' }};
            --c-secondary: {{ $campaign->secondary_color ?: '#7B61FF' }};
            --c-accent:    {{ $campaign->accent_color    ?: '#00D2FF' }};
            --c-bg:        {{ $campaign->bg_color        ?: '#0a0a1a' }};
            --c-text:      {{ $campaign->text_color      ?: '#ffffff' }};
        }
        body { background: var(--c-bg); color: var(--c-text); min-height: 100vh; }

        /* Blob background */
        @keyframes blob { 0%,100%{transform:translate(0,0) scale(1);} 33%{transform:translate(30px,-50px) scale(1.1);} 66%{transform:translate(-20px,20px) scale(0.9);} }
        .blob  { animation: blob  7s infinite; }
        .blob2 { animation: blob  9s infinite reverse; animation-delay:2s; }

        /* Glass card */
        .glass { background: rgba(255,255,255,.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,.1); }

        /* Section divider */
        .section-divider { border-top: 1px solid rgba(255,255,255,.07); }

        /* Form inputs */
        .form-input {
            width:100%; padding:.75rem 1rem;
            background:rgba(255,255,255,.07);
            border:1.5px solid rgba(255,255,255,.15);
            border-radius:.75rem; color:var(--c-text);
            font-size:.9rem; font-family:inherit; outline:none;
            transition:border-color .2s,background .2s;
        }
        .form-input::placeholder { color:rgba(255,255,255,.35); }
        .form-input:focus { border-color:var(--c-accent); background:rgba(255,255,255,.1); }

        /* Glow effect */
        .glow { box-shadow: 0 0 40px color-mix(in srgb, var(--c-primary) 35%, transparent); }

        /* FAQ accordion */
        details summary::-webkit-details-marker { display:none; }

        /* Sticky mobile bar */
        @media(max-width:767px) {
            .sticky-cta { position:fixed; bottom:0; left:0; right:0; z-index:40;
                background:color-mix(in srgb, var(--c-bg) 92%, transparent);
                backdrop-filter:blur(12px);
                border-top:1px solid rgba(255,255,255,.1);
                padding:.875rem 1rem; }
        }
        [x-cloak] { display:none!important; }
    </style>
</head>
<body x-data="{ showForm:false, formType:'interest', popupDismissed:false }">

    {{-- ══════════════════════════════════════════════
         POPUP AD
    ══════════════════════════════════════════════ --}}
    @if($popup && $popup->image)
    <div id="popup-overlay" style="display:none"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="relative max-w-sm w-full glass rounded-3xl overflow-hidden shadow-2xl">
            <button onclick="document.getElementById('popup-overlay').style.display='none'"
                    class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/50 text-white flex items-center justify-center text-xl font-bold hover:bg-black/70 transition">×</button>
            @if($popup->link_url)
                <a href="{{ $popup->link_url }}" target="_blank" onclick="document.getElementById('popup-overlay').style.display='none'">
                    <img src="{{ Storage::url($popup->image) }}" alt="Offer" class="w-full object-cover">
                </a>
                @if($popup->link_text)
                <div class="p-4 text-center">
                    <a href="{{ $popup->link_url }}" target="_blank"
                       class="inline-block px-6 py-2.5 text-white font-black rounded-xl transition hover:opacity-90 glow text-sm"
                       style="background:var(--c-primary)">{{ $popup->link_text }}</a>
                </div>
                @endif
            @else
                <img src="{{ Storage::url($popup->image) }}" alt="Offer" class="w-full object-cover">
            @endif
        </div>
    </div>
    <script>setTimeout(() => { document.getElementById('popup-overlay').style.display='flex'; }, {{ ($popup->delay_seconds ?? 3) * 1000 }});</script>
    @endif

    {{-- ══════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden py-20 sm:py-28">
        <div class="blob absolute top-1/4 -left-40 w-96 h-96 rounded-full opacity-20" style="background:var(--c-primary);filter:blur(90px)"></div>
        <div class="blob2 absolute bottom-1/4 -right-32 w-80 h-80 rounded-full opacity-20" style="background:var(--c-secondary);filter:blur(90px)"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left --}}
            <div>
                @if($campaign->badge_text)
                <span class="inline-block text-xs font-black uppercase tracking-widest mb-5 px-4 py-1.5 rounded-full glass" style="color:var(--c-accent)">
                    ✦ {{ $campaign->badge_text }}
                </span>
                @endif

                <h1 class="text-4xl sm:text-5xl font-black leading-tight mb-3">{{ $campaign->title }}</h1>

                @if($campaign->course_name)
                <p class="text-2xl sm:text-3xl font-black mb-4" style="color:var(--c-accent)">{{ $campaign->course_name }}</p>
                @endif

                @if($campaign->subtitle)
                <p class="text-base opacity-70 mb-8 leading-relaxed">{{ $campaign->subtitle }}</p>
                @endif

                {{-- Pricing --}}
                @if($campaign->fee || $campaign->original_fee)
                <div class="flex items-baseline gap-4 mb-8 flex-wrap">
                    @if($campaign->fee)
                    <p class="text-4xl font-black" style="color:var(--c-accent)">₹{{ number_format($campaign->fee) }}</p>
                    @endif
                    @if($campaign->original_fee)
                    <p class="text-xl opacity-40 line-through">₹{{ number_format($campaign->original_fee) }}</p>
                    @endif
                    @if($campaign->discountPercent())
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm font-black rounded-full border border-green-500/30">
                        {{ $campaign->discountPercent() }}% OFF
                    </span>
                    @endif
                </div>
                @endif

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 mb-5">
                    <button @click="formType='enrol'; showForm=true; $nextTick(()=>document.getElementById('lead-form').scrollIntoView({behavior:'smooth'}))"
                            class="px-8 py-4 text-white font-black rounded-2xl transition glow hover:opacity-90 shadow-xl text-sm sm:text-base"
                            style="background:var(--c-primary)">
                        {{ $campaign->cta_button_text ?: 'Enrol Now' }} →
                    </button>
                    <button @click="formType='interest'; showForm=true; $nextTick(()=>document.getElementById('lead-form').scrollIntoView({behavior:'smooth'}))"
                            class="px-8 py-4 glass font-black rounded-2xl hover:bg-white/10 transition text-sm sm:text-base"
                            style="border:2px solid var(--c-primary)">
                        {{ $campaign->interest_button_text ?: "I'm Interested" }}
                    </button>
                </div>

                @if($campaign->brochure_pdf)
                <a href="{{ Storage::url($campaign->brochure_pdf) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-sm opacity-50 hover:opacity-100 transition">
                    ↓ Download Brochure PDF
                </a>
                @endif
            </div>

            {{-- Right: hero image + features --}}
            <div class="space-y-5">
                @if($campaign->hero_image)
                <div class="relative rounded-3xl overflow-hidden glass">
                    <img src="{{ Storage::url($campaign->hero_image) }}" alt="{{ $campaign->title }}"
                         class="w-full object-cover" style="max-height:320px;object-fit:cover;">
                    <div class="absolute inset-0" style="background:linear-gradient(to top,var(--c-bg) 0%,transparent 40%)"></div>
                </div>
                @endif

                @if(!empty($campaign->features))
                <div class="glass rounded-3xl p-6">
                    <h3 class="font-black text-sm mb-4 uppercase tracking-wide" style="color:var(--c-accent)">What's Included</h3>
                    <ul class="space-y-2.5">
                        @foreach($campaign->features as $feature)
                        <li class="flex items-start gap-3 text-sm opacity-90">
                            <span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-xs font-black text-white"
                                  style="background:var(--c-primary)">✓</span>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════
         STATS STRIP
    ══════════════════════════════════════════════ --}}
    @if(!empty($campaign->stats))
    <section class="section-divider py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-8">
            <div class="glass rounded-3xl px-6 py-8 grid grid-cols-2 sm:grid-cols-{{ min(count($campaign->stats), 4) }} gap-6 text-center">
                @foreach($campaign->stats as $stat)
                @if(!empty($stat['value']))
                <div>
                    <p class="text-3xl sm:text-4xl font-black mb-1" style="color:var(--c-accent)">{{ $stat['value'] }}</p>
                    <p class="text-xs opacity-60 font-semibold uppercase tracking-wide">{{ $stat['label'] }}</p>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════
         DESCRIPTION
    ══════════════════════════════════════════════ --}}
    @if($campaign->description)
    <section class="section-divider py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <h2 class="text-2xl font-black mb-6" style="color:var(--c-accent)">About This Course</h2>
            <div class="opacity-80 leading-relaxed text-base whitespace-pre-line">{{ $campaign->description }}</div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════
         FACULTY CARD
    ══════════════════════════════════════════════ --}}
    @if($campaign->faculty_name)
    <section class="section-divider py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <h2 class="text-2xl font-black mb-8 text-center" style="color:var(--c-accent)">Meet Your Mentor</h2>
            <div class="glass rounded-3xl p-8 flex flex-col sm:flex-row items-center sm:items-start gap-8">
                {{-- Photo --}}
                <div class="shrink-0">
                    @if($campaign->faculty_photo)
                    <img src="{{ Storage::url($campaign->faculty_photo) }}" alt="{{ $campaign->faculty_name }}"
                         class="w-28 h-28 rounded-2xl object-cover border-4"
                         style="border-color:var(--c-primary)">
                    @else
                    <div class="w-28 h-28 rounded-2xl flex items-center justify-center text-4xl font-black text-white"
                         style="background:linear-gradient(135deg,var(--c-primary),var(--c-secondary))">
                        {{ strtoupper(substr($campaign->faculty_name, 0, 1)) }}
                    </div>
                    @endif
                </div>
                {{-- Info --}}
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-2xl font-black mb-1">{{ $campaign->faculty_name }}</h3>
                    @if($campaign->faculty_title)
                    <p class="text-sm opacity-60 mb-3 font-semibold">{{ $campaign->faculty_title }}</p>
                    @endif
                    @if($campaign->faculty_experience)
                    <span class="inline-block px-4 py-1.5 text-xs font-black rounded-full mb-4"
                          style="background:color-mix(in srgb,var(--c-primary) 20%,transparent);color:var(--c-accent);border:1px solid color-mix(in srgb,var(--c-primary) 30%,transparent)">
                        {{ $campaign->faculty_experience }}
                    </span>
                    @endif
                    @if($campaign->faculty_bio)
                    <p class="text-sm opacity-70 leading-relaxed">{{ $campaign->faculty_bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════
         TESTIMONIALS
    ══════════════════════════════════════════════ --}}
    @php $testimonials = collect($campaign->testimonials ?? [])->filter(fn($t) => !empty($t['name'])); @endphp
    @if($testimonials->isNotEmpty())
    <section class="section-divider py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-8">
            <h2 class="text-2xl font-black mb-8 text-center" style="color:var(--c-accent)">What Our Students Say</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($testimonials as $t)
                <div class="glass rounded-3xl p-6 flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center font-black text-white text-lg shrink-0"
                             style="background:linear-gradient(135deg,var(--c-primary),var(--c-secondary))">
                            {{ strtoupper(substr($t['name'] ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-black text-sm">{{ $t['name'] ?? '' }}</p>
                            @if(!empty($t['course']))<p class="text-xs opacity-50">{{ $t['course'] }}</p>@endif
                        </div>
                        @if(!empty($t['rank']))
                        <span class="ml-auto px-2.5 py-1 text-xs font-black rounded-full whitespace-nowrap"
                              style="background:color-mix(in srgb,var(--c-accent) 15%,transparent);color:var(--c-accent)">
                            {{ $t['rank'] }}
                        </span>
                        @endif
                    </div>
                    @if(!empty($t['quote']))
                    <p class="text-sm opacity-75 leading-relaxed border-l-2 pl-4 italic"
                       style="border-color:var(--c-primary)">"{{ $t['quote'] }}"</p>
                    @endif
                    <div class="flex gap-0.5" style="color:var(--c-accent)">★ ★ ★ ★ ★</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════
         LEAD FORM
    ══════════════════════════════════════════════ --}}
    <section id="lead-form" class="section-divider py-20">
        <div class="max-w-2xl mx-auto px-4 sm:px-8">
            @if(session('lead_success'))
            <div class="glass rounded-3xl p-10 text-center mb-8">
                <p class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center">
                    <svg class="w-8 h-8" style="color:var(--c-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                </p>
                <h3 class="text-xl font-black mb-2" style="color:var(--c-accent)">
                    {{ session('lead_type') === 'enrol' ? 'Enrolment Request Received!' : "We've noted your interest!" }}
                </h3>
                <p class="opacity-60 text-sm">Our team will reach out to you shortly. Stay tuned!</p>
            </div>
            @endif

            <div class="glass rounded-3xl p-8">
                <div x-show="!showForm" class="text-center">
                    <h2 class="text-2xl font-black mb-3">Ready to Start?</h2>
                    <p class="opacity-60 mb-8 text-sm">Choose how you'd like to proceed:</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button @click="formType='enrol'; showForm=true"
                                class="px-8 py-4 text-white font-black rounded-2xl glow transition hover:opacity-90"
                                style="background:var(--c-primary)">{{ $campaign->cta_button_text ?: 'Enrol Now' }}</button>
                        <button @click="formType='interest'; showForm=true"
                                class="px-8 py-4 glass font-black rounded-2xl hover:bg-white/10 transition"
                                style="border:2px solid var(--c-primary)">{{ $campaign->interest_button_text ?: "I'm Interested" }}</button>
                    </div>
                </div>

                <div x-show="showForm">
                    @if(!session('lead_success'))
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-black">
                            <span x-show="formType === 'enrol'">Enrolment Form</span>
                            <span x-show="formType === 'interest'">Express Interest</span>
                        </h2>
                        <button @click="showForm=false" class="text-xs opacity-40 hover:opacity-100 transition font-semibold">← Back</button>
                    </div>

                    @if($errors->any())
                    <div class="p-4 mb-4 rounded-xl text-sm" style="background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#fca5a5">
                        <ul>@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('campaign.lead', $campaign->slug) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="enquiry_type" :value="formType">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold opacity-50 mb-1.5 uppercase tracking-wide">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name" class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold opacity-50 mb-1.5 uppercase tracking-wide">Phone *</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+91 XXXXX XXXXX" class="form-input">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold opacity-50 mb-1.5 uppercase tracking-wide">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold opacity-50 mb-1.5 uppercase tracking-wide">City</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="Your city" class="form-input">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold opacity-50 mb-1.5 uppercase tracking-wide">Message <span class="opacity-60 font-normal normal-case">(optional)</span></label>
                            <textarea name="message" rows="3" placeholder="Any questions or details..." class="form-input" style="resize:none">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-4 text-white font-black rounded-2xl transition glow hover:opacity-90"
                                style="background:var(--c-primary)">
                            <span x-show="formType === 'enrol'">Submit Enrolment Request</span>
                            <span x-show="formType === 'interest'">Send My Interest</span>
                        </button>
                        <p class="text-xs opacity-30 text-center">Your information is confidential and will only be used to contact you about this course.</p>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════
         FAQs
    ══════════════════════════════════════════════ --}}
    @php $faqs = collect($campaign->faqs ?? [])->filter(fn($f) => !empty($f['question'])); @endphp
    @if($faqs->isNotEmpty())
    <section class="section-divider py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-8">
            <h2 class="text-2xl font-black mb-8 text-center" style="color:var(--c-accent)">Frequently Asked Questions</h2>
            <div class="space-y-3">
                @foreach($faqs as $faq)
                <details class="glass rounded-2xl overflow-hidden group" x-data="{ open: false }">
                    <summary @click="open = !open"
                             class="flex items-center justify-between px-6 py-5 cursor-pointer list-none font-bold text-sm hover:bg-white/5 transition">
                        <span>{{ $faq['question'] }}</span>
                        <span class="ml-4 shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-transform"
                              style="background:color-mix(in srgb,var(--c-primary) 25%,transparent)"
                              :class="open ? 'rotate-45' : ''">+</span>
                    </summary>
                    <div class="px-6 pb-5 text-sm opacity-70 leading-relaxed border-t border-white/5 pt-4">
                        {{ $faq['answer'] }}
                    </div>
                </details>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════ --}}
    <footer class="section-divider py-8 text-center text-xs opacity-30">
        © {{ date('Y') }} Topper's Hope. All rights reserved. &nbsp;|&nbsp;
        <a href="{{ url('/privacy') }}" class="hover:opacity-70 ml-1">Privacy</a> &nbsp;|&nbsp;
        <a href="{{ url('/terms') }}" class="hover:opacity-70 ml-1">Terms</a>
    </footer>

    {{-- ══════════════════════════════════════════════
         STICKY MOBILE CTA
    ══════════════════════════════════════════════ --}}
    <div class="sticky-cta flex gap-3 md:hidden">
        <button @click="formType='enrol'; showForm=true; $nextTick(()=>document.getElementById('lead-form').scrollIntoView({behavior:'smooth'}))"
                class="flex-1 py-3 text-white font-black rounded-xl text-sm"
                style="background:var(--c-primary)">{{ $campaign->cta_button_text ?: 'Enrol Now' }}</button>
        <button @click="formType='interest'; showForm=true; $nextTick(()=>document.getElementById('lead-form').scrollIntoView({behavior:'smooth'}))"
                class="flex-1 py-3 glass font-black rounded-xl text-sm"
                style="border:2px solid var(--c-primary)">Interested</button>
    </div>

    @if($errors->any())
    <script>document.addEventListener('alpine:init', () => { window._showForm = true; });</script>
    @endif
</body>
</html>
