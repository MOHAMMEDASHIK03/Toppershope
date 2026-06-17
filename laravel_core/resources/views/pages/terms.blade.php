@extends('layouts.public')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100 py-20 md:py-24">
    <div class="absolute -top-20 left-1/4 w-72 h-72 bg-blue-100/70 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 right-1/4 w-80 h-80 bg-violet-100/60 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div id="hero-badge" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Legal · Terms
        </div>
        <h1 id="hero-title" class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-5">
            Terms &amp;
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-violet-600">Conditions</span>
        </h1>
        <p id="hero-sub" class="text-lg text-slate-600 max-w-2xl mx-auto mb-5">
            Review the terms that govern usage of our platform, classes, content, and related services.
        </p>
        <p class="text-sm text-slate-500">Last updated: <strong class="text-slate-700">March 1, 2026</strong></p>
    </div>
</section>

{{-- ===== CONTENT ===== --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl p-6 mb-12">
            <p class="text-gray-700 leading-relaxed">
                These Terms and Conditions ("Terms") govern your access to and use of the Topper's Hope website, mobile application, and all associated services (collectively, the "Service"), operated by <strong>Topper's Hope EdTech Pvt. Ltd.</strong> ("Company", "we", "our", "us"). By accessing or using our Service, you agree to be bound by these Terms. If you disagree with any part of the terms, please do not access the Service.
            </p>
        </div>

        @php
        $sections = [
            ['icon' => 'M5.121 17.804A9 9 0 1112 21a8.96 8.96 0 01-6.879-3.196zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'title' => '1. Eligibility & Account Registration', 'content' => '
                <p>To use our Service, you must:</p>
                <ul>
                    <li>Be at least 13 years of age. Students below 18 must have consent of a parent or guardian.</li>
                    <li>Provide accurate, current, and complete information during registration.</li>
                    <li>Maintain the security of your password and accept all risks of unauthorized access to your account.</li>
                    <li>Notify us immediately at support@toppershope.in if your account is compromised.</li>
                </ul>
                <p>One account is permitted per student. Multiple accounts for the same individual are prohibited and may result in termination of all associated accounts.</p>
            '],
            ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => '2. Course Enrollment & Access', 'content' => '
                <p>When you enroll in a course or batch:</p>
                <ul>
                    <li>You receive a <strong>non-exclusive, non-transferable, limited license</strong> to access and view the course content for personal, non-commercial, and educational use only.</li>
                    <li>Course access is granted for the duration specified at the time of enrollment. Access may expire or be adjusted by the Company.</li>
                    <li>You may not share your login credentials, screen-record live sessions, or distribute any course content.</li>
                    <li>We reserve the right to modify course content, schedules, faculty, or course structure without prior notice.</li>
                </ul>
            '],
            ['icon' => 'M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2z', 'title' => '3. Payments, Fees & Refunds', 'content' => '
                <p><strong>Fees:</strong> All fees are listed in Indian Rupees (INR) and are inclusive of applicable GST unless stated otherwise. Fees may change and will be communicated before purchase.</p>
                <p><strong>Payment:</strong> We accept UPI, net banking, debit/credit cards, and EMI via Razorpay. Payments must be completed in full before access is granted.</p>
                <p><strong>Refund Policy:</strong></p>
                <ul>
                    <li>Refund requests must be raised within <strong>7 days</strong> of enrollment via email to refunds@toppershope.in.</li>
                    <li>Courses where more than 20% of content has been accessed are not eligible for a full refund.</li>
                    <li>Live batch enrollments are non-refundable once the batch has commenced.</li>
                    <li>Scholarship test fees are non-refundable under any circumstances.</li>
                    <li>Approved refunds are processed within 5–7 business days to the original payment method.</li>
                </ul>
            '],
            ['icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'title' => '4. Intellectual Property', 'content' => '
                <p>All content on the platform — including but not limited to text, graphics, videos, audio, tests, DPPs, study material, and software — is the exclusive property of <strong>Topper\'s Hope EdTech Pvt. Ltd.</strong> or its content partners and is protected by Indian copyright, trademark, and intellectual property laws.</p>
                <ul>
                    <li>You may not reproduce, distribute, publicly display, modify, or create derivative works from any content without our express written permission.</li>
                    <li>Screen recording live or recorded sessions is strictly prohibited and constitutes copyright infringement.</li>
                    <li>We actively monitor for unauthorised sharing of content.</li>
                </ul>
            '],
            ['icon' => 'M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728', 'title' => '5. Prohibited Conduct', 'content' => '
                <p>You agree not to:</p>
                <ul>
                    <li>Use our Service for any unlawful purpose or in violation of these Terms.</li>
                    <li>Harass, abuse, or harm another person or group through our platform.</li>
                    <li>Upload or transmit viruses or any other malicious code.</li>
                    <li>Attempt to gain unauthorized access to any portion of the platform.</li>
                    <li>Use bots, scrapers, or automated means to access the platform.</li>
                    <li>Impersonate any person or entity, or falsely state or misrepresent your affiliation.</li>
                    <li>Resell, sublicense, or commercially exploit any content from our platform.</li>
                </ul>
                <p>Violation of these prohibitions may result in immediate termination of your account without a refund.</p>
            '],
            ['icon' => 'M12 9v2m0 4h.01M10.29 3.86l-7.08 12.24A2 2 0 004.92 19h14.16a2 2 0 001.71-2.9L13.71 3.86a2 2 0 00-3.42 0z', 'title' => '6. Disclaimer of Warranties', 'content' => '
                <p>The Service is provided on an <strong>"AS IS" and "AS AVAILABLE"</strong> basis without any warranties, express or implied, including but not limited to:</p>
                <ul>
                    <li>Merchantability, fitness for a particular purpose, or non-infringement.</li>
                    <li>That the Service will be uninterrupted, error-free, or virus-free.</li>
                    <li>That results obtained through the Service will be accurate or reliable.</li>
                    <li>Specific outcomes in competitive exams (ranks, admissions, or grades).</li>
                </ul>
            '],
            ['icon' => 'M3 17l6-6 4 4 8-8', 'title' => '7. Limitation of Liability', 'content' => '
                <p>To the fullest extent permitted by applicable law, Topper\'s Hope shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, goodwill, service interruption, or loss of educational opportunity, arising out of or in connection with these Terms or your use of the Service.</p>
                <p>Our total liability to you for any claim arising out of or relating to these Terms or the Service shall not exceed the amount you paid us in the three (3) months preceding the event giving rise to the claim.</p>
            '],
            ['icon' => 'M3 10l9-5 9 5-9 5-9-5zm0 0v8h18v-8', 'title' => '8. Governing Law & Dispute Resolution', 'content' => '
                <p>These Terms shall be governed by and construed in accordance with the laws of <strong>India</strong>, without regard to its conflict of law provisions.</p>
                <p>Any dispute, claim, or controversy arising out of or relating to these Terms or use of the Service shall first be attempted to be resolved through good-faith negotiation. Failing that, disputes shall be subject to the exclusive jurisdiction of the courts of <strong>Chennai, Tamil Nadu, India</strong>.</p>
            '],
            ['icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z', 'title' => '9. Changes to Terms', 'content' => '
                <p>We reserve the right to modify these Terms at any time. We will notify you of significant changes by:</p>
                <ul>
                    <li>Posting the new Terms on this page with an updated "Last Updated" date.</li>
                    <li>Sending an email to your registered email address.</li>
                </ul>
                <p>Your continued use of the Service after any changes constitutes your acceptance of the new Terms. If you do not agree to the new terms, please stop using the Service.</p>
            '],
            ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'title' => '10. Contact Us', 'content' => '
                <p>If you have questions about these Terms, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> legal@toppershope.in</li>
                    <li><strong>Phone:</strong> +91 98765 43210</li>
                    <li><strong>Address:</strong> Toppers Hope, SOWDAMBIKA COMPLEX, D.NO.43,1st Floor, Chairman Karuppanna Devar Street, Sulur, Coimbatore 641406</li>
                </ul>
            '],
        ];
        @endphp

        <div class="space-y-8">
            @foreach($sections as $s)
            <div class="terms-section bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-8">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $s['icon'] }}"></path></svg>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900">{{ $s['title'] }}</h2>
                </div>
                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_li]:text-gray-600 [&_p]:mb-3 [&_strong]:text-gray-800">
                    {!! $s['content'] !!}
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('privacy') }}" class="inline-flex items-center gap-2 text-primary font-semibold hover:text-blue-700 transition-colors">
                Read our Privacy Policy
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger);
    gsap.from(['#hero-badge','#hero-title','#hero-sub'], { opacity:0, y:30, stagger:0.15, duration:0.8, ease:'power3.out' });
    gsap.utils.toArray('.terms-section').forEach((el, i) => {
        gsap.from(el, { opacity:0, y:30, duration:0.6, delay: i * 0.05, ease:'power2.out',
            scrollTrigger: { trigger: el, start: 'top 90%' }
        });
    });
});
</script>
@endpush
@endsection
