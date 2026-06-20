@extends('layouts.public')

@section('title', 'Apply for ' . $jobPosting->title)

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
(function() {
    // Direct transition fade in on load
    if (typeof gsap !== 'undefined') {
        gsap.fromTo('#back-link', { opacity:0, y:-10 }, { opacity:1, y:0, duration:.4 });
        gsap.fromTo('#job-details-pane', { opacity:0, x:-25 }, { opacity:1, x:0, duration:.6, ease:'power3.out' });
        gsap.fromTo('#apply-form-pane', { opacity:0, x:25 }, { opacity:1, x:0, duration:.6, ease:'power3.out' }, '-=.4');
    }
    
    // File input visual label update helper
    const fileInput = document.getElementById('resume');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileName = file ? file.name : '';
            const uploadArea = fileInput.parentNode;
            
            if (fileName && uploadArea) {
                // Style upload area as active/success state
                uploadArea.classList.remove('border-slate-200', 'bg-slate-50/50');
                uploadArea.classList.add('border-primary-400', 'bg-primary-50/20');
                
                // Update icon to checklist/success check icon
                const iconContainer = uploadArea.querySelector('.w-12');
                if (iconContainer) {
                    iconContainer.innerHTML = '<svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    iconContainer.classList.remove('bg-white', 'text-slate-400');
                    iconContainer.classList.add('bg-primary-100', 'text-primary-600', 'border-primary-200');
                }
                
                // Show dynamic filename in premium badge
                const textEl = uploadArea.querySelector('p.text-slate-700');
                if (textEl) {
                    textEl.innerHTML = `<span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary-50 border border-primary-100 text-primary-700 font-extrabold text-sm shadow-sm select-all">${fileName}</span>`;
                }
                
                // Update subtext
                const subEl = uploadArea.querySelector('p.text-slate-400') || uploadArea.querySelector('p.text-primary-500');
                if (subEl) {
                    subEl.textContent = 'Selected successfully. Click or drag to change.';
                    subEl.className = 'text-xs text-primary-500 mt-3.5 font-bold';
                }
            } else if (uploadArea) {
                // Reset to default
                uploadArea.classList.remove('border-primary-400', 'bg-primary-50/20');
                uploadArea.classList.add('border-slate-200', 'bg-slate-50/50');
                
                const iconContainer = uploadArea.querySelector('.w-12');
                if (iconContainer) {
                    iconContainer.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>';
                    iconContainer.classList.remove('bg-primary-100', 'text-primary-600', 'border-primary-200');
                    iconContainer.classList.add('bg-white', 'text-slate-400');
                }
                
                const textEl = uploadArea.querySelector('p.text-slate-700');
                if (textEl) {
                    textEl.textContent = 'Choose file or drag here';
                }
                
                const subEl = uploadArea.querySelector('p.text-primary-500') || uploadArea.querySelector('p.text-slate-400');
                if (subEl) {
                    subEl.textContent = 'Supports PDF, DOC, DOCX up to 5MB';
                    subEl.className = 'text-xs text-slate-400 mt-1 font-semibold';
                }
            }
        });
    }
})();
</script>
@endpush

