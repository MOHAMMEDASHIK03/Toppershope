

<?php $__env->startSection('title', ($category->landing('title', $category->name)) . ' Courses'); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
$colorMap = [
    'orange' => ['ring'=>'ring-orange-300','bg_light'=>'bg-orange-50','text'=>'text-orange-600','btn'=>'bg-orange-500 hover:bg-orange-600','badge'=>'bg-orange-100 text-orange-800','border'=>'border-orange-200','pill'=>'bg-orange-50 border-orange-200 text-orange-700'],
    'red'    => ['ring'=>'ring-red-300','bg_light'=>'bg-red-50','text'=>'text-red-600','btn'=>'bg-red-500 hover:bg-red-600','badge'=>'bg-red-100 text-red-800','border'=>'border-red-200','pill'=>'bg-red-50 border-red-200 text-red-700'],
    'blue'   => ['ring'=>'ring-blue-300','bg_light'=>'bg-blue-50','text'=>'text-blue-600','btn'=>'bg-blue-600 hover:bg-blue-700','badge'=>'bg-blue-100 text-blue-800','border'=>'border-blue-200','pill'=>'bg-blue-50 border-blue-200 text-blue-700'],
    'purple' => ['ring'=>'ring-purple-300','bg_light'=>'bg-purple-50','text'=>'text-purple-600','btn'=>'bg-purple-600 hover:bg-purple-700','badge'=>'bg-purple-100 text-purple-800','border'=>'border-purple-200','pill'=>'bg-purple-50 border-purple-200 text-purple-700'],
    'teal'   => ['ring'=>'ring-teal-300','bg_light'=>'bg-teal-50','text'=>'text-teal-600','btn'=>'bg-teal-600 hover:bg-teal-700','badge'=>'bg-teal-100 text-teal-800','border'=>'border-teal-200','pill'=>'bg-teal-50 border-teal-200 text-teal-700'],
    'indigo' => ['ring'=>'ring-indigo-300','bg_light'=>'bg-indigo-50','text'=>'text-indigo-600','btn'=>'bg-indigo-600 hover:bg-indigo-700','badge'=>'bg-indigo-100 text-indigo-800','border'=>'border-indigo-200','pill'=>'bg-indigo-50 border-indigo-200 text-indigo-700'],
    'green'  => ['ring'=>'ring-green-300','bg_light'=>'bg-green-50','text'=>'text-green-600','btn'=>'bg-green-600 hover:bg-green-700','badge'=>'bg-green-100 text-green-800','border'=>'border-green-200','pill'=>'bg-green-50 border-green-200 text-green-700'],
    'slate'  => ['ring'=>'ring-slate-300','bg_light'=>'bg-slate-100','text'=>'text-slate-700','btn'=>'bg-slate-700 hover:bg-slate-800','badge'=>'bg-slate-200 text-slate-800','border'=>'border-slate-200','pill'=>'bg-slate-100 border-slate-200 text-slate-700'],
    'yellow' => ['ring'=>'ring-yellow-300','bg_light'=>'bg-yellow-50','text'=>'text-yellow-700','btn'=>'bg-yellow-500 hover:bg-yellow-600','badge'=>'bg-yellow-100 text-yellow-800','border'=>'border-yellow-200','pill'=>'bg-yellow-50 border-yellow-200 text-yellow-700'],
];
$c = $colorMap[$category->landing('color', 'blue')] ?? $colorMap['blue'];
$heroBg = $category->landing('hero_bg', 'from-blue-50 to-indigo-50');
$iconUrl = $category->landing('icon_url');
$subjects = $category->landing('subjects', []);
$features = $category->landing('features', []);
?>


