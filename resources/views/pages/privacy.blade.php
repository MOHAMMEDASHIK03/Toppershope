@extends('layouts.public')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100 py-20 md:py-24">
    <div class="absolute -top-20 left-1/4 w-72 h-72 bg-primary-100/70 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 right-1/4 w-80 h-80 bg-violet-100/60 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div id="hero-badge" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Legal · Privacy
        </div>
        <h1 id="hero-title" class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-5">
            Privacy
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-600">Policy</span>
        </h1>
        <p id="hero-sub" class="text-lg text-slate-600 max-w-2xl mx-auto mb-5">
            Understand how we collect, process, and protect your data while using Topper's Hope services.
        </p>
        <p class="text-sm text-slate-500">Last updated: <strong class="text-slate-700">March 1, 2026</strong></p>
    </div>
</section>

{{-- ===== CONTENT ===== --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Intro card --}}
        <div class="bg-primary-50 border-l-4 border-primary rounded-r-2xl p-6 mb-12">
            <p class="text-gray-700 leading-relaxed">
                Welcome to <strong>Topper's Hope</strong> ("Company", "we", "our", "us"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website <strong>toppershope.in</strong> and use our services. Please read this policy carefully. If you disagree with its terms, please discontinue use of our site.
            </p>
        </div>

        @php
        $sections = [
            ['icon' => 'M9 12h6m-6 4h6M9 8h6m-7 12h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => '1. Information We Collect', 'content' => '
                <p>We may collect information about you in a variety of ways. The information we may collect on the Site includes:</p>
                <ul>
                    <li><strong>Personal Data:</strong> Name, email address, phone number, date of birth, and educational details you voluntarily give us when registering or enrolling in a course.</li>
                    <li><strong>Payment Data:</strong> We use third-party payment processors (Razorpay). We do not store your card details. All payment data is governed by the processor\'s privacy policy.</li>
                    <li><strong>Device & Usage Data:</strong> Browser type, operating system, IP address, pages visited, time spent, and links clicked — collected automatically via cookies and log files.</li>
                    <li><strong>Communication Data:</strong> Messages you send us through the contact form, doubt portal, or email.</li>
                </ul>
            '],
            ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => '2. How We Use Your Information', 'content' => '
                <p>Having accurate information about you permits us to provide a smooth, efficient, and customised experience. We use your information to:</p>
                <ul>
                    <li>Create and manage your student account</li>
                    <li>Process payments and send transaction confirmations</li>
                    <li>Deliver course content, test series, and study material</li>
                    <li>Send you administrative information (schedule changes, batch updates)</li>
                    <li>Send promotional communications — you may opt out at any time</li>
                    <li>Monitor and analyse usage to improve our platform</li>
                    <li>Prevent fraudulent transactions and monitor against theft</li>
                    <li>Respond to legal requests and prevent harm</li>
                </ul>
            '],
            ['icon' => 'M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5', 'title' => '3. Disclosure of Your Information', 'content' => '
                <p>We may share information we have collected about you in certain situations:</p>
                <ul>
                    <li><strong>By Law or to Protect Rights:</strong> If required by law or to protect our rights.</li>
                    <li><strong>Third-Party Service Providers:</strong> We may share your information with third parties that perform services for us (payment processing, email delivery, analytics, customer support).</li>
                    <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets, your information may be transferred.</li>
                    <li><strong>With Your Consent:</strong> We may disclose your information for any other purpose with your consent.</li>
                </ul>
                <p>We do <strong>not sell</strong> your personal information to third parties.</p>
            '],
            ['icon' => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0zM8.5 8.5h.01M15.5 8.5h.01M12 12h.01M9 14.5h.01M14.5 15.5h.01', 'title' => '4. Cookies & Tracking', 'content' => '
                <p>We use cookies and similar tracking technologies to track activity on our platform and hold certain information. Cookies are files with small amounts of data which may include an anonymous unique identifier.</p>
                <ul>
                    <li><strong>Session Cookies:</strong> Maintain your login session and cart.</li>
                    <li><strong>Preference Cookies:</strong> Remember your settings and preferences.</li>
                    <li><strong>Analytics Cookies:</strong> Help us understand how you interact with our platform (Google Analytics).</li>
                </ul>
                <p>You can instruct your browser to refuse all cookies. However, if you do not accept cookies, you may not be able to use some portions of our service.</p>
            '],
            ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => '5. Security of Your Information', 'content' => '
                <p>We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse.</p>
                <p>We use SSL/TLS encryption for all data transmission and store passwords using industry-standard hashing algorithms.</p>
            '],
            ['icon' => 'M9 12h6m-3-9a9 9 0 100 18 9 9 0 000-18z', 'title' => "6. Children's Privacy", 'content' => '
                <p>Our service is intended for students aged 13 and above. We do not knowingly collect personally identifiable information from children under 13. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us. If we become aware that we collected personal data from a child under 13 without parental consent, we take steps to remove that information from our servers.</p>
            '],
            ['icon' => 'M12 3l8 4v6c0 5.25-3.438 10.5-8 12-4.562-1.5-8-6.75-8-12V7l8-4z', 'title' => '7. Your Rights (DPDPA 2023)', 'content' => '
                <p>Under India\'s Digital Personal Data Protection Act, 2023 (DPDPA), you have the following rights regarding your personal data:</p>
                <ul>
                    <li><strong>Right to Access:</strong> Request a summary of your personal data we hold.</li>
                    <li><strong>Right to Correction:</strong> Request correction of inaccurate or incomplete data.</li>
                    <li><strong>Right to Erasure:</strong> Request deletion of your personal data (subject to legal obligations).</li>
                    <li><strong>Right to Grievance Redressal:</strong> File a complaint with our Data Protection Officer.</li>
                    <li><strong>Right to Nominate:</strong> Nominate another person to exercise these rights in case of death or incapacity.</li>
                </ul>
                <p>To exercise these rights, contact us at <strong>privacy@toppershope.in</strong>.</p>
            '],
            ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'title' => '8. Contact Us', 'content' => '
                <p>If you have questions or comments about this Privacy Policy, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> privacy@toppershope.in</li>
                    <li><strong>Phone:</strong> <a href="tel:'.config('contact.phone_tel').'">'.e(config('contact.phone')).'</a></li>
                    <li><strong>WhatsApp:</strong> <a href="'.config('contact.whatsapp_url').'" target="_blank" rel="noopener">'.e(config('contact.whatsapp')).'</a></li>
                    <li><strong>Address:</strong> '.e(config('contact.company')).', '.e(config('contact.address')).'</li>
                </ul>
            '],
        ];
        @endphp

        <div class="space-y-8">
            @foreach($sections as $s)
            <div class="privacy-section bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-8">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $s['icon'] }}"></path></svg>
                    </span>
                    <h2 class="text-xl font-bold text-gray-900">{{ $s['title'] }}</h2>
                </div>
                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_li]:text-gray-600 [&_p]:mb-3 [&_strong]:text-gray-800">
                    {!! $s['content'] !!}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Footer links --}}
        <div class="mt-12 text-center">
            <a href="{{ route('terms') }}" class="inline-flex items-center gap-2 text-primary font-semibold hover:text-primary-700 transition-colors">
                Read our Terms &amp; Conditions
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
    gsap.utils.toArray('.privacy-section').forEach((el, i) => {
        gsap.from(el, { opacity:0, y:30, duration:0.6, delay: i * 0.05, ease:'power2.out',
            scrollTrigger: { trigger: el, start: 'top 90%' }
        });
    });
});
</script>
@endpush
@endsection
