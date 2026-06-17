@php
    $action   = $editing ? route('ads.campaigns.update', $campaign) : route('ads.campaigns.store');
    $method   = $editing ? 'PUT' : 'POST';
    $v        = fn($key, $default = '') => old($key, $editing && $campaign ? ($campaign->$key ?? $default) : $default);
    $vColor   = fn($key, $default) => old($key, $editing && $campaign ? ($campaign->$key ?? $default) : $default);
    $features = $editing && $campaign ? implode("\n", $campaign->features ?? []) : old('features', '');

    // Pre-filled JSON data for Alpine
    $initStats = json_encode(old('stats',
        $editing && $campaign && $campaign->stats
            ? $campaign->stats
            : [['label'=>'','value'=>''],['label'=>'','value'=>'']]
    ));
    $initTestimonials = json_encode(old('testimonials',
        $editing && $campaign && $campaign->testimonials
            ? $campaign->testimonials
            : [['name'=>'','course'=>'','rank'=>'','quote'=>'']]
    ));
    $initFaqs = json_encode(old('faqs',
        $editing && $campaign && $campaign->faqs
            ? $campaign->faqs
            : [['question'=>'','answer'=>'']]
    ));
@endphp

<div class="py-4 max-w-4xl">
<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
@csrf @method($method)