<section class="relative bg-gradient-to-br <?php echo e($heroBg); ?> border-b border-gray-200 py-20 overflow-hidden" id="cat-hero">
    <div class="absolute inset-0 bg-pattern opacity-60 z-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center gap-12">
        <div class="md:w-2/3">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-primary transition-colors mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Home
            </a>
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="<?php echo e(route('category.show', $category)); ?>" class="px-3 py-1 rounded-full border text-xs font-bold <?php echo e(!$subcategory ? 'bg-primary text-white border-primary' : $c['pill']); ?>">All</a>
                <?php $__currentLoopData = $category->activeSubcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('category.show.subcategory', [$category, $sub])); ?>" class="px-3 py-1 rounded-full border text-xs font-bold <?php echo e($subcategory && $subcategory->id === $sub->id ? 'bg-primary text-white border-primary' : $c['pill']); ?>"><?php echo e($sub->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-4"><?php echo e($category->landing('title', $category->name)); ?></h1>
            <p class="text-xl text-gray-600 mb-6 font-medium"><?php echo e($category->landing('subtitle', '')); ?></p>
            <p class="text-gray-600 leading-relaxed max-w-2xl mb-8"><?php echo e($category->landing('about', $category->description)); ?></p>
            <?php if($subcategory): ?>
            <p class="text-sm font-bold text-primary mb-4">Showing batches for: <?php echo e($subcategory->name); ?></p>
            <?php endif; ?>
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo e(route('register')); ?>" class="px-8 py-3.5 <?php echo e($c['btn']); ?> text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                    Enroll Now
                </a>
                <a href="#batches" class="px-8 py-3.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    View Batches
                </a>
            </div>
        </div>
        <div class="md:w-1/3 flex justify-center md:justify-end">
            <div class="relative w-56 h-56 <?php echo e($c['bg_light']); ?> rounded-full flex items-center justify-center shadow-inner ring-8 <?php echo e($c['ring']); ?> ring-opacity-30">
                <?php if($iconUrl): ?>
                <img src="<?php echo e($iconUrl); ?>" alt="<?php echo e($category->name); ?>" class="w-32 h-32 object-contain drop-shadow-xl">
                <?php endif; ?>
                <div class="absolute -top-4 -right-4 w-16 h-16 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-100">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 21h8M12 17v4M7 4h10v2a5 5 0 01-5 5h0a5 5 0 01-5-5V4zM5 6H4a2 2 0 00-2 2v1a3 3 0 003 3h1M19 6h1a2 2 0 012 2v1a3 3 0 01-3 3h-1"></path></svg>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div><p class="text-3xl font-black text-gray-900">1 Lakh+</p><p class="text-sm text-gray-500 mt-1">Enrolled Students</p></div>
        <div><p class="text-3xl font-black text-gray-900">98%</p><p class="text-sm text-gray-500 mt-1">Success Rate</p></div>
        <div><p class="text-3xl font-black text-gray-900">500+</p><p class="text-sm text-gray-500 mt-1">Hours of Content</p></div>
        <div><p class="text-3xl font-black text-gray-900">50+</p><p class="text-sm text-gray-500 mt-1">Expert Faculty</p></div>
    </div>
</div>