@section('content')
<div class="relative overflow-hidden bg-slate-50 min-h-screen py-12">
    <!-- Glow spots -->
    <div class="absolute -top-32 -left-20 w-80 h-80 rounded-full bg-primary-100/60 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-20 w-96 h-96 rounded-full bg-violet-100/60 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-8" id="back-link">
            <a href="{{ route('careers') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary transition-colors bg-white px-4 py-2 border border-slate-200 rounded-xl shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                Back to Opportunities
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Job Posting Details -->
            <div class="lg:col-span-5 space-y-6" id="job-details-pane">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <span class="px-3 py-1 bg-primary-50 border border-primary-100 text-primary rounded-full text-[10px] font-black uppercase tracking-wider mb-4 inline-block">
                        {{ $jobPosting->department->name ?? 'General' }}
                    </span>
                    
                    <h1 class="text-3xl font-black text-slate-900 leading-tight mb-2">
                        {{ $jobPosting->title }}
                    </h1>
                    
                    <div class="flex flex-wrap gap-y-2 gap-x-4 items-center text-xs font-bold text-slate-400 capitalize mb-6">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $jobPosting->location ?? 'Remote' }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $jobPosting->employment_type }}
                        </span>
                        @if($jobPosting->salary_range)
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $jobPosting->salary_range }}
                        </span>
                        @endif
                    </div>

                    <div class="h-px bg-slate-100 my-6"></div>

                    <!-- Role Description -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2.5">About the Role</h3>
                            <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line font-medium">
                                {{ $jobPosting->description }}
                            </div>
                        </div>

                        <!-- Requirements -->
                        @if($jobPosting->requirements)
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2.5">Requirements</h3>
                            <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line font-medium">
                                {{ $jobPosting->requirements }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Culture Quote Card -->
                <div class="bg-gradient-to-br from-primary via-[#2233ff] to-primary-600 rounded-3xl p-6 text-white shadow-lg shadow-primary-500/20">
                    <p class="text-[10px] uppercase font-black tracking-widest text-primary-200 mb-3">Our Core Philosophy</p>
                    <p class="font-extrabold text-lg mb-4 leading-snug">"We hire for drive, mentor for skills, and build for scale."</p>
                    <p class="text-xs text-primary-150 font-medium">Every team member at Topper's Hope has complete ownership of their domain, and works directly with our founders to build state-of-the-art software and curricula.</p>
                </div>
            </div>

            <!-- RIGHT COLUMN: Application Form -->
            <div class="lg:col-span-7" id="apply-form-pane">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <h2 class="text-2xl font-black text-slate-900 mb-2">Submit Your Application</h2>
                    <p class="text-slate-500 text-sm font-semibold mb-8">Please complete the form below. Our HR team reviews all submissions within 48 hours.</p>
                    
                    <form action="{{ route('careers.store', $jobPosting) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- First & Last Name row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border @error('first_name') border-red-400 focus:ring-red-150 @else border-slate-200 focus:ring-primary-600/15 @enderror rounded-xl focus:ring-4 focus:border-primary outline-none transition-all font-semibold text-slate-800 text-sm">
                                @error('first_name')
                                    <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border @error('last_name') border-red-400 focus:ring-red-150 @else border-slate-200 focus:ring-primary-600/15 @enderror rounded-xl focus:ring-4 focus:border-primary outline-none transition-all font-semibold text-slate-800 text-sm">
                                @error('last_name')
                                    <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email & Phone row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border @error('email') border-red-400 focus:ring-red-150 @else border-slate-200 focus:ring-primary-600/15 @enderror rounded-xl focus:ring-4 focus:border-primary outline-none transition-all font-semibold text-slate-800 text-sm">
                                @error('email')
                                    <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., {{ config('contact.phone') }}"
                                       class="w-full px-4 py-3 bg-slate-50 border @error('phone') border-red-400 focus:ring-red-150 @else border-slate-200 focus:ring-primary-600/15 @enderror rounded-xl focus:ring-4 focus:border-primary outline-none transition-all font-semibold text-slate-800 text-sm">
                                @error('phone')
                                    <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Resume Upload -->
                        <div>
                            <label for="resume" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">Upload Resume / CV <span class="text-red-500">*</span></label>
                            
                            <div class="relative group border-2 border-dashed @error('resume') border-red-300 bg-red-50/20 @else border-slate-200 bg-slate-50/50 hover:border-slate-300 @enderror rounded-2xl p-6 transition-colors flex flex-col items-center justify-center text-center">
                                <input type="file" id="resume" name="resume" required accept=".pdf,.doc,.docx" title=" "
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-primary transition-colors mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                
                                <p class="text-sm font-bold text-slate-700">Choose file or drag here</p>
                                <p class="text-xs text-slate-400 mt-1 font-semibold">Supports PDF, DOC, DOCX up to 5MB</p>
                                
                                <!-- Selected File Indicator (Using simple pure CSS or Alpine is unnecessary, standard file inputs will update standard browser, we can add a visual placeholder or let standard input work) -->
                            </div>
                            
                            @error('resume')
                                <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes / Cover Letter -->
                        <div>
                            <label for="notes" class="block text-xs font-black uppercase text-slate-700 tracking-wider mb-2">Short Cover Letter / Notes</label>
                            <textarea id="notes" name="notes" rows="5" placeholder="Tell us why you are a great fit for Topper's Hope..."
                                      class="w-full px-4 py-3 bg-slate-50 border @error('notes') border-red-400 focus:ring-red-150 @else border-slate-200 focus:ring-primary-600/15 @enderror rounded-xl focus:ring-4 focus:border-primary outline-none transition-all font-semibold text-slate-800 text-sm placeholder:text-slate-400">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-700 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl transition-all shadow-[0_8px_20px_rgba(119,35,214,0.25)] hover:shadow-[0_10px_24px_rgba(119,35,214,0.35)] flex items-center justify-center gap-2">
                                Submit Candidacy
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script pushed to header/footer stack for proper resource loading execution order -->
@endsection
