

<?php $__env->startSection('title', 'Manage ' . $course->name); ?>
<?php $__env->startSection('page_header'); ?>
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route('faculty.dashboard')); ?>" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
        </a>
        <span>Manage: <span class="text-primary"><?php echo e($course->name); ?></span></span>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- Navbar specific to the Course -->
<div class="mb-8 border-b border-slate-200">
    <nav class="-mb-px flex space-x-8">
        <a href="#" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
            Curriculum Builder
        </a>
        <a href="<?php echo e(route('faculty.courses.content.index', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Content Manager
        </a>
        <a href="<?php echo e(route('faculty.courses.quizzes.index', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Quizzes
        </a>
        <a href="<?php echo e(route('faculty.courses.doubts.index', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            Doubts 
            <?php $unresolvedCount = $course->doubts()->where('is_resolved', false)->count(); ?>
            <?php if($unresolvedCount > 0): ?>
                <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow-sm shadow-red-500/30"><?php echo e($unresolvedCount); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('faculty.courses.students.index', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Students
        </a>
        <a href="<?php echo e(route('faculty.courses.results.index', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Results
        </a>
    </nav>
</div>

<!-- Curriculum Builder -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Course Structure</h3>
            <p class="text-sm text-slate-500 mt-1">Organize your course into Subjects, Chapters, and Units.</p>
        </div>
        
        <!-- Add Subject Form -->
        <form action="<?php echo e(route('faculty.courses.subjects.store', $course->id)); ?>" method="POST" class="flex items-center gap-2">
            <?php echo csrf_field(); ?>
            <input type="text" name="name" placeholder="New Subject Name" required class="text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 focus:ring-1 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm shadow-orange-500/20 transition-all text-sm flex items-center">
                <i class="ph-bold ph-plus mr-1.5"></i> Add Subject
            </button>
        </form>
    </div>

    <?php if($course->subjects->isEmpty()): ?>
        <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
            <i class="ph-bold ph-books text-4xl text-slate-300 mb-3"></i>
            <h4 class="font-bold text-slate-700">No Subjects Yet</h4>
            <p class="text-sm text-slate-500">Add your first subject to start building the curriculum.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $course->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- Subject Card -->
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white" x-data="{ open: true }">
                    <div class="bg-slate-50 px-5 py-4 flex items-center justify-between border-b border-slate-200 cursor-pointer select-none" @click="open = !open">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-caret-down transition-transform duration-200 text-slate-400" :class="open ? 'rotate-0' : '-rotate-90'"></i>
                            <h4 class="font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-6 h-6 rounded bg-primary/10 text-primary flex items-center justify-center text-xs">S</span>
                                <?php echo e($subject->name); ?>

                            </h4>
                            <span class="text-xs text-slate-400 font-medium pl-2"><?php echo e($subject->chapters->count()); ?> Chapters</span>
                        </div>
                        
                        <div class="flex items-center gap-3" @click.stop>
                            <!-- Delete Subject -->
                            <form action="<?php echo e(route('faculty.courses.subjects.destroy', [$course->id, $subject->id])); ?>" method="POST" onsubmit="return confirm('Are you sure? This deletes ALL chapters and units inside this subject!');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1"><i class="ph-bold ph-trash"></i></button>
                            </form>
                        </div>
                    </div>

                    <div x-show="open" x-collapse class="p-5 bg-white">
                        <!-- Add Chapter Form -->
                        <div class="mb-4">
                            <form action="<?php echo e(route('faculty.courses.chapters.store', [$course->id, $subject->id])); ?>" method="POST" class="flex items-center gap-2">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="name" placeholder="New Chapter Name" required class="text-sm bg-white border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none w-64">
                                <button type="submit" class="bg-orange-50 hover:bg-blue-100 text-orange-600 font-bold py-1.5 px-3 rounded-lg border border-blue-200 transition-colors text-xs flex items-center">
                                    <i class="ph-bold ph-plus mr-1"></i> Add Chapter
                                </button>
                            </form>
                        </div>

                        <?php if($subject->chapters->isEmpty()): ?>
                            <p class="text-sm text-slate-400 italic py-2 pl-4">No chapters created in this subject yet.</p>
                        <?php else: ?>
                            <div class="space-y-3 pl-4 border-l-2 border-slate-100 ml-3">
                                <?php $__currentLoopData = $subject->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <!-- Chapter -->
                                    <div class="border border-slate-200 rounded-lg overflow-hidden" x-data="{ chapOpen: true }">
                                        <div class="bg-white px-4 py-3 flex items-center justify-between border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors" @click="chapOpen = !chapOpen">
                                            <div class="flex items-center gap-3">
                                                <i class="ph-bold ph-caret-down transition-transform duration-200 text-slate-300 text-sm" :class="chapOpen ? 'rotate-0' : '-rotate-90'"></i>
                                                <h5 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                                                    <span class="w-5 h-5 rounded bg-blue-100 text-orange-600 flex items-center justify-center text-[10px]">C</span>
                                                    <?php echo e($chapter->name); ?>

                                                </h5>
                                                <span class="text-[10px] text-slate-400 font-medium pl-2"><?php echo e($chapter->units->count()); ?> Units</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 text-sm" @click.stop>
                                                <!-- Delete Chapter -->
                                                <form action="<?php echo e(route('faculty.courses.chapters.destroy', [$course->id, $subject->id, $chapter->id])); ?>" method="POST">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1"><i class="ph-bold ph-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                        <div x-show="chapOpen" x-collapse class="p-4 bg-slate-50/50">
                                            <!-- Add Unit Form -->
                                            <div class="mb-3">
                                                <form action="<?php echo e(route('faculty.courses.units.store', [$course->id, $subject->id, $chapter->id])); ?>" method="POST" class="flex items-center gap-2">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="text" name="name" placeholder="New Unit (Topic) Name" required class="text-xs bg-white border border-slate-300 rounded px-2 py-1 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none w-56">
                                                    <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-bold py-1 px-2 rounded border border-emerald-200 transition-colors text-[10px] flex items-center">
                                                        <i class="ph-bold ph-plus mr-1"></i> Add Unit
                                                    </button>
                                                </form>
                                            </div>

                                            <?php if($chapter->units->isEmpty()): ?>
                                                <p class="text-xs text-slate-400 italic py-1 pl-2">No units created yet.</p>
                                            <?php else: ?>
                                                <ul class="space-y-1.5 pl-2">
                                                    <?php $__currentLoopData = $chapter->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- Unit Item -->
                                                        <li class="flex items-center justify-between bg-white border border-slate-200 rounded px-3 py-2 shadow-sm">
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                                <span class="text-sm font-semibold text-slate-700"><?php echo e($unit->name); ?></span>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <div class="flex gap-1.5 text-xs text-slate-400 font-medium mr-2">
                                                                    <div class="bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded flex items-center gap-1" title="Videos"><i class="ph-fill ph-video-camera"></i> <?php echo e($unit->videos->count()); ?></div>
                                                                    <div class="bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded flex items-center gap-1" title="Notes"><i class="ph-fill ph-file-pdf"></i> <?php echo e($unit->notes->count()); ?></div>
                                                                    <div class="bg-purple-50 text-purple-600 px-1.5 py-0.5 rounded flex items-center gap-1" title="Quizzes"><i class="ph-fill ph-game-controller"></i> <?php echo e($unit->quizzes->count()); ?></div>
                                                                </div>

                                                                <form action="<?php echo e(route('faculty.courses.units.destroy', [$course->id, $subject->id, $chapter->id, $unit->id])); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors"><i class="ph-fill ph-x-circle text-lg"></i></button>
                                                                </form>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/faculty/courses/curriculum.blade.php ENDPATH**/ ?>