{{-- ════════════════════════════════════════════════════
     SECTION 1 — BASIC INFO
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500 text-sm font-black">1</div>
        <h2 class="font-black text-slate-900 text-sm">Basic Information</h2>
    </div>
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Campaign Title *</label>
            <input type="text" name="title" value="{{ $v('title') }}" required
                   placeholder="e.g. IIT JEE 2026 Crash Course"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 hover:bg-white transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Subtitle</label>
            <input type="text" name="subtitle" value="{{ $v('subtitle') }}"
                   placeholder="Short tagline shown below the title"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Course Name</label>
            <input type="text" name="course_name" value="{{ $v('course_name') }}"
                   placeholder="e.g. NEET UG Intensive"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Badge Text</label>
            <input type="text" name="badge_text" value="{{ $v('badge_text') }}"
                   placeholder="Early Bird"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Hero Image</label>
            @if($editing && $campaign?->hero_image)
                <img src="{{ Storage::url($campaign->hero_image) }}" class="w-full h-20 object-cover rounded-xl border border-slate-200 mb-2">
            @endif
            <input type="file" name="hero_image" accept="image/*"
                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold hover:file:bg-orange-100 transition">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Description</label>
            <textarea name="description" rows="4" placeholder="Full campaign description shown on the landing page..."
                      class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition resize-none">{{ $v('description') }}</textarea>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Key Features <span class="font-normal normal-case text-slate-400 ml-1">— one per line</span></label>
            <textarea name="features" rows="5"
                      placeholder="300+ HD Video Lectures&#10;Live Doubt Sessions&#10;Mock Tests &amp; Analytics&#10;Study Materials PDF"
                      class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition resize-none">{{ $features }}</textarea>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 2 — PRICING & CTA
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500 text-sm font-black">2</div>
        <h2 class="font-black text-slate-900 text-sm">Pricing & CTA</h2>
    </div>
    <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Fee (₹)</label>
            <input type="number" name="fee" value="{{ $v('fee') }}" placeholder="14999"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Original (₹)</label>
            <input type="number" name="original_fee" value="{{ $v('original_fee') }}" placeholder="24999"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Enrol Button</label>
            <input type="text" name="cta_button_text" value="{{ $v('cta_button_text', 'Enrol Now') }}"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Interest Button</label>
            <input type="text" name="interest_button_text" value="{{ $v('interest_button_text', "I'm Interested") }}"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div class="col-span-2">
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Brochure PDF</label>
            @if($editing && $campaign?->brochure_pdf)
                <p class="text-xs text-green-600 font-semibold mb-1.5">✓ Brochure already uploaded</p>
            @endif
            <input type="file" name="brochure_pdf" accept=".pdf"
                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold hover:file:bg-orange-100 transition">
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 3 — STATS STRIP
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
     x-data="{ stats: {{ $initStats }} }">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500 text-sm font-black">3</div>
            <h2 class="font-black text-slate-900 text-sm">Stats Strip</h2>
            <span class="text-xs text-slate-400 font-normal ml-1">— shown as a highlight bar on the landing page</span>
        </div>
        <button type="button" @click="stats.push({label:'',value:''})"
                class="text-xs font-bold text-orange-500 hover:text-orange-700 flex items-center gap-1">
            + Add Stat
        </button>
    </div>
    <div class="p-6 space-y-3">
        <template x-for="(stat, i) in stats" :key="i">
            <div class="flex items-center gap-3">
                <div class="flex-1 grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Value</label>
                        <input type="text" :name="`stats[${i}][value]`" x-model="stat.value"
                               placeholder="500+" 
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Label</label>
                        <input type="text" :name="`stats[${i}][label]`" x-model="stat.label"
                               placeholder="Students Enrolled"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
                    </div>
                </div>
                <button type="button" @click="stats.splice(i,1)"
                        class="mt-5 w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition flex-shrink-0">✕</button>
            </div>
        </template>
        <p class="text-xs text-slate-400 pt-1">Leave all stats blank to hide this section. Example: "98% · Success Rate", "300+ · HD Lectures", "6K+ · Students"</p>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 4 — FACULTY
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center text-purple-500 text-sm font-black">4</div>
        <h2 class="font-black text-slate-900 text-sm">Faculty / Mentor</h2>
        <span class="text-xs text-slate-400 font-normal ml-1">— leave blank to hide this section</span>
    </div>
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Faculty Name</label>
            <input type="text" name="faculty_name" value="{{ $v('faculty_name') }}"
                   placeholder="Dr. Rajesh Kumar"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Title / Designation</label>
            <input type="text" name="faculty_title" value="{{ $v('faculty_title') }}"
                   placeholder="IITian · Ex-FIITJEE Faculty · 15 yrs exp."
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Experience Badge</label>
            <input type="text" name="faculty_experience" value="{{ $v('faculty_experience') }}"
                   placeholder="15+ Years Teaching"
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Faculty Photo</label>
            @if($editing && $campaign?->faculty_photo)
                <img src="{{ Storage::url($campaign->faculty_photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-orange-200 mb-2">
            @endif
            <input type="file" name="faculty_photo" accept="image/*"
                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold hover:file:bg-orange-100 transition">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Bio</label>
            <textarea name="faculty_bio" rows="3"
                      placeholder="Brief paragraph about the faculty member — qualifications, achievements, teaching style..."
                      class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition resize-none">{{ $v('faculty_bio') }}</textarea>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 5 — TESTIMONIALS
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
     x-data="{ testimonials: {{ $initTestimonials }} }">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center text-green-600 text-sm font-black">5</div>
            <h2 class="font-black text-slate-900 text-sm">Student Testimonials</h2>
            <span class="text-xs text-slate-400 font-normal ml-1">— leave all blank to hide</span>
        </div>
        <button type="button" @click="testimonials.push({name:'',course:'',rank:'',quote:''})"
                class="text-xs font-bold text-orange-500 hover:text-orange-700 flex items-center gap-1">
            + Add Testimonial
        </button>
    </div>
    <div class="p-6 space-y-5">
        <template x-for="(t, i) in testimonials" :key="i">
            <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 relative">
                <button type="button" @click="testimonials.splice(i,1)"
                        class="absolute top-3 right-3 w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition text-sm">✕</button>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Student Name</label>
                        <input type="text" :name="`testimonials[${i}][name]`" x-model="t.name"
                               placeholder="Priya Sharma"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Course Enrolled</label>
                        <input type="text" :name="`testimonials[${i}][course]`" x-model="t.course"
                               placeholder="NEET UG Target 2026"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Achievement / Rank</label>
                        <input type="text" :name="`testimonials[${i}][rank]`" x-model="t.rank"
                               placeholder="NEET 680/720 · AIR 142"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Quote</label>
                    <textarea :name="`testimonials[${i}][quote]`" x-model="t.quote" rows="2"
                              placeholder="What they said about the course..."
                              class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition resize-none"></textarea>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 6 — FAQs
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
     x-data="{ faqs: {{ $initFaqs }} }">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600 text-sm font-black">6</div>
            <h2 class="font-black text-slate-900 text-sm">FAQs</h2>
            <span class="text-xs text-slate-400 font-normal ml-1">— leave all blank to hide</span>
        </div>
        <button type="button" @click="faqs.push({question:'',answer:''})"
                class="text-xs font-bold text-orange-500 hover:text-orange-700 flex items-center gap-1">
            + Add FAQ
        </button>
    </div>
    <div class="p-6 space-y-4">
        <template x-for="(faq, i) in faqs" :key="i">
            <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 relative">
                <button type="button" @click="faqs.splice(i,1)"
                        class="absolute top-3 right-3 w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition text-sm">✕</button>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Question</label>
                        <input type="text" :name="`faqs[${i}][question]`" x-model="faq.question"
                               placeholder="Who is this course for?"
                               class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Answer</label>
                        <textarea :name="`faqs[${i}][answer]`" x-model="faq.answer" rows="2"
                                  placeholder="Detailed answer..."
                                  class="w-full px-3 py-2 border border-slate-200 rounded-xl bg-white text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 transition resize-none"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 7 — COLOUR THEME
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-pink-100 flex items-center justify-center text-pink-500 text-sm font-black">7</div>
        <h2 class="font-black text-slate-900 text-sm">Colour Theme</h2>
        <span class="text-xs text-slate-400 font-normal ml-1">— click swatch to open picker, or type hex</span>
    </div>
    <div class="p-6 grid grid-cols-2 sm:grid-cols-5 gap-4">
        @foreach([
            ['name' => 'primary_color',   'label' => 'Primary',    'default' => '#1B2AFF'],
            ['name' => 'secondary_color', 'label' => 'Secondary',  'default' => '#7B61FF'],
            ['name' => 'accent_color',    'label' => 'Accent',     'default' => '#00D2FF'],
            ['name' => 'bg_color',        'label' => 'Background', 'default' => '#0a0a1a'],
            ['name' => 'text_color',      'label' => 'Text',       'default' => '#ffffff'],
        ] as $col)
        <div x-data="{ hex: '{{ $vColor($col['name'], $col['default']) }}' }">
            <label class="block text-xs font-bold text-slate-600 mb-1.5">{{ $col['label'] }}</label>
            <div class="flex items-center gap-2">
                <input type="color" x-model="hex" @input="$refs.t.value = hex"
                       class="w-9 h-9 rounded-lg border-2 border-slate-200 cursor-pointer flex-shrink-0"
                       :style="'border-color:' + hex" :value="hex">
                <input type="text" name="{{ $col['name'] }}" x-model="hex" x-ref="t"
                       @input="hex = $event.target.value"
                       placeholder="{{ $col['default'] }}"
                       maxlength="7"
                       class="flex-1 min-w-0 px-2 py-2 border border-slate-200 rounded-xl text-slate-900 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Default: {{ $col['default'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SECTION 8 — POPUP
════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
     x-data="{ popupType: '{{ $v('popup_type', 'global') }}' }">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center text-indigo-500 text-sm font-black">8</div>
        <h2 class="font-black text-slate-900 text-sm">Popup Ad</h2>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid grid-cols-3 gap-3">
            @foreach(['none' => 'No Popup', 'global' => 'Use Global Popup', 'custom' => 'Custom Image'] as $val => $lbl)
            <label class="relative cursor-pointer">
                <input type="radio" name="popup_type" value="{{ $val }}" x-model="popupType" class="sr-only">
                <div :class="popupType === '{{ $val }}' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-200 text-slate-500 hover:border-orange-200'"
                     class="border-2 rounded-xl p-3 text-center text-xs font-bold transition">{{ $lbl }}</div>
            </label>
            @endforeach
        </div>
        <div x-show="popupType === 'custom'" x-transition style="display:none">
            @if($editing && $campaign?->popup_image)
                <img src="{{ Storage::url($campaign->popup_image) }}" class="w-40 rounded-xl border border-slate-200 mb-2">
            @endif
            <input type="file" name="popup_image" accept="image/*"
                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold hover:file:bg-orange-100 transition">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5">Popup Delay (seconds)</label>
            <input type="number" name="popup_delay_seconds" value="{{ $v('popup_delay_seconds', 3) }}" min="0" max="30"
                   class="w-28 px-3 py-2 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/30 focus:border-orange-400 bg-slate-50 transition">
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     SUBMIT
════════════════════════════════════════════════════ --}}
<div class="flex items-center justify-between gap-4 bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-4">
    <label class="flex items-center gap-3 cursor-pointer">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               {{ $v('is_active', '1') == '1' ? 'checked' : '' }}
               class="w-4 h-4 rounded accent-orange-500">
        <span class="font-bold text-slate-800 text-sm">Publish campaign (make it live)</span>
    </label>
    <div class="flex items-center gap-3">
        <a href="{{ route('ads.campaigns.index') }}"
           class="px-5 py-2.5 border border-slate-200 rounded-xl text-slate-700 font-bold text-sm hover:border-slate-400 transition">Cancel</a>
        <button type="submit"
                class="px-7 py-2.5 text-white font-black rounded-xl transition shadow-md text-sm"
                style="background: linear-gradient(135deg, #f97316, #fb923c)">
            {{ $editing ? 'Update Campaign' : 'Create Campaign' }}
        </button>
    </div>
</div>

</form>
</div>
