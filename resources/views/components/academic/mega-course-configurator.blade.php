@props([
    'course',
    'categories',
    'backHref',
    'backLabel' => 'Back to courses',
    'formAction',
    'batchStoreUrl',
    'batchUpdateUrlPrefix',
    'extendedBatches' => false,
])

<div x-data="megaConfig({
    whatYouLearn: {{ $course->what_you_learn ? json_encode(is_string($course->what_you_learn) ? json_decode($course->what_you_learn) : $course->what_you_learn) : '[]' }},
    includes: {{ $course->includes ? json_encode(is_string($course->includes) ? json_decode($course->includes) : $course->includes) : '[]' }},
    faculty: {{ $course->faculty ? json_encode(is_string($course->faculty) ? json_decode($course->faculty) : $course->faculty) : '[]' }},
    isPublished: {{ $course->is_published ? 'true' : 'false' }},
    extendedBatches: {{ $extendedBatches ? 'true' : 'false' }},
    batchUpdatePrefix: @js(rtrim($batchUpdateUrlPrefix, '/') . '/'),
    batchStoreUrl: @js($batchStoreUrl),
    categoryId: @js($course->category_id),
})" class="pb-12 max-w-6xl">

    <a href="{{ $backHref }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-700 transition-colors mb-5">
        <i class="ph-bold ph-arrow-left"></i>
        {{ $backLabel }}
    </a>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-3 flex-wrap">
                {{ $course->name }}
                <span x-show="isPublished" x-cloak class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-primary-50 text-primary-700 border border-primary-200">Published</span>
                <span x-show="!isPublished" x-cloak class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
            </h2>
            <p class="text-sm text-slate-500 mt-1">Manage landing page details, instructors, and batches.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-3 px-4 py-2.5 bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-900 leading-none" x-text="isPublished ? 'Published' : 'Draft'"></p>
                    <p class="text-[10px] text-slate-500" x-text="isPublished ? 'Visible to students' : 'Hidden from public'"></p>
                </div>
                <button type="button"
                    role="switch"
                    :aria-checked="isPublished"
                    :aria-label="isPublished ? 'Published — visible to students' : 'Draft — hidden from public'"
                    @click="isPublished = !isPublished; $refs.mainForm.querySelector('[name=is_published_toggle]').value = isPublished ? '1' : '0'"
                    :class="{ 'is-on': isPublished }"
                    class="admin-toggle">
                    <span class="admin-toggle__thumb" aria-hidden="true"></span>
                </button>
            </div>
            <a href="{{ route('course.detail', $course->slug) }}" target="_blank" class="btn-secondary">
                <i class="ph-bold ph-eye"></i> Preview
            </a>
            <button type="button" @click="$refs.mainForm.submit()" class="btn-primary px-5 py-2.5 rounded-xl shadow-sm">
                <i class="ph-bold ph-floppy-disk"></i> Save changes
            </button>
        </div>
    </div>

    <div class="flex items-center border-b border-slate-200 mb-6 overflow-x-auto">
        <template x-for="tab in tabs" :key="tab.id">
            <button type="button" @click="activeTab = tab.id"
                    :class="activeTab === tab.id ? 'admin-tab-active' : 'admin-tab'"
                    class="whitespace-nowrap py-4 px-5 border-b-2 font-bold text-sm transition-colors">
                <span x-text="tab.name"></span>
            </button>
        </template>
    </div>

    <form x-ref="mainForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="what_you_learn" :value="JSON.stringify(whatYouLearn)">
        <input type="hidden" name="includes" :value="JSON.stringify(includes)">
        <input type="hidden" name="faculty_json" :value="JSON.stringify(faculty)">
        <input type="hidden" name="is_published_toggle" :value="isPublished ? '1' : '0'">

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl px-5 py-3.5 text-rose-700 font-semibold text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div x-show="activeTab === 'basic'" x-cloak class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">Core Product Identity</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Course title (public name) <span class="text-primary-500">*</span></label>
                        <input type="text" name="title" value="{{ $course->name }}" required class="admin-input">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Category</label>
                        <input type="text"
                            value="{{ $course->category?->name ?? '—' }}{{ $course->subcategory ? ' · ' . $course->subcategory->name : '' }}"
                            disabled
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 text-slate-600 py-2.5 px-4 text-sm cursor-not-allowed">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Short card description <span class="text-primary-500">*</span></label>
                    <textarea name="description" required rows="2" class="admin-input resize-none">{{ $course->description }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Full about description (landing page)</label>
                    <textarea name="about" rows="5" class="admin-input resize-none">{{ $course->about }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Language</label>
                        <input type="text" name="language" value="{{ $course->language ?? 'English' }}" class="admin-input">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1.5">Estimated duration</label>
                        <input type="text" name="duration" value="{{ $course->duration }}" placeholder="e.g. 12 Months" class="admin-input">
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'landing'" x-cloak style="display:none;" class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/80">
                    <div>
                        <h3 class="font-bold text-slate-900">What You'll Learn</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Grid items shown below the course description.</p>
                    </div>
                    <button type="button" @click="addWhatYouLearn()" class="btn-primary text-sm py-1.5 px-3 rounded-lg">
                        <i class="ph-bold ph-plus"></i> Add Point
                    </button>
                </div>
                <div class="p-5 space-y-3">
                    <template x-for="(item, index) in whatYouLearn" :key="index">
                        <div class="flex items-center gap-3 group">
                            <i class="ph-fill ph-check-circle text-primary-500 shrink-0 text-lg"></i>
                            <input type="text" x-model="whatYouLearn[index]" placeholder="e.g. Master concepts of Newton's Laws" class="flex-1 admin-input text-sm">
                            <button type="button" @click="whatYouLearn.splice(index, 1)" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-rose-50 rounded-lg transition-colors">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </template>
                    <p x-show="whatYouLearn.length === 0" class="text-center py-8 text-slate-500 text-sm">No learning points added yet.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/80 rounded-t-2xl">
                    <div>
                        <h3 class="font-bold text-slate-900">This Course Includes</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Feature list using emoji icons.</p>
                    </div>
                    <button type="button" @click="addInclude()" class="btn-primary text-sm py-1.5 px-3 rounded-lg">
                        <i class="ph-bold ph-plus"></i> Add Feature
                    </button>
                </div>
                <div class="p-5 space-y-3">
                    <template x-for="(inc, index) in includes" :key="index">
                        <div class="flex items-center gap-3">
                            <!-- Emoji Picker Dropdown -->
                            <div class="relative shrink-0" x-data="{ pickerOpen: false }">
                                <button type="button" @click="pickerOpen = !pickerOpen" @click.outside="pickerOpen = false" class="w-10 h-10 rounded-lg bg-primary-50 border border-primary-100 flex items-center justify-center text-lg transition-colors hover:bg-primary-100 focus:ring-2 focus:ring-primary-400">
                                    <span x-text="inc.icon || '📦'"></span>
                                </button>
                                
                                <div x-show="pickerOpen" style="display: none;" class="absolute top-12 left-0 z-50 shadow-xl rounded-xl border border-slate-200 bg-white overflow-hidden">
                                    <emoji-picker @emoji-click="inc.icon = $event.detail.unicode; pickerOpen = false" class="light"></emoji-picker>
                                </div>
                            </div>
                            
                            <input type="text" x-model="inc.text" placeholder="Description (e.g. 50+ Hours of Video Content)" class="flex-1 admin-input text-sm">
                            
                            <button type="button" @click="includes.splice(index, 1)" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-rose-50 rounded-lg transition-colors shrink-0">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </template>
                    <p x-show="includes.length === 0" class="text-center py-8 text-slate-500 text-sm">No features added yet.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-4 mb-5">Media &amp; settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Syllabus PDF</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 hover:border-primary-400 transition-colors">
                            <input type="file" name="syllabus_pdf" accept="application/pdf" class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                        @if($course->syllabus_pdf_path)
                            <p class="mt-2 text-xs text-primary-700 font-semibold flex items-center gap-1">
                                <i class="ph-fill ph-check-circle"></i> Active: {{ basename($course->syllabus_pdf_path) }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Custom hero image</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 hover:border-primary-400 transition-colors">
                            <input type="file" name="hero_image" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                        @if($course->hero_image)
                            <p class="mt-2 text-xs text-primary-700 font-semibold flex items-center gap-1">
                                <i class="ph-fill ph-check-circle"></i> Custom image active.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'faculty'" x-cloak style="display:none;" class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Teaching faculty</h3>
                    <p class="text-sm text-slate-500 mt-1">Instructor profiles shown on the public landing page.</p>
                </div>
                <button type="button" @click="addFaculty()" class="btn-primary text-sm py-2.5 px-4 rounded-xl">
                    <i class="ph-bold ph-plus"></i> Add profile
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <template x-for="(fac, index) in faculty" :key="index">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm relative p-5 hover:shadow-md transition-shadow">
                        <button type="button" @click="faculty.splice(index, 1)" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-600 hover:border-rose-200 shadow-sm transition-colors z-10">
                            <i class="ph-bold ph-trash text-sm"></i>
                        </button>
                        <div class="space-y-3 pr-6">
                            <input type="text" x-model="fac.name" placeholder="Full name" class="admin-input text-sm font-semibold">
                            <input type="text" x-model="fac.designation" placeholder="Designation" class="admin-input text-sm">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" x-model="fac.subject" placeholder="Subject" class="admin-input text-sm">
                                <input type="text" x-model="fac.experience" placeholder="Experience" class="admin-input text-sm">
                            </div>
                            <input type="text" x-model="fac.students" placeholder="Students taught" class="admin-input text-sm">
                        </div>
                    </div>
                </template>
                <div x-show="faculty.length === 0" class="col-span-full py-12 text-center text-slate-500 border-2 border-dashed border-slate-200 rounded-2xl bg-white">
                    <i class="ph-fill ph-chalkboard-teacher text-4xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-600">No faculty designated yet.</p>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'batches'" x-cloak style="display:none;" class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Batches &amp; cohorts</h3>
                    <p class="text-sm text-slate-500">Students enroll into individual batches derived from this master course.</p>
                </div>
                <button type="button" @click="showBatchModal = true" class="btn-primary text-sm py-2.5 px-4 rounded-xl">
                    <i class="ph-bold ph-plus"></i> Create batch
                </button>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                @if($course->batches->isEmpty())
                    <div class="p-12 text-center">
                        <i class="ph-fill ph-users-three text-5xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-semibold text-slate-600">No batches created yet.</p>
                        <button type="button" @click="showBatchModal = true" class="mt-4 btn-primary text-sm">
                            <i class="ph-bold ph-plus"></i> Create first batch
                        </button>
                    </div>
                @else
                    <div class="panel-table-wrap">
                        <table class="admin-table w-full text-left">
                            <thead>
                                <tr>
                                    <th>Batch name</th>
                                    <th>Price</th>
                                    <th class="text-center">Seats</th>
                                    @if($extendedBatches)
                                        <th class="text-center">Mode</th>
                                    @endif
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->batches as $batch)
                                    <tr>
                                        <td>
                                            <p class="font-semibold text-slate-800">{{ $batch->name }}</p>
                                            @if($batch->start_date)
                                                <p class="text-xs text-slate-500">Starts {{ $batch->start_date->format('M d, Y') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="font-semibold text-slate-800">₹{{ number_format($batch->price) }}</p>
                                            @if($batch->original_price && $batch->original_price > $batch->price)
                                                <p class="text-xs text-slate-400 line-through">₹{{ number_format($batch->original_price) }}</p>
                                            @endif
                                        </td>
                                        <td class="text-center text-slate-600">{{ $batch->filled_seats }} / {{ $batch->total_seats }}</td>
                                        @if($extendedBatches)
                                            <td class="text-center">
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-primary-50 text-primary-700 border border-primary-200">{{ $batch->mode ?? '—' }}</span>
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            @if($batch->status === 'active')
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-sky-50 text-sky-700 border border-sky-200">Active</span>
                                            @elseif($batch->status === 'filling_fast')
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200">Filling fast</span>
                                            @else
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">{{ str_replace('_', ' ', $batch->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <button type="button" @click="openEditBatch(@js($batch))" class="text-primary-700 hover:text-primary-700 font-semibold text-sm">Edit</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <div x-show="showBatchModal" x-cloak style="display:none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="closeBatchModal()"></div>
            
            <div class="relative bg-white border border-slate-200 rounded-2xl shadow-xl w-full max-w-xl z-10 text-left overflow-visible sm:my-8" @click.stop>
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 rounded-t-2xl">
                    <h3 class="text-slate-900 font-bold text-lg" x-text="editingBatchId ? 'Edit batch' : 'Create batch'"></h3>
                    <button type="button" @click="closeBatchModal()" class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Close">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <form :action="editingBatchId ? batchUpdatePrefix + editingBatchId : batchStoreUrl" method="POST">
                    @csrf
                    <template x-if="editingBatchId">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Batch name <span class="text-primary-500">*</span></label>
                            <input type="text" name="name" x-model="batchForm.name" required class="admin-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Price (₹) <span class="text-primary-500">*</span></label>
                            <input type="number" name="price" x-model="batchForm.price" required class="admin-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">MRP / original (₹)</label>
                            <input type="number" name="original_price" x-model="batchForm.original_price" class="admin-input">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Total seats <span class="text-primary-500">*</span></label>
                            <input type="number" name="total_seats" x-model="batchForm.total_seats" required class="admin-input">
                        </div>
                        @if($extendedBatches)
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Start date</label>
                                <input type="date" name="start_date" x-model="batchForm.start_date" class="admin-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Mode</label>
                                <select name="mode" x-model="batchForm.mode" class="admin-input">
                                    <option value="Online Live">Online Live</option>
                                    <option value="Offline">Offline / Classroom</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Recorded">Self-Paced / Recorded</option>
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Status</label>
                            <select name="status" x-model="batchForm.status" class="admin-input">
                                <option value="active">Active</option>
                                <option value="filling_fast">Filling fast</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <x-category-fields
                                :categories="$categories"
                                :category-id="$course->category_id"
                                :subcategory-required="true"
                                input-class="admin-input"
                            />
                        </div>
                        @if($extendedBatches)
                            <div class="sm:col-span-2">
                                <label class="flex items-start sm:items-center gap-3 cursor-pointer p-4 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-colors">
                                    <input type="checkbox" name="is_upcoming" :checked="batchForm.is_upcoming" @change="batchForm.is_upcoming = $event.target.checked" class="rounded mt-0.5 sm:mt-0 w-5 h-5 text-primary-500 focus:ring-primary-500 border-amber-300">
                                    <div>
                                        <p class="text-sm font-bold text-amber-900">Mark as &quot;Coming soon&quot;</p>
                                        <p class="text-xs text-amber-700 mt-0.5">Shows in upcoming batches; students can register interest instead of enrolling.</p>
                                    </div>
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col-reverse sm:flex-row gap-3 rounded-b-2xl">
                        <button type="button" @click="closeBatchModal()" class="flex-1 btn-secondary justify-center py-2.5">Cancel</button>
                        <button type="submit" class="flex-1 btn-primary justify-center py-2.5 rounded-xl">Save batch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1/index.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('megaConfig', (config) => ({
            activeTab: 'basic',
            tabs: [
                { id: 'basic',   name: 'Basic Info' },
                { id: 'landing', name: 'Landing Page' },
                { id: 'faculty', name: 'Faculty Displays' },
                { id: 'batches', name: 'Batches & Pricing' },
            ],
            isPublished: config.isPublished,
            whatYouLearn: config.whatYouLearn || [],
            includes:     config.includes     || [],
            faculty:      config.faculty      || [],
            extendedBatches: config.extendedBatches || false,
            batchUpdatePrefix: config.batchUpdatePrefix,
            batchStoreUrl: config.batchStoreUrl,
            categoryId: config.categoryId,
            showBatchModal: false,
            editingBatchId: null,
            batchForm: {
                name: '',
                price: '',
                original_price: '',
                total_seats: 100,
                status: 'active',
                category_id: config.categoryId,
                subcategory_id: '',
                mode: 'Online Live',
                start_date: '',
                is_upcoming: false,
            },

            addWhatYouLearn() { this.whatYouLearn.push(''); },
            addInclude()      { this.includes.push({ icon: '📦', text: '' }); },
            addFaculty()      { this.faculty.push({ name: '', designation: '', subject: '', experience: '', students: '' }); },

            openEditBatch(batch) {
                this.editingBatchId = batch.id;
                this.batchForm = {
                    name: batch.name,
                    price: batch.price,
                    original_price: batch.original_price || '',
                    total_seats: batch.total_seats,
                    status: batch.status || 'active',
                    category_id: batch.category_id || this.categoryId,
                    subcategory_id: batch.subcategory_id || '',
                    mode: batch.mode || 'Online Live',
                    start_date: batch.start_date ? String(batch.start_date).substring(0, 10) : '',
                    is_upcoming: Boolean(batch.is_upcoming),
                };
                this.showBatchModal = true;
            },

            closeBatchModal() {
                this.showBatchModal = false;
                this.editingBatchId = null;
                this.batchForm = {
                    name: '',
                    price: '',
                    original_price: '',
                    total_seats: 100,
                    status: 'active',
                    category_id: this.categoryId,
                    subcategory_id: '',
                    mode: 'Online Live',
                    start_date: '',
                    is_upcoming: false,
                };
            },
        }));
    });
</script>
@endpush
@endonce
