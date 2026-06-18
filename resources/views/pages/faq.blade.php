@extends('layouts.public')

@section('title', 'Frequently Asked Questions')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
@endpush

@section('content')

<!-- ===== PREMIUM HERO ===== -->
<section id="faq-hero" class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100">
    <div class="absolute -top-28 left-1/4 w-72 h-72 rounded-full bg-primary-100/70 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-28 right-1/4 w-80 h-80 rounded-full bg-violet-100/60 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <span id="hero-badge" class="inline-flex items-center bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6">Support Center</span>
            <h1 id="hero-title" class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-5">
                Frequently Asked
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-600">Questions</span>
            </h1>
            <p id="hero-sub" class="text-slate-600 text-lg leading-relaxed max-w-xl">
                Clear answers for admissions, classes, plans, payments, tests, and account support.
            </p>
            <div id="hero-search" class="mt-8 flex items-center gap-3 bg-white border border-slate-200 rounded-2xl px-5 py-3.5 max-w-md shadow-sm">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search your question..." class="bg-transparent text-slate-700 placeholder-slate-400 outline-none text-sm w-full" id="faq-search-input">
            </div>
        </div>

        <div class="md:w-1/2 flex justify-center items-center" id="hero-3d">
            <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-lg">
                <div class="grid grid-cols-1 gap-3">
                    @foreach(['How do I choose the right course?', 'Can I switch batches after joining?', 'Are classes recorded for revision?'] as $q)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">{{ $q }}</div>
                    @endforeach
                </div>
                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=900" alt="FAQ visual"
                     class="hidden"
                     id="faq-3d-img">
            </div>
        </div>
    </div>
</section>


<!-- ===== INTRO SECTION — "Asked Questions" ===== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <!-- Left: Intro text block -->
            <div class="lg:col-span-8 gsap-reveal">
                <!-- Accent bordered heading -->
                <div class="flex items-stretch gap-4 mb-8">
                    <div class="w-1.5 rounded-full bg-gradient-to-b from-primary to-primary-500 shrink-0"></div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900">
                        <span class="text-primary">Asked</span> Questions
                    </h2>
                </div>

                <div class="space-y-6 text-gray-600 text-lg leading-8 max-w-3xl">
                    <p>
                        Welcome to the <strong class="text-gray-900">Topper's Hope FAQs</strong> page — your go-to resource for answers to common queries. We understand that embarking on your <strong class="text-primary">NEET, JEE, UPSC</strong>, or any other competitive exam journey can raise many questions, and we're here to provide complete clarity and support.
                    </p>
                    <p>
                        Our FAQs page is designed to address the most frequently asked questions, ensuring that you have the information you need for a successful learning experience. Whether you're unsure about the registration process, account settings, or payment methods — this section is here to assist you. Find answers regarding how to sign up, update your profile, or make payments securely.
                    </p>
                    <p>
                        We're dedicated to making your journey with <strong class="text-gray-900">Topper's Hope</strong> as hassle-free as possible, and our FAQs are a great starting point. If you can't find what you're looking for below, our support team is always just a message away.
                    </p>
                </div>

                <!-- Quick stats row -->
                <div class="mt-10 grid grid-cols-3 gap-6">
                    @foreach([['val'=>'15+','label'=>'Questions Answered'],['val'=>'< 2 hrs','label'=>'Avg. Response Time'],['val'=>'24×7','label'=>'Support Available']] as $stat)
                    <div class="text-center bg-gradient-to-b from-primary-50 to-white border border-primary-100 rounded-2xl py-5 px-4">
                        <p class="text-2xl font-black text-primary">{{ $stat['val'] }}</p>
                        <p class="text-xs text-gray-500 font-semibold mt-1 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Contact Info Card -->
            <div class="lg:col-span-4 gsap-reveal lg:sticky lg:top-28" id="contact-card">
                <div class="relative bg-gradient-to-br from-primary via-[#2233ff] to-primary-800 rounded-3xl p-8 text-white shadow-2xl overflow-hidden">
                    <!-- Decorative circles -->
                    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5 border border-white/10"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white/5 border border-white/10"></div>

                    <div class="relative z-10">
                        <h3 class="text-3xl font-black mb-2 leading-tight">Contact<br>Information</h3>
                        <p class="text-primary-200 text-sm mb-6 leading-relaxed">No question should be left without an answer. Contact us now and we'll get back to you promptly.</p>

                        <div class="w-full h-px bg-white/20 mb-6"></div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">Office Location</p>
                                    <p class="text-primary-200 text-sm">Tamil Nadu, India</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">Phone Numbers</p>
                                    <a href="tel:+917012276177" class="text-primary-200 hover:text-white text-sm block transition-colors">+91 70122 76177</a>
                                    <a href="tel:+918075098177" class="text-primary-200 hover:text-white text-sm block transition-colors">+91 80750 98177</a>
                                    <a href="tel:+917639276646" class="text-primary-200 hover:text-white text-sm block transition-colors">+91 76392 76646</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white">Email</p>
                                    <a href="mailto:support@toppershope.com" class="text-primary-200 hover:text-white text-sm block transition-colors">support@toppershope.com</a>
                                </div>
                            </li>
                        </ul>

                        <div class="w-full h-px bg-white/20 mb-6"></div>

                        <p class="font-bold text-sm text-white mb-4">Follow Us</p>
                        <div class="flex gap-3">
                            @foreach([
                                ['icon'=>'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z','href'=>'#','label'=>'Facebook'],
                                ['icon'=>'M22.54 6.42a2.78 2.78 0 00-1.95-1.95C18.88 4 12 4 12 4s-6.88 0-8.59.47a2.78 2.78 0 00-1.95 1.95A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z M9.75 15.02V8.98L15.5 12l-5.75 3.02z','href'=>'#','label'=>'YouTube'],
                                ['icon'=>'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z M2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z','href'=>'#','label'=>'LinkedIn'],
                                ['icon'=>'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z M17.5 6.5h.01 M7.5 2h9a5.5 5.5 0 015.5 5.5v9a5.5 5.5 0 01-5.5 5.5h-9A5.5 5.5 0 012 16.5v-9A5.5 5.5 0 017.5 2z','href'=>'#','label'=>'Instagram'],
                            ] as $social)
                            <a href="{{ $social['href'] }}" aria-label="{{ $social['label'] }}"
                               class="w-10 h-10 rounded-full border border-white/30 bg-white/10 hover:bg-white/25 flex items-center justify-center transition-all hover:scale-110 hover:border-white/50">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $social['icon'] }}"></path>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CATEGORY FILTER ===== -->
