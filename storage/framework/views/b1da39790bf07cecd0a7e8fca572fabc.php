

<?php $__env->startSection('title', 'Contact Us'); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- ===== PREMIUM HERO ===== -->
<section id="contact-hero" class="relative overflow-hidden bg-gradient-to-b from-slate-50 via-white to-white border-b border-slate-100">
    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-blue-100/70 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 right-0 w-80 h-80 rounded-full bg-violet-100/60 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <span id="hero-badge" class="inline-flex items-center bg-white border border-slate-200 text-primary text-xs font-extrabold px-4 py-2 rounded-full uppercase tracking-widest mb-6">We're Here for You</span>
            <h1 id="hero-title" class="text-4xl md:text-6xl font-black text-slate-900 leading-tight mb-5">
                Talk to Our
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-violet-600">Support Team</span>
            </h1>
            <p id="hero-sub" class="text-slate-600 text-lg leading-relaxed max-w-xl">
                Get quick help for admissions, payments, batches, and technical issues from our student success team.
            </p>
            <div id="hero-btns" class="mt-8 flex flex-wrap gap-3">
                <a href="tel:+917012276177" class="px-7 py-3 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-[0_8px_24px_rgba(37,99,235,0.28)] flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    Call Now
                </a>
                <a href="mailto:support@toppershope.com" class="px-7 py-3 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-bold rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email Us
                </a>
            </div>
        </div>

        <div class="md:w-1/2 w-full" id="hero-3d">
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-lg">
                <div class="grid sm:grid-cols-3 gap-3">
                    <?php $__currentLoopData = [['Avg. Reply','< 2 hrs'],['Support Window','9 AM - 9 PM'],['Satisfaction','98%']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-4 text-center">
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold"><?php echo e($m[0]); ?></p>
                        <p class="text-base font-black text-slate-900 mt-1"><?php echo e($m[1]); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=900" alt="Contact support team"
                     class="hidden"
                     id="contact-3d-img">
            </div>
        </div>
    </div>
</section>

<!-- ===== INTRO + CONTACT FORM + SIDEBAR ===== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <!-- Left: Contact Form -->
            <div class="lg:col-span-8 gsap-reveal">
                <!-- Accent border heading -->
                <div class="flex items-stretch gap-4 mb-8">
                    <div class="w-1.5 rounded-full bg-gradient-to-b from-primary to-purple-500 shrink-0"></div>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900">
                        <span class="text-primary">Send Us</span> a Message
                    </h2>
                </div>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed max-w-xl">
                    Fill out the form below and our team will get back to you within 2 business hours. For urgent queries, please call us directly.
                </p>

                <!-- Contact Form -->
                <form class="space-y-6" id="contact-form" onsubmit="handleSubmit(event)">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name *</label>
                            <input type="text" required placeholder="Rahul Sharma"
                                   class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address *</label>
                            <input type="email" required placeholder="rahul@example.com"
                                   class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" placeholder="+91 98765 43210"
                                   class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Query Type</label>
                            <select class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-700 bg-gray-50 hover:bg-white appearance-none">
                                <option>Course Enquiry</option>
                                <option>Admissions</option>
                                <option>Payment or Refund</option>
                                <option>Technical Support</option>
                                <option>Partnership</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subject *</label>
                        <input type="text" required placeholder="Brief subject of your message"
                               class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Your Message *</label>
                        <textarea required rows="5" placeholder="Describe your query in detail..."
                                  class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-gray-900 placeholder-gray-400 bg-gray-50 hover:bg-white resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-primary hover:bg-blue-700 text-white font-black rounded-xl transition-all shadow-[0_4px_14px_rgba(27,42,255,0.39)] hover:shadow-[0_6px_20px_rgba(27,42,255,0.5)] flex items-center gap-2 text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Send Message
                    </button>
                    <!-- Success toast -->
                    <div id="form-success" class="hidden mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl font-semibold text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Message sent! Our team will reach out within 2 hours.
                    </div>
                </form>
            </div>

            <!-- Right: Contact Info Sticky Card -->
            <div class="lg:col-span-4 gsap-reveal lg:sticky lg:top-28 space-y-6">
                <!-- Main Contact Card -->
                <div class="relative bg-gradient-to-br from-primary via-[#2233ff] to-[#7B61FF] rounded-3xl p-8 text-white shadow-2xl overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/5 border border-white/10"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 rounded-full bg-white/5 border border-white/10"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-1">Contact<br>Information</h3>
                        <p class="text-blue-200 text-sm mb-5 leading-relaxed">No question should be left without an answer. Contact us now.</p>
                        <div class="w-full h-px bg-white/20 mb-5"></div>
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div><p class="text-xs text-blue-200 uppercase tracking-wide">Location</p><p class="font-bold text-white text-sm">Coimbatore, Tamil Nadu 641406</p></div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-200 uppercase tracking-wide">Phone Numbers</p>
                                    <a href="tel:+917012276177" class="block font-bold text-white text-sm hover:text-cyan-200 transition-colors">+91 70122 76177</a>
                                    <a href="tel:+918075098177" class="block font-bold text-white text-sm hover:text-cyan-200 transition-colors">+91 80750 98177</a>
                                    <a href="tel:+917639276646" class="block font-bold text-white text-sm hover:text-cyan-200 transition-colors">+91 76392 76646</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div><p class="text-xs text-blue-200 uppercase tracking-wide">Email</p><a href="mailto:support@toppershope.com" class="font-bold text-white text-sm hover:text-cyan-200 transition-colors">support@toppershope.com</a></div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div><p class="text-xs text-blue-200 uppercase tracking-wide">Working Hours</p><p class="font-bold text-white text-sm">Mon – Sat: 9 AM – 9 PM IST</p></div>
                            </li>
                        </ul>
                        <div class="w-full h-px bg-white/20 mb-5"></div>
                        <p class="font-bold text-xs text-white mb-3 uppercase tracking-wider">Follow Us</p>
                        <div class="flex gap-3">
                            <?php $__currentLoopData = [['fb','M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],['yt','M22.54 6.42a2.78 2.78 0 00-1.95-1.95C18.88 4 12 4 12 4s-6.88 0-8.59.47a2.78 2.78 0 00-1.95 1.95A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z M9.75 15.02V8.98L15.5 12l-5.75 3.02z'],['li','M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z M2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],['ig','M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z M17.5 6.5h.01 M7.5 2h9a5.5 5.5 0 015.5 5.5v9a5.5 5.5 0 01-5.5 5.5h-9A5.5 5.5 0 012 16.5v-9A5.5 5.5 0 017.5 2z']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="#" class="w-10 h-10 rounded-full border border-white/30 bg-white/10 hover:bg-white/25 flex items-center justify-center transition-all hover:scale-110 hover:border-white/50">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo e($s[1]); ?>"></path></svg>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp card -->
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-green-100 border border-green-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h4 class="font-black text-gray-900 mb-2">Chat on WhatsApp</h4>
                    <p class="text-gray-500 text-sm mb-4">For instant help, message us on WhatsApp — usually replied in minutes.</p>
                    <a href="https://wa.me/917012276177" target="_blank" class="block py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all shadow-sm">Open WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== MAP SECTION ===== -->
<section class="bg-slate-50 border-t border-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 gsap-reveal">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-black text-gray-900">Find Us on <span class="text-primary">Google Maps</span></h2>
            <p class="text-gray-500 mt-2 text-sm">Our headquarters is based in Coimbatore, Tamil Nadu, India</p>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">
            <iframe
                src="https://maps.google.com/maps?q=Tamil+Nadu,+India&t=&z=8&ie=UTF8&iwloc=&output=embed"
                class="w-full h-64 md:h-96 block border-0 grayscale hover:grayscale-0 transition-all duration-700"
                allowfullscreen
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- ===== FAQ CTA ===== -->
<section class="py-16 bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 text-center gsap-reveal">
        <h2 class="text-3xl font-black text-white mb-4">Looking for quick answers?</h2>
        <p class="text-gray-400 mb-8">Check our FAQ page — we've answered the most common questions there.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('faq')); ?>" class="px-10 py-4 bg-primary hover:bg-blue-700 text-white font-black rounded-xl transition-all shadow-xl">Browse FAQs</a>
            <a href="<?php echo e(route('about')); ?>" class="px-10 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold rounded-xl transition-all">About Us</a>
        </div>
    </div>
</section>

<script>
function handleSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type=submit]');
    const success = document.getElementById('form-success');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Sending...';
    setTimeout(() => {
        success.classList.remove('hidden');
        success.classList.add('flex');
        btn.innerHTML = '✓ Sent!';
        btn.classList.add('bg-green-600');
        btn.classList.remove('bg-primary');
    }, 1800);
}

(function() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    // Hero entrance
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.fromTo('#hero-badge',  { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 })
      .fromTo('#hero-title',  { opacity:0, y:40 }, { opacity:1, y:0, duration:.7 }, '-=.3')
      .fromTo('#hero-sub',    { opacity:0, y:30 }, { opacity:1, y:0, duration:.6 }, '-=.4')
      .fromTo('#hero-btns',   { opacity:0, y:20 }, { opacity:1, y:0, duration:.5 }, '-=.3')
      .fromTo('#hero-3d',     { opacity:0, scale:.85, x:40 }, { opacity:1, scale:1, x:0, duration:.9 }, '-=.5');

    // Float 3D asset
    gsap.to('#contact-3d-img', { y: -16, duration:3.5, repeat:-1, yoyo:true, ease:'sine.inOut' });

    // Scroll reveals
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el, { opacity:0, y:40 }, { opacity:1, y:0, duration:.8, ease:'power2.out',
            scrollTrigger:{ trigger:el, start:'top 85%' }
        });
    });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/pages/contact.blade.php ENDPATH**/ ?>