<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <div class="gsap-reveal">
                <h2 class="text-2xl font-black text-gray-900 mb-6">Subjects Covered</h2>
                <div class="space-y-3">
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 <?php echo e($c['bg_light']); ?> <?php echo e($c['text']); ?> rounded-lg flex items-center justify-center font-black text-sm shrink-0"><?php echo e($loop->iteration); ?></div>
                        <span class="font-semibold text-gray-700"><?php echo e($subject); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="gsap-reveal">
                <h2 class="text-2xl font-black text-gray-900 mb-6">What You Get</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-3 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-semibold text-gray-700"><?php echo e($feature); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="batches" class="py-20 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 gsap-reveal">
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-3">Available Batches</h2>
            <p class="text-gray-500">Choose the batch that fits your academic level and schedule</p>
        </div>

        <?php if($batches->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 gsap-stagger-parent mb-16">
            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="gsap-stagger-child bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-all group overflow-hidden flex flex-col">
                <div class="h-36 bg-gradient-to-br <?php echo e($heroBg); ?> flex items-center justify-center border-b border-gray-100 relative">
                    <div class="absolute top-3 left-3 flex items-center gap-2 flex-wrap">
                        <?php if($batch->subcategory): ?>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-600/90 text-white shadow-sm"><?php echo e($batch->subcategory->name); ?></span>
                        <?php endif; ?>
                        <?php if($batch->category): ?>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-white/90 text-slate-700 border border-white/70 shadow-sm"><?php echo e($batch->category->name); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="text-4xl font-black <?php echo e($c['text']); ?> uppercase tracking-tighter"><?php echo e(strtoupper(substr($batch->name,0,4))); ?></span>
                    <?php if($batch->status == 'filling_fast'): ?>
                    <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-black px-2.5 py-0.5 rounded-md animate-pulse uppercase">Filling Fast</span>
                    <?php endif; ?>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <a href="<?php echo e(route('course.detail', $batch->course->slug)); ?>" class="font-bold text-gray-900 mb-1 group-hover:text-primary transition-colors text-lg hover:underline underline-offset-2 block"><?php echo e($batch->name); ?></a>
                    <?php if($batch->batch_description): ?>
                    <p class="text-xs text-gray-500 mb-1"><?php echo e($batch->batch_description); ?></p>
                    <?php endif; ?>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2"><?php echo e($batch->course->description ?? 'Expert-led live classes with DPPs, Tests & 24×7 doubt support.'); ?></p>
                    <?php if($batch->mode): ?>
                    <span class="text-[10px] font-bold bg-blue-50 text-primary border border-blue-100 px-2 py-0.5 rounded-full self-start mb-3"><?php echo e($batch->mode); ?></span>
                    <?php endif; ?>
                    <?php if($batch->total_seats > 0): ?>
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span><?php echo e($batch->seats_remaining); ?> seats left</span>
                            <span><?php echo e($batch->fill_percent); ?>% filled</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full <?php echo e($batch->fill_percent >= 80 ? 'bg-red-400' : 'bg-primary'); ?>" style="width:<?php echo e($batch->fill_percent); ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4">
                        <div>
                            <?php if($batch->original_price && $batch->original_price > 0): ?>
                            <p class="text-xs text-gray-400 line-through">₹<?php echo e(number_format($batch->original_price)); ?></p>
                            <?php endif; ?>
                            <p class="text-2xl font-black text-gray-900">₹<?php echo e(number_format($batch->price)); ?></p>
                        </div>
                        <a href="<?php echo e(route('checkout.show', $batch->uuid)); ?>" class="px-5 py-2.5 rounded-xl border-2 border-primary text-primary hover:bg-primary hover:text-white text-sm font-bold transition-all">Enroll Now</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <?php if($upcomingBatches->count() > 0): ?>
        <div class="gsap-reveal">
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                    Coming Soon
                </span>
                <h3 class="text-2xl font-black text-gray-900 mt-4 mb-2">Upcoming Batch Programs</h3>
                <p class="text-gray-500 text-sm">Register your interest — early access slots fill fast!</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $upcomingBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative bg-white rounded-2xl border-2 border-amber-200 p-6 shadow-sm hover:shadow-lg transition-all flex flex-col gap-3 overflow-hidden group">
                    <div class="absolute top-3 right-3 bg-amber-400 text-amber-900 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase">Coming Soon</div>
                    <div class="w-14 h-14 <?php echo e($c['bg_light']); ?> rounded-2xl flex items-center justify-center shrink-0">
                        <?php if($iconUrl): ?><img src="<?php echo e($iconUrl); ?>" alt="<?php echo e($ub->name); ?>" class="w-8 h-8 object-contain"><?php endif; ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base mb-0.5"><?php echo e($ub->name); ?></h4>
                        <?php if($ub->batch_description): ?>
                        <p class="text-xs text-gray-500"><?php echo e($ub->batch_description); ?></p>
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
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-lg font-black text-gray-900">₹<?php echo e(number_format($ub->price)); ?></p>
                        <a href="<?php echo e(route('contact')); ?>" class="px-4 py-2 text-xs font-bold <?php echo e($c['btn']); ?> text-white rounded-xl transition-all">Register Interest</a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>


<section class="py-16 bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 text-center gsap-reveal">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Ready to start your <?php echo e($category->name); ?> journey?</h2>
        <p class="text-gray-400 mb-8 text-lg">Join thousands of students already studying smarter with Topper's Hope.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('register')); ?>" class="px-10 py-4 <?php echo e($c['btn']); ?> text-white font-black rounded-xl text-lg transition-all shadow-xl">Start Free Demo</a>
            <a href="<?php echo e(route('faq')); ?>" class="px-10 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold rounded-xl transition-all">Read FAQs</a>
        </div>
    </div>
</section>

<script>
(function() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);
    gsap.fromTo('#cat-hero > div > div', { opacity:0, y:30 }, { opacity:1, y:0, duration:.8, stagger:.15, ease:'power2.out', delay:.2 });
    gsap.utils.toArray('.gsap-reveal').forEach(el => {
        gsap.fromTo(el, { opacity:0, y:40 }, { opacity:1, y:0, duration:.7, ease:'power2.out', scrollTrigger:{ trigger:el, start:'top 85%' }});
    });
    gsap.utils.toArray('.gsap-stagger-parent').forEach(parent => {
        const kids = parent.querySelectorAll('.gsap-stagger-child');
        ScrollTrigger.create({ trigger:parent, start:'top 80%', onEnter:() => {
            gsap.fromTo(kids, { opacity:0, y:30, scale:.96 }, { opacity:1, y:0, scale:1, duration:.55, stagger:.1, ease:'power2.out' });
        }});
    });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/pages/category.blade.php ENDPATH**/ ?>