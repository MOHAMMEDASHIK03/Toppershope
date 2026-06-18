

<?php $__env->startSection('title', 'Content Manager: ' . $course->name); ?>
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
        <a href="<?php echo e(route('faculty.courses.curriculum', $course->id)); ?>" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            Curriculum Builder
        </a>
        <a href="<?php echo e(route('faculty.courses.content.index', $course->id)); ?>" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm">
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

<!-- Content Manager -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8" x-data="{ 
    selectedUnit: null, 
    unitName: '',
    uploadType: 'video', // 'video' or 'note'
    
    selectUnit(id, name) {
        this.selectedUnit = id;
        this.unitName = name;
    }
}">
    <div class="flex h-[600px]">
        
        <!-- Left Sidebar: Curriculum Tree -->
        <div class="w-1/3 border-r border-slate-200 bg-slate-50 flex flex-col h-full overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-white">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-bold ph-list-dashes text-primary"></i> Topics Content
                </h3>
                <p class="text-xs text-slate-500 mt-1">Select a unit to manage its videos and notes.</p>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $course->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div x-data="{ expanded: false }" class="bg-white border text-sm border-slate-200 rounded-lg shadow-sm">
                        
                        <div @click="expanded = !expanded" class="px-3 py-2 border-b border-slate-100 flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <i class="ph-bold ph-caret-right text-slate-400 transition-transform" :class="expanded ? 'rotate-90' : ''"></i>
                            <span class="font-bold text-slate-700"><?php echo e($subject->name); ?></span>
                        </div>

                        <div x-show="expanded" x-collapse>
                            <?php $__empty_2 = true; $__currentLoopData = $subject->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <div x-data="{ chapExpanded: false }" class="border-l border-slate-100 ml-3">
                                    <div @click="chapExpanded = !chapExpanded" class="px-3 py-1.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                                        <i class="ph-bold ph-caret-right text-slate-300 text-xs transition-transform" :class="chapExpanded ? 'rotate-90' : ''"></i>
                                        <span class="font-semibold text-slate-600 text-[13px]"><?php echo e($chapter->name); ?></span>
                                    </div>

                                    <div x-show="chapExpanded" x-collapse class="pl-7 pb-2 space-y-1">
                                        <?php $__empty_3 = true; $__currentLoopData = $chapter->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                            <div @click="selectUnit(<?php echo e($unit->id); ?>, '<?php echo e(addslashes($unit->name)); ?>')"
                                                 class="px-2 py-1.5 rounded cursor-pointer text-xs flex items-center justify-between transition-colors m-1"
                                                 :class="selectedUnit == <?php echo e($unit->id); ?> ? 'bg-primary/10 text-primary font-bold' : 'text-slate-500 hover:bg-slate-100'">
                                                <span class="flex items-center gap-1.5">
                                                    <div class="w-1.5 h-1.5 rounded-full" :class="selectedUnit == <?php echo e($unit->id); ?> ? 'bg-primary' : 'bg-slate-300'"></div>
                                                    <?php echo e($unit->name); ?>

                                                </span>
                                                <div class="flex gap-1 text-[10px]">
                                                    <span class="bg-orange-50 text-orange-600 px-1 rounded flex items-center"><i class="ph-fill ph-video-camera mr-0.5"></i> <?php echo e($unit->videos->count()); ?></span>
                                                    <span class="bg-orange-50 text-orange-600 px-1 rounded flex items-center"><i class="ph-fill ph-file-pdf mr-0.5"></i> <?php echo e($unit->notes->count()); ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                                            <div class="text-[11px] text-slate-400 px-2 py-1 italic">No units in chapter</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <div class="text-[11px] text-slate-400 px-3 py-2 italic ml-4">No chapters yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-400 text-sm italic">
                        No subjects found. Build your curriculum first.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Side: Content Area -->
        <div class="w-2/3 bg-white flex flex-col h-full">
            
            <!-- Empty State -->
            <div x-show="!selectedUnit" class="flex-1 flex flex-col items-center justify-center text-center p-8">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4 border border-slate-100 shadow-sm">
                    <i class="ph-fill ph-cursor-click"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Select a Topic</h3>
                <p class="text-slate-500 max-w-sm">Choose a unit from the curriculum tree on the left to start adding and managing learning materials.</p>
            </div>

            <!-- Content Manager Display -->
            <div x-show="selectedUnit" class="flex flex-col h-full hidden" :class="{ 'hidden': !selectedUnit }">
                
                <!-- Topic Header -->
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-white z-10">
                    <div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wider mb-1 block">Managing Topic Content</span>
                        <h2 class="text-xl font-bold text-slate-900" x-text="unitName"></h2>
                    </div>
                    
                    <!-- Upload Tabs -->
                    <div class="flex bg-slate-100 p-1 rounded-lg">
                        <button @click="uploadType = 'video'" class="px-3 py-1.5 text-sm font-semibold rounded-md transition-colors flex items-center gap-1.5" :class="uploadType === 'video' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                            <i class="ph-fill ph-video-camera"></i> Videos
                        </button>
                        <button @click="uploadType = 'note'" class="px-3 py-1.5 text-sm font-semibold rounded-md transition-colors flex items-center gap-1.5" :class="uploadType === 'note' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'">
                            <i class="ph-fill ph-file-pdf"></i> PDF Notes
                        </button>
                    </div>
                </div>

                <!-- Forms and Display Area -->
                <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6">

                    <!-- SUCCESS/ERROR MESSAGES -->
                    <?php if($errors->any()): ?>
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
                            <i class="ph-fill ph-x-circle text-xl mt-0.5"></i>
                            <ul class="list-disc list-inside text-sm font-medium">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- VIDEO SECTION -->
                    <div x-show="uploadType === 'video'">
                        <div class="bg-white border border-primary/20 shadow-sm rounded-xl p-5 mb-6" x-data="{ videoSource: 'upload' }">
                            <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2"><i class="ph-fill ph-plus-circle text-primary"></i> Embed New Video</h4>
                            
                            <form :action="`/faculty/course/<?php echo e($course->id); ?>/units/${selectedUnit}/videos`" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Video Title</label>
                                        <input type="text" name="title" required placeholder="e.g. Introduction to Velocity" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Description (Max 300 Chars)</label>
                                        <textarea name="description" maxlength="300" rows="2" placeholder="Brief summary of the video content..." class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors resize-none"></textarea>
                                    </div>

                                    <!-- Video Source Toggle -->
                                    <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100 mb-2">
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Video Source</label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                <input type="radio" value="upload" x-model="videoSource" class="text-primary focus:ring-primary h-4 w-4">
                                                <span class="font-medium text-slate-700">Upload Video File</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                <input type="radio" value="url" x-model="videoSource" class="text-primary focus:ring-primary h-4 w-4">
                                                <span class="font-medium text-slate-700">External Video URL</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Conditional Inputs -->
                                    <div class="md:col-span-1" x-show="videoSource === 'upload'" x-transition>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Video File (MP4, MKV - Max 500MB)</label>
                                        <input type="file" name="video_file" accept=".mp4,.mkv,.avi,.mov" :required="videoSource === 'upload'" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 outline-none file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                    </div>

                                    <div class="md:col-span-1" x-show="videoSource === 'url'" x-transition style="display: none;">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Video Source URL (YouTube/S3)</label>
                                        <div class="relative">
                                            <i class="ph-bold ph-link absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="url" name="video_url" :required="videoSource === 'url'" placeholder="https://..." class="w-full pl-9 text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                        </div>
                                    </div>

                                    <div class="col-span-2 md:col-span-1">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Duration (Minutes)</label>
                                        <div class="relative w-full md:w-1/2">
                                            <i class="ph-bold ph-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="number" name="duration_minutes" min="1" required placeholder="45" class="w-full pl-9 text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-lg shadow-sm shadow-orange-500/20 transition-all text-sm">Save Video</button>
                                </div>
                            </form>
                        </div>

                        <!-- Render Uploaded Videos -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-slate-600 text-xs uppercase tracking-wider pl-1">Uploaded Videos in Topic</h4>
                            
                            <?php $hasVideos = false; ?>
                            <?php $__currentLoopData = $course->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subject->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $chapter->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unitLoop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $unitLoop->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $hasVideos = true; ?>
                                            <div x-show="selectedUnit == <?php echo e($unitLoop->id); ?>" class="bg-white border border-slate-200 rounded-xl p-4 flex gap-4 hidden" :class="{ 'hidden': selectedUnit != <?php echo e($unitLoop->id); ?> }">
                                                <div class="w-32 h-20 bg-slate-800 rounded-lg flex items-center justify-center flex-shrink-0 relative overflow-hidden group border border-slate-900 shadow-sm">
                                                    <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: url('https://images.unsplash.com/photo-1610484826967-09c5720778c7?q=80&w=200&auto=format&fit=crop');"></div>
                                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center z-10 group-hover:bg-white/30 transition-colors">
                                                        <i class="ph-fill ph-play text-white ml-0.5"></i>
                                                    </div>
                                                    <div class="absolute bottom-1 right-1.5 bg-black/70 text-white text-[9px] font-bold px-1 rounded z-10"><?php echo e($video->duration_minutes); ?>m</div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <h5 class="font-bold text-slate-800 truncate"><?php echo e($video->title); ?></h5>
                                                        <?php if($video->video_path): ?>
                                                            <span class="bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase">File</span>
                                                        <?php else: ?>
                                                            <span class="bg-blue-100 text-blue-700 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase">Link</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?php echo e($video->description ?? 'No description provided.'); ?></p>
                                                    <div class="mt-2 flex items-center gap-3">
                                                        <a href="<?php echo e($video->video_path ? Storage::url($video->video_path) : $video->video_url); ?>" target="_blank" class="text-[10px] font-bold text-primary hover:underline flex items-center gap-1"><i class="ph-bold ph-arrow-square-out"></i> Open Video</a>
                                                        <div class="text-[10px] text-slate-400 font-medium">Added <?php echo e($video->created_at->diffForHumans()); ?></div>
                                                    </div>
                                                </div>
                                                <div class="flex items-start">
                                                    <form action="<?php echo e(route('faculty.courses.videos.destroy', [$course->id, $video->id])); ?>" method="POST" onsubmit="return confirm('Delete this video?');">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Placeholder if specific branch has no videos -->
                            <div class="text-center py-6 bg-slate-50/50 border border-dashed border-slate-200 rounded-xl text-slate-400 text-sm italic">
                                Use the form above to add lecture videos.
                            </div>
                        </div>
                    </div>

                    <!-- PDF NOTES SECTION -->
                    <div x-show="uploadType === 'note'" style="display: none;">
                         <div class="bg-white border border-orange-100 shadow-sm rounded-xl p-5 mb-6">
                            <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2"><i class="ph-fill ph-file-arrow-up text-orange-500"></i> Upload Topic Notes</h4>
                            
                            <form :action="`/faculty/course/<?php echo e($course->id); ?>/units/${selectedUnit}/notes`" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="grid grid-cols-1 gap-4 mb-4">
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Notes Document Title</label>
                                        <input type="text" name="title" required placeholder="e.g. Chapter 1 Summary PDF" class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Description (Max 300 Chars)</label>
                                        <textarea name="description" maxlength="300" rows="2" placeholder="Brief summary of the document..." class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-colors resize-none"></textarea>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs font-bold text-slate-700 mb-1">PDF File (Max 10MB)</label>
                                        <input type="file" name="file" accept=".pdf" required class="w-full text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 outline-none file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-colors">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-lg shadow-sm shadow-orange-500/20 transition-all text-sm">Upload PDF</button>
                                </div>
                            </form>
                        </div>

                        <!-- Render Uploaded Notes -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-slate-600 text-xs uppercase tracking-wider pl-1">Uploaded PDF Files</h4>
                            
                             <?php $__currentLoopData = $course->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subject->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $chapter->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unitLoop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $unitLoop->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div x-show="selectedUnit == <?php echo e($unitLoop->id); ?>" class="bg-white border border-slate-200 rounded-xl p-4 flex gap-4 hidden" :class="{ 'hidden': selectedUnit != <?php echo e($unitLoop->id); ?> }">
                                                <div class="w-12 h-14 bg-red-50 text-red-500 rounded flex flex-col items-center justify-center flex-shrink-0 border border-red-100">
                                                    <i class="ph-fill ph-file-pdf text-2xl"></i>
                                                    <span class="text-[9px] font-bold uppercase tracking-wider mt-0.5">PDF</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="font-bold text-slate-800 truncate"><?php echo e($note->title); ?></h5>
                                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?php echo e($note->description ?? 'No description provided.'); ?></p>
                                                    <div class="flex items-center gap-3 mt-2">
                                                        <a href="<?php echo e(Storage::url($note->file_path)); ?>" target="_blank" class="text-[10px] font-bold text-primary hover:underline flex items-center gap-1"><i class="ph-bold ph-arrow-square-out"></i> Open Document</a>
                                                        <div class="text-[10px] text-slate-400 font-medium">Uploaded <?php echo e($note->created_at->format('M d, Y')); ?></div>
                                                    </div>
                                                </div>
                                                <div class="flex items-start">
                                                    <form action="<?php echo e(route('faculty.courses.notes.destroy', [$course->id, $note->id])); ?>" method="POST" onsubmit="return confirm('Delete this document?');">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="text-center py-6 bg-slate-50/50 border border-dashed border-slate-200 rounded-xl text-slate-400 text-sm italic">
                                Use the form above to upload study materials.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/faculty/courses/content.blade.php ENDPATH**/ ?>