<div class="bg-slate-50 border-y border-gray-200 py-5 sticky top-20 z-30" x-data>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-3 items-center justify-center">
            @foreach(['All','Admissions','Courses','Payments','Results','Technical'] as $cat)
            <button
                class="faq-filter-btn px-5 py-2 rounded-full border text-sm font-semibold transition-all {{ $loop->first ? 'bg-primary text-white border-primary shadow-md' : 'bg-white border-gray-200 text-gray-600 hover:border-primary hover:text-primary' }}"
                data-filter="{{ strtolower($cat) }}"
                onclick="filterFaq(this, '{{ strtolower($cat) }}')">
                {{ $cat }}
            </button>
            @endforeach
        </div>
    </div>
</div>

<!-- ===== FAQ ACCORDION (Two-column layout) ===== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- FAQ Accordion -->
            <div class="lg:col-span-2" x-data="{ open: null }">
                @php
                $faqs = [
                    ['cat'=>'admissions','q'=>'How do I enroll in a batch?','a'=>'Enrolling is simple! Visit the course or category page, select your preferred batch, click "Enroll Now", create your account (or log in), and complete the payment via Razorpay. Your access is activated instantly after payment confirmation.'],
                    ['cat'=>'admissions','q'=>'Do I need to register before buying?','a'=>'Yes, you need to create an account first. Registration is free and takes under 2 minutes. You\'ll need your name, email, phone number, Date of Birth, and your target course (JEE/NEET).'],
                    ['cat'=>'admissions','q'=>'Is there an age limit to join?','a'=>'There is no strict age limit. Our courses are designed for students from Class 8 onwards through to working professionals appearing for UPSC or SSC exams.'],
                    ['cat'=>'courses','q'=>'What does a typical batch include?','a'=>'Every batch includes Live + Recorded video lectures, Daily Practice Papers (DPPs) with video solutions, a chapter-wise test series, All India Mock Tests, PDF notes and formula sheets, and 24×7 doubt resolution support via our Doubt Desk.'],
                    ['cat'=>'courses','q'=>'Can I watch recorded videos if I miss a live class?','a'=>'Absolutely! All live sessions are recorded and are available within 2–4 hours in your dashboard. You can watch them unlimited times. Some batches may have a time-restricted viewing policy (e.g., 30 days from broadcast).'],
                    ['cat'=>'courses','q'=>'How long does a batch last?','a'=>'Duration varies by batch type. Foundation batches run 12–18 months. Dropper batches run 10–12 months. Crash courses are typically 45–90 days.'],
                    ['cat'=>'courses','q'=>'Are the courses aligned with NTA/NCERT guidelines?','a'=>'Yes, 100%. Our entire JEE and NEET curriculum is strictly aligned with the current NTA exam pattern and NCERT syllabus. Our faculty audits the content every year post-notification.'],
                    ['cat'=>'payments','q'=>'What payment methods are accepted?','a'=>'We accept all major UPI apps (PhonePe, GPay, Paytm), Debit Cards, Credit Cards, Net Banking, and EMI options through Razorpay. All payments are 100% secure and encrypted.'],
                    ['cat'=>'payments','q'=>'Can I get a refund?','a'=>'We offer a full refund within 7 days of purchase if you are not satisfied for any reason. After the 7-day window, refunds are processed on a case-by-case basis. Contact support@toppershope.com to initiate.'],
                    ['cat'=>'payments','q'=>'Are scholarships available?','a'=>'Yes! We run a Scholarship Admission Test regularly — top performers receive up to 90% fee waivers. Check our homepage banner for upcoming test dates and registration links.'],
                    ['cat'=>'results','q'=>'What is the success rate of your students?','a'=>'We maintain a 98%+ result rate among students who complete at least 80% of the curriculum and appear for our test series. Our top performers have achieved AIR under 100 in JEE Advanced and NEET.'],
                    ['cat'=>'results','q'=>'Do you publish rank lists?','a'=>'Yes, we publish detailed semester and annual rank lists on our Results page. We believe in radical transparency — no cherry-picking, actual verified outcomes from real students.'],
                    ['cat'=>'technical','q'=>'Which devices are supported?','a'=>'Our platform works on any modern web browser (Chrome, Firefox, Safari, Edge) on laptop, tablet, and mobile. We also have Android and iOS apps with offline viewing support.'],
                    ['cat'=>'technical','q'=>'Is there a mobile app?','a'=>'Yes! The Topper\'s Hope app is available on the Google Play Store and Apple App Store. It supports offline video downloads, live class notifications, DPP submission, and doubt uploads.'],
                    ['cat'=>'technical','q'=>'I forgot my password. What should I do?','a'=>'Click "Forgot Password?" on the login page. You\'ll receive a password reset link on your registered email. If you don\'t receive it within 5 minutes, check your spam folder or contact support.'],
                ];
                @endphp

                <div class="space-y-3" id="faq-list">
                    @foreach($faqs as $i => $faq)
                    <div class="faq-item gsap-stagger-child border border-gray-200 rounded-2xl overflow-hidden hover:border-primary/40 transition-all hover:shadow-sm"
                         data-cat="{{ $faq['cat'] }}">
                        <button
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-start justify-between gap-4 p-6 text-left"
                            :class="open === {{ $i }} ? 'bg-primary/5' : 'bg-white hover:bg-gray-50'"
                        >
                            <span class="font-bold text-gray-900 leading-snug">{{ $faq['q'] }}</span>
                            <div class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-all duration-300"
                                 :class="open === {{ $i }} ? 'bg-primary rotate-45' : 'bg-gray-100'">
                                <svg class="w-4 h-4 transition-colors" :class="open === {{ $i }} ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </button>
                        <div x-show="open === {{ $i }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="px-6 pb-6">
                            <div class="border-l-4 border-primary pl-4 ml-0 mt-1">
                                <p class="text-gray-600 leading-relaxed">{{ $faq['a'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Sticky: Contact + Highlights (lg only) -->
            <div class="hidden lg:block">
                <!-- Quick help card -->
                <div class="sticky top-40 space-y-6">
                    <div class="bg-gradient-to-br from-primary via-[#2233ff] to-primary-800 rounded-3xl p-7 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-white/5 border border-white/10"></div>
                        <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full bg-white/5 border border-white/10"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-black mb-1">Contact Information</h3>
                            <p class="text-primary-200 text-xs mb-5 leading-relaxed">No question should be left without an answer. Contact us now.</p>
                            <div class="w-full h-px bg-white/20 mb-5"></div>
                            <ul class="space-y-3 mb-5 text-sm">
                                <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg><span class="text-primary-100">Tamil Nadu, India</span></li>
                                <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg><a href="tel:+917012276177" class="text-primary-100 hover:text-white transition-colors">+91 70122 76177</a></li>
                                <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg><a href="tel:+918075098177" class="text-primary-100 hover:text-white transition-colors">+91 80750 98177</a></li>
                                <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg><a href="tel:+917639276646" class="text-primary-100 hover:text-white transition-colors">+91 76392 76646</a></li>
                                <li class="flex items-center gap-3"><svg class="w-4 h-4 text-primary-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg><a href="mailto:support@toppershope.com" class="text-primary-100 hover:text-white transition-colors">support@toppershope.com</a></li>
                            </ul>
                            <div class="w-full h-px bg-white/20 mb-5"></div>
                            <p class="font-bold text-xs text-white mb-3 uppercase tracking-wider">Follow Us</p>
                            <div class="flex gap-2">
                            @foreach(range(1,4) as $s)
                                <button class="w-9 h-9 rounded-full border border-white/30 bg-white/10 hover:bg-white/25 flex items-center justify-center text-[11px] text-white font-bold transition-all hover:scale-110">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Still have questions -->
                    <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-primary-100 border border-primary-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                        <h4 class="font-black text-gray-900 mb-2">Still have questions?</h4>
                        <p class="text-gray-500 text-sm mb-4">Our team replies within 2 hours.</p>
                        <a href="mailto:support@toppershope.com" class="block w-full py-2.5 bg-primary hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">Email Us Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== MOBILE CONTACT CARD ===== -->
<section class="lg:hidden py-12 bg-slate-50 border-t border-gray-100">
    <div class="max-w-lg mx-auto px-4">
        <div class="bg-gradient-to-br from-primary to-primary-600 rounded-3xl p-8 text-white shadow-xl">
            <h3 class="text-2xl font-black mb-1">Contact Information</h3>
            <p class="text-primary-200 text-sm mb-6">No question should be left without an answer.</p>
            <ul class="space-y-3 text-sm mb-6">
                <li class="flex items-center gap-3 text-primary-100"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Tamil Nadu, India</li>
                <li><a href="tel:+917012276177" class="flex items-center gap-3 text-primary-100 hover:text-white"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>+91 70122 76177</a></li>
                <li><a href="tel:+918075098177" class="flex items-center gap-3 text-primary-100 hover:text-white"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>+91 80750 98177</a></li>
                <li><a href="tel:+917639276646" class="flex items-center gap-3 text-primary-100 hover:text-white"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>+91 76392 76646</a></li>
                <li><a href="mailto:support@toppershope.com" class="flex items-center gap-3 text-primary-100 hover:text-white"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>support@toppershope.com</a></li>
            </ul>
        </div>
    </div>
</section>

<!-- ===== GSAP + Search script ===== -->
<script>
(function() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // Hero sequence
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.fromTo('#hero-badge',   { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 })
      .fromTo('#hero-title',   { opacity:0, y:40 }, { opacity:1, y:0, duration:.7 }, '-=.3')
      .fromTo('#hero-sub',     { opacity:0, y:30 }, { opacity:1, y:0, duration:.6 }, '-=.4')
      .fromTo('#hero-search',  { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 }, '-=.3')
      .fromTo('#hero-3d',      { opacity:0, scale:.85 }, { opacity:1, scale:1, duration:.9 }, '-=.6');

    // 3D model float animation
    gsap.to('#faq-3d-img', { y: -18, duration: 3.5, repeat:-1, yoyo:true, ease:'sine.inOut' });

    // Scroll reveals
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el, { opacity:0, y:40 }, { opacity:1, y:0, duration:.8, ease:'power2.out',
            scrollTrigger:{ trigger:el, start:'top 85%' }
        });
    });

    // FAQ stagger
    const faqItems = document.querySelectorAll('.gsap-stagger-child');
    if (faqItems.length) {
        ScrollTrigger.create({ trigger:'#faq-list', start:'top 80%', onEnter: () => {
            gsap.fromTo(faqItems, { opacity:0, x:-20 }, { opacity:1, x:0, duration:.5, stagger:.07, ease:'power2.out' });
        }});
    }

    // Contact card parallax
    gsap.to('#contact-card', {
        yPercent: -5,
        ease: 'none',
        scrollTrigger: { trigger: '#contact-card', start: 'top 80%', end: 'bottom 20%', scrub: 1 }
    });
})();

// Category filter
function filterFaq(btn, cat) {
    document.querySelectorAll('.faq-filter-btn').forEach(b => {
        b.classList.remove('bg-primary','text-white','border-primary','shadow-md');
        b.classList.add('bg-white','border-gray-200','text-gray-600');
    });
    btn.classList.remove('bg-white','border-gray-200','text-gray-600');
    btn.classList.add('bg-primary','text-white','border-primary','shadow-md');
    document.querySelectorAll('.faq-item').forEach(item => {
        item.style.display = (cat === 'all' || item.dataset.cat === cat) ? 'block' : 'none';
    });
}

// Live search
const searchInput = document.getElementById('faq-search-input');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.faq-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = (!q || text.includes(q)) ? 'block' : 'none';
        });
    });
}
</script>
@endsection
