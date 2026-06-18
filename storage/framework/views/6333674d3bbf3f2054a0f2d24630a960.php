

<?php $__env->startSection('title', 'Meetings & Live Sessions'); ?>
<?php $__env->startSection('page_header', 'Meetings & Live Sessions'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="meetingManager()" x-init="init()">



<?php if(!$token): ?>
<div class="fp-banner-google">
    <div style="display:flex;align-items:center;gap:1rem;flex:1">
        <div class="fp-banner-icon">
            <svg viewBox="0 0 48 48" width="32" height="32"><path fill="#fff" d="M43.6 20H24v8h11.3C33.8 33.1 29.4 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c2.9 0 5.6 1.1 7.6 2.8L37.3 9C34 6.1 29.2 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20c11 0 19.6-7.8 19.6-20 0-1.3-.1-2-.1-4z"/></svg>
        </div>
        <div>
            <h4>Link your Google Account to schedule Meet sessions</h4>
            <p>Required to generate Google Meet links and send calendar invites automatically. Free, uses Google Calendar API.</p>
        </div>
    </div>
    <a href="<?php echo e(route('faculty.google.redirect')); ?>" class="fp-banner-btn">
        🔗&nbsp; Link Google Account
    </a>
</div>
<?php else: ?>
<div class="fp-banner-success" style="margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:0.875rem;flex:1">
        <div class="fp-linked-icon"><i class="ph-bold ph-check"></i></div>
        <div>
            <strong>Google Account Linked</strong><br>
            <span><?php echo e($token->google_email); ?></span>
        </div>
    </div>
    <form method="POST" action="<?php echo e(route('faculty.google.unlink')); ?>" onsubmit="return confirm('Unlink your Google account? You won\'t be able to schedule meetings.')">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" style="font-size:.8rem;color:#64748b;font-weight:700;border:none;background:none;cursor:pointer;padding:.25rem .5rem;border-radius:.5rem;" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#64748b'">Unlink</button>
    </form>
</div>
<?php endif; ?>


<div class="fp-page-header">
    <div>
        <h2 class="fp-page-title">Scheduled Meetings</h2>
        <p class="fp-page-subtitle"><?php echo e($upcoming->count()); ?> upcoming &middot; <?php echo e($past->count()); ?> past</p>
    </div>
    <button @click="openCreate()" <?php echo e(!$token ? 'disabled' : ''); ?>

        class="fp-btn-blue"
        style="<?php echo e(!$token ? 'opacity:.5;cursor:not-allowed;' : ''); ?>"
        title="<?php echo e(!$token ? 'Link your Google account first' : 'Schedule a new meeting'); ?>">
        <i class="ph-bold ph-video-camera-plus" style="font-size:1.1rem"></i>
        Schedule Meeting
    </button>
</div>


<?php if($upcoming->isEmpty()): ?>
<div class="fp-empty" style="margin-bottom:1.5rem;">
    <div class="fp-empty-icon">
        <i class="ph-bold ph-video-camera"></i>
    </div>
    <h3>No Upcoming Meetings</h3>
    <p>Schedule a Google Meet session to get started. You can invite your entire batch or have a one-on-one session with a student.</p>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:1rem;margin-bottom:2rem;">
    <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="fp-meeting-card">
        <div class="fp-meeting-card-bar"></div>
        <div class="fp-meeting-card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;margin-bottom:.875rem;">
                <div style="flex:1">
                    <div style="display:flex;gap:.4rem;margin-bottom:.4rem;flex-wrap:wrap;">
                        <span class="fp-badge <?php echo e($meeting->type === 'batch' ? 'batch' : 'one-on-one'); ?>">
                            <?php echo e($meeting->type === 'batch' ? '👥 Batch' : '👤 1-on-1'); ?>

                        </span>
                        <span class="fp-badge active">UPCOMING</span>
                    </div>
                    <h3 style="font-weight:800;color:#0f172a;font-size:1.1rem;margin:0 0 4px;line-height:1.3;"><?php echo e($meeting->title); ?></h3>
                    <?php if($meeting->description): ?>
                        <p style="color:#64748b;font-size:.8rem;margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;"><?php echo e($meeting->description); ?></p>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(route('faculty.meetings.destroy', $meeting)); ?>"
                    onsubmit="return confirm('Cancel this meeting? All attendees will be notified.')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="fp-btn-danger" style="padding:.35rem .6rem;font-size:.85rem;">
                        <i class="ph-bold ph-x-circle"></i>
                    </button>
                </form>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1rem;">
                <div style="background:#f8fafc;border-radius:.875rem;padding:.875rem;">
                    <div style="font-size:.65rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">📅 Date & Time</div>
                    <div style="font-weight:700;color:#1e293b;font-size:.875rem;"><?php echo e($meeting->start_at->format('d M Y')); ?></div>
                    <div style="color:#64748b;font-size:.75rem;"><?php echo e($meeting->start_at->format('h:i A')); ?> – <?php echo e($meeting->end_at->format('h:i A')); ?></div>
                </div>
                <div style="background:#f8fafc;border-radius:.875rem;padding:.875rem;">
                    <div style="font-size:.65rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">👥 Invitees</div>
                    <div style="font-weight:700;color:#1e293b;font-size:.875rem;"><?php echo e($meeting->invitees->count()); ?> student<?php echo e($meeting->invitees->count() !== 1 ? 's' : ''); ?></div>
                    <?php if($meeting->batch): ?><div style="color:#64748b;font-size:.75rem;"><?php echo e($meeting->batch->name); ?></div><?php endif; ?>
                </div>
            </div>

            <?php if($meeting->meet_link): ?>
            <a href="<?php echo e($meeting->meet_link); ?>" target="_blank" class="fp-btn-blue" style="width:100%;justify-content:center;">
                <i class="ph-fill ph-video-camera"></i> Join Google Meet
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($past->isNotEmpty()): ?>
<div>
    <p style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#64748b;margin-bottom:.75rem;">Past &amp; Cancelled Meetings</p>
    <div class="fp-table-wrap">
        <table class="fp-table">
            <thead>
                <tr>
                    <th>Meeting</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Invitees</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $past; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:#334155;"><?php echo e($meeting->title); ?></div>
                        <?php if($meeting->meet_link && $meeting->status !== 'cancelled'): ?>
                        <a href="<?php echo e($meeting->meet_link); ?>" target="_blank" style="font-size:.75rem;color:#3b82f6;">Open Link ↗</a>
                        <?php endif; ?>
                    </td>
                    <td style="color:#64748b;">
                        <?php echo e($meeting->start_at->format('d M Y')); ?><br>
                        <span style="font-size:.75rem;"><?php echo e($meeting->start_at->format('h:i A')); ?></span>
                    </td>
                    <td><span class="fp-badge <?php echo e($meeting->type === 'batch' ? 'batch' : 'one-on-one'); ?>"><?php echo e($meeting->type === 'batch' ? 'Batch' : '1-on-1'); ?></span></td>
                    <td><?php echo e($meeting->invitees->count()); ?></td>
                    <td>
                        <span class="fp-badge <?php echo e($meeting->status === 'cancelled' ? 'cancelled' : 'online'); ?>">
                            <?php echo e($meeting->status === 'cancelled' ? 'Cancelled' : 'Completed'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>


<div x-show="showModal" x-transition.opacity class="fp-modal-overlay" style="display:none">
    <div @click.outside="showModal=false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="fp-modal">

        
        <div class="fp-modal-header">
            <div>
                <h3 class="fp-modal-title">📅 Schedule a Meeting</h3>
                <p class="fp-modal-subtitle">Invite students to a Google Meet session</p>
            </div>
            <button type="button" @click="showModal=false" class="fp-modal-close">✕</button>
        </div>

        
        <form method="POST" action="<?php echo e(route('faculty.meetings.store')); ?>" class="fp-modal-body" style="display:flex;flex-direction:column;gap:1.1rem;" @submit="submitting=true">
            <?php echo csrf_field(); ?>

            
            <div>
                <label class="fp-label">Meeting Title <span style="color:#ef4444">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Doubt Clearing – Chapter 5" class="fp-input">
            </div>

            
            <div>
                <label class="fp-label">Description <span style="color:#94a3b8;font-weight:400">(optional)</span></label>
                <textarea name="description" class="fp-textarea" placeholder="What will you cover in this session?"></textarea>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="fp-label">Start Date &amp; Time <span style="color:#ef4444">*</span></label>
                    <input type="datetime-local" name="start_at" required min="<?php echo e(now()->addMinutes(5)->format('Y-m-d\TH:i')); ?>" class="fp-input">
                </div>
                <div>
                    <label class="fp-label">Duration</label>
                    <select name="duration" class="fp-select">
                        <option value="30">30 minutes</option>
                        <option value="60" selected>1 hour</option>
                        <option value="90">1.5 hours</option>
                        <option value="120">2 hours</option>
                        <option value="180">3 hours</option>
                    </select>
                </div>
            </div>

            
            <div>
                <label class="fp-label">Meeting Type <span style="color:#ef4444">*</span></label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <label class="fp-type-option" :class="meetType==='batch' ? 'selected-primary' : ''"
                        @click="meetType='batch'">
                        <input type="radio" name="type" value="batch" x-model="meetType">
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#1e293b;">👥 Entire Batch</div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:2px;">All enrolled students invited</div>
                        </div>
                    </label>
                    <label class="fp-type-option" :class="meetType==='one_on_one' ? 'selected-blue' : ''"
                        @click="meetType='one_on_one'">
                        <input type="radio" name="type" value="one_on_one" x-model="meetType">
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#1e293b;">👤 One-on-One</div>
                            <div style="font-size:.75rem;color:#64748b;margin-top:2px;">Pick specific student(s)</div>
                        </div>
                    </label>
                </div>
            </div>

            
            <div x-show="meetType === 'batch'" style="display:flex;flex-direction:column;gap:.875rem;">
                <div>
                    <label class="fp-label">Course <span style="color:#ef4444">*</span></label>
                    <select name="course_id" x-model="selectedCourse" @change="loadBatches()" class="fp-select">
                        <option value="">— Select a course —</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div x-show="batches.length > 0">
                    <label class="fp-label">Batch <span style="color:#ef4444">*</span></label>
                    <select name="batch_id" x-model="selectedBatch" @change="loadStudentsForBatch()" class="fp-select">
                        <option value="">— Select a batch —</option>
                        <template x-for="b in batches" :key="b.id">
                            <option :value="b.id" x-text="b.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="selectedBatch && batchStudentCount !== null"
                    style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:.875rem;padding:.875rem 1rem;font-size:.875rem;color:#1e40af;font-weight:600;">
                    <i class="ph-fill ph-users" style="margin-right:.5rem;"></i>
                    <span x-text="batchStudentCount + ' active student(s) will be invited to this session'"></span>
                </div>
            </div>

            
            <div x-show="meetType === 'one_on_one'" style="display:flex;flex-direction:column;gap:.875rem;">
                <div>
                    <label class="fp-label">Course</label>
                    <select x-model="selectedCourse" @change="loadBatches()" name="course_id" class="fp-select">
                        <option value="">— Select a course —</option>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div x-show="batches.length > 0">
                    <label class="fp-label">Batch</label>
                    <select x-model="selectedBatch" @change="loadStudentsForBatch()" class="fp-select">
                        <option value="">— Select a batch —</option>
                        <template x-for="b in batches" :key="b.id">
                            <option :value="b.id" x-text="b.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="students.length > 0">
                    <label class="fp-label">Select Student(s) <span style="color:#ef4444">*</span></label>
                    <div style="border:1.5px solid #e2e8f0;border-radius:.875rem;overflow:hidden;max-height:180px;overflow-y:auto;">
                        <template x-for="s in students" :key="s.id">
                            <label style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;cursor:pointer;border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                <input type="checkbox" name="student_ids[]" :value="s.id" style="accent-color:#f97316;">
                                <div>
                                    <div style="font-weight:600;font-size:.875rem;color:#1e293b;" x-text="s.name"></div>
                                    <div style="font-size:.75rem;color:#64748b;" x-text="s.email"></div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            
            <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                <button type="button" @click="showModal=false" class="fp-btn-ghost" style="flex:1;justify-content:center;">Cancel</button>
                <button type="submit" :disabled="submitting" class="fp-btn-blue" style="flex:1;justify-content:center;">
                    <span x-show="!submitting"><i class="ph-bold ph-video-camera" style="margin-right:.4rem;"></i>Create &amp; Send Invites</span>
                    <span x-show="submitting">Creating meeting…</span>
                </button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
function meetingManager() {
    return {
        showModal: false,
        meetType: 'batch',
        selectedCourse: '',
        selectedBatch: '',
        batches: [],
        students: [],
        batchStudentCount: null,
        submitting: false,
        init() {},
        openCreate() {
            this.showModal = true;
            this.meetType = 'batch';
            this.selectedCourse = '';
            this.selectedBatch = '';
            this.batches = [];
            this.students = [];
            this.batchStudentCount = null;
            this.submitting = false;
        },
        async loadBatches() {
            this.selectedBatch = '';
            this.batches = [];
            this.students = [];
            this.batchStudentCount = null;
            if (!this.selectedCourse) return;
            const r = await fetch(`/faculty/meetings/courses/${this.selectedCourse}/batches`);
            this.batches = await r.json();
        },
        async loadStudentsForBatch() {
            this.batchStudentCount = null;
            this.students = [];
            if (!this.selectedBatch) return;
            const r = await fetch(`/faculty/meetings/batches/${this.selectedBatch}/students`);
            this.students = await r.json();
            this.batchStudentCount = this.students.length;
        },
    };
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.faculty', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Lama Projects\toppershope-website\resources\views/faculty/meetings/index.blade.php ENDPATH**/ ?>