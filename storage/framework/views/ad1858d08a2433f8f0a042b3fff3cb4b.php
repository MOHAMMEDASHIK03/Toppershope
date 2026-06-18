
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Welcome Back, ' . auth()->user()->name); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Enrolled Courses','value' => $totalCourses,'hint' => 'Active enrollments','accent' => 'indigo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Enrolled Courses','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalCourses),'hint' => 'Active enrollments','accent' => 'indigo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Quizzes Taken','value' => $totalQuizzes,'hint' => 'Tests completed','accent' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Quizzes Taken','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalQuizzes),'hint' => 'Tests completed','accent' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Average Score','value' => number_format($avgScore, 1),'hint' => 'Across all tests','accent' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Average Score','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($avgScore, 1)),'hint' => 'Across all tests','accent' => 'amber']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
</div>

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-slate-900">Continue Learning</h2>
        <a href="<?php echo e(route('student.my-courses')); ?>" class="text-orange-600 text-sm font-semibold hover:text-orange-700 transition-colors">View All →</a>
    </div>

    <?php if($enrollments->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__currentLoopData = $enrollments->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('student.my-courses.show', $enrollment->id)); ?>" class="bg-white border border-slate-200 rounded-xl overflow-hidden hover:border-orange-200 hover:shadow-md transition-all group block shadow-sm">
                    <div class="h-36 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center relative overflow-hidden">
                        <?php if($enrollment->batch->course->thumbnail): ?>
                            <img src="<?php echo e(asset('storage/' . $enrollment->batch->course->thumbnail)); ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="text-4xl font-bold text-indigo-300"><?php echo e(strtoupper(substr($enrollment->batch->course->name, 0, 2))); ?></div>
                        <?php endif; ?>
                        <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-slate-900/70 text-[10px] font-semibold text-white">
                            <?php echo e($enrollment->batch->name); ?>

                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-slate-900 text-sm mb-1 group-hover:text-orange-600 transition-colors"><?php echo e($enrollment->batch->course->name); ?></h3>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <?php if($enrollment->batch->course->category): ?>
                                <span class="px-2 py-0.5 rounded bg-orange-50 text-indigo-700 font-semibold"><?php echo e(strtoupper($enrollment->batch->course->category->name)); ?></span>
                            <?php endif; ?>
                            <?php if($enrollment->batch->mentor_name): ?>
                                <span><?php echo e($enrollment->batch->mentor_name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-semibold border border-emerald-200">Active</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal99089f8e2ef4184d7d35db81d60c6521 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.empty-state','data' => ['title' => 'No courses yet','description' => 'Browse our catalog and enroll in your first course to start learning.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No courses yet','description' => 'Browse our catalog and enroll in your first course to start learning.']); ?>
             <?php $__env->slot('action', null, []); ?> 
                <a href="<?php echo e(route('student.catalog')); ?>" class="btn-primary mt-4 inline-flex">Browse Courses →</a>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $attributes = $__attributesOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__attributesOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521)): ?>
<?php $component = $__componentOriginal99089f8e2ef4184d7d35db81d60c6521; ?>
<?php unset($__componentOriginal99089f8e2ef4184d7d35db81d60c6521); ?>
<?php endif; ?>
    <?php endif; ?>
</div>

<?php if($recentQuizzes->count() > 0): ?>
<div>
    <h2 class="text-lg font-bold text-slate-900 mb-4">Recent Test Results</h2>
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Date</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentQuizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="font-semibold"><?php echo e($attempt->quiz->title ?? 'Quiz'); ?></td>
                        <td>
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <?php echo e($attempt->score); ?> pts
                            </span>
                        </td>
                        <td class="text-slate-500"><?php echo e($attempt->created_at->diffForHumans()); ?></td>
                        <td class="text-right">
                            <a href="<?php echo e(route('student.quiz.results', $attempt->quiz_id)); ?>" class="text-orange-600 text-xs font-semibold hover:text-orange-700">View Results →</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('student.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/student/dashboard.blade.php ENDPATH**/ ?>