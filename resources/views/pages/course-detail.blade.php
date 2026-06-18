@extends('layouts.public')

@section('title', ($course->meta_title ?? $course->name) . ' — Topper\'s Hope')

@section('content')

{{-- ===== TOP BREADCRUMB + HERO ===== --}}
<section class="bg-gradient-to-br from-gray-900 via-[#0d1b6e] to-gray-900 pt-8 pb-0 relative overflow-hidden min-h-[420px]">
    {{-- Orbs --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-white/50 mb-8" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
            <span>›</span>
            @if($course->category)
            <a href="{{ route('category.show', $course->category) }}" class="hover:text-white transition-colors">{{ $course->category->name }}</a>
            <span>›</span>
            @endif
            <span class="text-white/80">{{ $course->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-0 items-end">
            {{-- LEFT: Course Info --}}
            <div class="lg:col-span-2 pb-16" id="hero-left">
                @if($course->category)
                <span class="inline-block bg-primary/30 border border-primary/40 text-primary-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-5">
                    {{ $course->category->name }}
                </span>
                @endif

                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4">
                    {{ $course->name }}
                </h1>

                @if($course->description)
                <p class="text-lg text-white/70 mb-6 leading-relaxed max-w-2xl">
                    {{ $course->description }}
                </p>
                @endif

                {{-- Rating + stats row --}}
                <div class="flex flex-wrap items-center gap-4 mb-8">
                    <div class="flex items-center gap-1.5 text-yellow-400 text-sm font-bold">
                        ★★★★★ <span class="text-white/60 font-normal text-xs ml-1">4.8 (2.3k+ ratings)</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-white/60 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>{{ $course->batches->sum('filled_seats') }}+ students enrolled</span>
                    </div>
                    @if($course->language)
                    <div class="flex items-center gap-1.5 text-white/60 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                        {{ $course->language }}
                    </div>
                    @endif
                    @if($course->subcategory)
                    <span class="bg-white/10 border border-white/20 text-white/70 text-xs px-2.5 py-0.5 rounded-full font-semibold">{{ $course->subcategory->name }}</span>
                    @endif
                    @if($course->duration)
                    <span class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-white/70 text-xs px-2.5 py-0.5 rounded-full font-semibold"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $course->duration }}</span>
                    @endif
                </div>

                {{-- Quick Highlights — only if faculty has set them --}}
                @if(!empty($course->highlights))
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($course->highlights as $hl)
                    <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-center">
                        <div class="w-8 h-8 mx-auto mb-2 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-white text-xs font-bold">{{ $hl['text'] ?? $hl }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT: Hero student image — fills bottom of dark hero section --}}
            <div class="hidden lg:flex items-end justify-center relative" id="hero-graphic">
                @if($course->hero_image)
                    {{-- Faculty-uploaded hero image --}}
                    <img src="{{ asset('storage/' . $course->hero_image) }}" alt="Student"
                         class="h-72 object-contain object-bottom drop-shadow-2xl">
                @else
                    {{-- Default placeholder image (replace with student photo later) --}}
                    <div class="relative">
                        {{-- Glowing base circle --}}
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-64 h-16 bg-primary/30 rounded-full blur-2xl"></div>
                        <img src="{{ asset('images/course_hero_student.png') }}" alt="Student studying"
                             class="h-72 object-contain object-bottom drop-shadow-2xl relative z-10">
                        {{-- Floating badge: top rank --}}
                        <div class="absolute top-4 -right-6 bg-white rounded-2xl px-3 py-2 shadow-xl flex items-center gap-2 z-20">
                            <span class="w-7 h-7 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 21h8M12 17v4M7 4h10v2a5 5 0 01-5 5h0a5 5 0 01-5-5V4z"></path></svg></span>
                            <div>
                                <p class="text-[10px] text-gray-500 font-semibold leading-none">Top Ranker</p>
                                <p class="text-xs font-black text-gray-900">AIR 47 — JEE</p>
                            </div>
                        </div>
                        {{-- Floating badge: 5 star --}}
                        <div class="absolute bottom-16 -left-8 bg-white rounded-xl px-3 py-1.5 shadow-xl flex items-center gap-1.5 z-20">
                            <span class="text-yellow-400 text-sm font-black">★★★★★</span>
                            <span class="text-[10px] font-bold text-gray-700">4.9 Rating</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Wave bottom --}}
    <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full block -mb-px">
        <path d="M0 48L60 40C120 32 240 16 360 12C480 8 600 16 720 22C840 28 960 32 1080 30C1200 28 1320 20 1380 16L1440 12V48H0Z" fill="white"/>
    </svg>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        {{-- ===== LEFT: Course Content ===== --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- About --}}
            @if($course->about)
            <div class="course-section" id="section-about">
                <h2 class="text-2xl font-black text-gray-900 mb-4">About this Course</h2>
                <div class="bg-gradient-to-br from-primary-50 to-primary-50 border border-primary-100 rounded-2xl p-6 text-gray-700 leading-relaxed">
                    {!! nl2br(e($course->about)) !!}
                </div>
            </div>
            @endif

            {{-- What You'll Learn --}}
            @if(!empty($course->what_you_learn))
            <div class="course-section" id="section-learn">
                <h2 class="text-2xl font-black text-gray-900 mb-5">What You'll Learn</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($course->what_you_learn as $point)
                    <div class="flex items-start gap-3 bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm text-gray-700 font-medium leading-snug">{{ $point }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="course-section" id="section-learn">
                <h2 class="text-2xl font-black text-gray-900 mb-5">What You'll Learn</h2>
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-amber-800 text-sm font-medium">
                    Course curriculum details will be updated by the faculty shortly. Check back soon.
                </div>
            </div>
            @endif

            {{-- ===== This Course Includes (DB-driven, 4–8 items) ===== --}}
            <div class="course-section" id="section-includes">
                <h2 class="text-2xl font-black text-gray-900 mb-5">This Course Includes</h2>
                @if(!empty($course->includes))
                    {{-- Faculty has set includes — show items from DB --}}
                    @php $showItems = array_slice($course->includes, 0, 8); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($showItems as $item)
                        @php
                            $iconVal = $item['icon'] ?? '📦';
                            $isPhosphor = str_starts_with($iconVal, 'ph-');
                        @endphp
                        <div class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/20 transition-all">
                            <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center shrink-0 text-xl select-none">
                                @if($isPhosphor)
                                    <i class="{{ $iconVal }} text-primary"></i>
                                @else
                                    {{ $iconVal }}
                                @endif
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ $item['text'] ?? $item }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Faculty hasn't set includes yet --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-amber-800 text-sm font-medium">
                        Course inclusions will be listed here by the faculty soon. Check back or contact us for details.
                    </div>
                @endif
            </div>

            {{-- Syllabus Accordion --}}
            <div class="course-section" id="section-syllabus">
                <h2 class="text-2xl font-black text-gray-900 mb-5">Course Syllabus</h2>
                @if(!empty($course->syllabus))
                <div class="space-y-3" x-data="{ open: 0 }">
                    @foreach($course->syllabus as $idx => $section)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <button @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                            class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition-colors text-left group">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary text-xs font-black flex items-center justify-center shrink-0">{{ $idx + 1 }}</span>
                                <span class="font-bold text-gray-900 text-sm">{{ $section['section'] ?? $section['title'] ?? 'Section ' . ($idx+1) }}</span>
                            </div>
                            <svg :class="open === {{ $idx }} ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open === {{ $idx }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-4 bg-gray-50 border-t border-gray-100">
                            @if(!empty($section['topics']))
                            <ul class="space-y-2 mt-3">
                                @foreach($section['topics'] as $topic)
                                <li class="flex items-center gap-2.5 text-sm text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                    {{ $topic }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-amber-800 text-sm font-medium">
                    Detailed syllabus will be published by the faculty team soon. Contact us for the full syllabus PDF.
                </div>
                @endif
                
                @if($course->syllabus_pdf_path)
                <div class="mt-5 flex justify-end">
                    <a href="{{ asset('storage/' . $course->syllabus_pdf_path) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-50 hover:bg-primary-600 text-primary-700 hover:text-white font-black rounded-xl transition-all border border-primary-100 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Full Syllabus PDF
                    </a>
                </div>
                @endif
            </div>

            {{-- FAQs --}}
            @if(!empty($course->faqs))
            <div class="course-section" id="section-faq">
                <h2 class="text-2xl font-black text-gray-900 mb-5">Frequently Asked Questions</h2>
                <div class="space-y-3" x-data="{ open: null }">
                    @foreach($course->faqs as $idx => $faq)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <button @click="open === {{ $idx }} ? open = null : open = {{ $idx }}"
                            class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 text-left transition-colors">
                            <span class="font-bold text-gray-900 text-sm pr-4">{{ $faq['q'] }}</span>
                            <svg :class="open === {{ $idx }} ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform duration-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open === {{ $idx }}" x-transition class="px-5 pb-4 bg-gray-50 border-t border-gray-100">
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- END left col --}}

        {{-- ===== RIGHT: Sticky Sidebar ===== --}}
        <div class="space-y-6" id="sticky-sidebar">
            <div class="sticky top-24 space-y-5">

                @php
                $activeBatches   = $course->batches->where('is_upcoming', false);
                $upcomingBatches = $course->batches->where('is_upcoming', true);
                @endphp

                @if($activeBatches->count() > 0)
                <div class="space-y-4">
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Choose Your Batch</p>
                    @foreach($activeBatches as $batch)
                    <div class="bg-white rounded-2xl border-2 border-gray-200 hover:border-primary/50 transition-all shadow-sm hover:shadow-md group overflow-hidden">
                        @if($batch->status === 'filling_fast')
                        <div class="bg-red-500 text-white text-[10px] font-black text-center py-1.5 uppercase tracking-wider">Filling Fast</div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-black text-gray-900 text-base">{{ $batch->name }}</h4>
                                    @if($batch->batch_description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $batch->batch_description }}</p>
                                    @endif
                                </div>
                                @if($batch->mode)
                                <span class="shrink-0 ml-2 text-[10px] font-bold bg-primary-50 text-primary border border-primary-100 px-2 py-0.5 rounded-full">{{ $batch->mode }}</span>
                                @endif
                            </div>
                            <div class="space-y-2 mb-4">
                                @if($batch->start_date)
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Starts {{ $batch->start_date->format('d M Y') }}
                                </div>
                                @endif
                                @if($batch->schedule)
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $batch->schedule }}
                                </div>
                                @endif
                                @if($batch->mentor_name)
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $batch->mentor_name }}@if($batch->mentor_designation), <span class="text-gray-400">{{ $batch->mentor_designation }}</span>@endif
                                </div>
                                @endif
                            </div>
                            @if($batch->total_seats > 0)
                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                                    <span>{{ $batch->seats_remaining }} seats left</span>
                                    <span>{{ $batch->fill_percent }}% filled</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $batch->fill_percent >= 80 ? 'bg-red-400' : 'bg-primary' }} transition-all"
                                         style="width: {{ $batch->fill_percent }}%"></div>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                <div>
                                    @if($batch->original_price && $batch->original_price > $batch->price)
                                    <p class="text-xs text-gray-400 line-through">₹{{ number_format($batch->original_price) }}</p>
                                    @endif
                                    <p class="text-2xl font-black text-gray-900">₹{{ number_format($batch->price) }}</p>
                                    @if($batch->original_price && $batch->original_price > $batch->price)
                                    <span class="text-xs font-bold text-green-600">{{ round((($batch->original_price - $batch->price)/$batch->original_price)*100) }}% OFF</span>
                                    @endif
                                </div>
                                <a href="{{ route('checkout.show', $batch->uuid) }}"
                                   class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-600 hover:from-primary-700 hover:to-primary-700 text-white font-black rounded-xl text-sm transition-all shadow-[0_4px_14px_rgba(119,35,214,0.4)] hover:shadow-[0_4px_20px_rgba(119,35,214,0.6)] hover:-translate-y-0.5 inline-flex items-center gap-1.5 border border-primary-500/50">
                                    Enroll Now
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6 text-center">
                    <p class="text-primary-700 font-bold text-sm">Launching Soon</p>
                    <p class="text-primary-500 text-xs mt-1">Join the waitlist to get early access</p>
                    <a href="{{ route('contact') }}" class="mt-4 inline-block px-5 py-2.5 bg-primary text-white font-bold rounded-xl text-sm hover:bg-primary-700 transition-colors">
                        Register Interest
                    </a>
                </div>
                @endif

                {{-- Upcoming batches --}}
                @if($upcomingBatches->count() > 0)
                <div>
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Coming Soon</p>
                    <div class="space-y-3">
                        @foreach($upcomingBatches as $ub)
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-black text-gray-900 text-sm">{{ $ub->name }}</h4>
                                <span class="text-[10px] font-black bg-amber-400 text-white px-2 py-0.5 rounded-full uppercase">Coming Soon</span>
                            </div>
                            @if($ub->start_date)
                            <p class="text-xs text-gray-600 mb-3">From {{ $ub->start_date->format('d M Y') }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <p class="text-lg font-black text-gray-900">₹{{ number_format($ub->price) }}</p>
                                <a href="{{ route('contact') }}" class="text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg transition-colors">
                                    Register Interest
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Trust signals --}}
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-3">
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Why Topper's Hope?</p>
                    @foreach([
                        '1M+ Students Enrolled',
                        '4.9/5 Average Rating',
                        'Expert-Led Curriculum',
                        '24×7 Doubt Support',
                        'Secure Payment Gateway',
                        '7-Day Refund Policy',
                    ] as $txt)
                    <div class="flex items-center gap-2.5 text-sm text-gray-600">
                        <span class="w-6 h-6 rounded-lg bg-primary-50 border border-primary-100 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                        <span class="font-medium">{{ $txt }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Need Help --}}
                <div class="bg-gradient-to-br from-primary to-primary-700 rounded-2xl p-5 text-white text-center">
                    <p class="font-black text-base mb-1">Need Help?</p>
                    <p class="text-xs text-primary-200 mb-4">Talk to our academic counselors</p>
                    <a href="tel:+919876543210" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        +91 98765 43210
                    </a>
                </div>

            </div>
        </div>{{-- end right col --}}

    </div>{{-- end 3-col grid --}}
</div>

{{-- ===== FACULTY SECTION ===== --}}
@if(!empty($course->faculty))
<section class="py-16 bg-gradient-to-br from-gray-50 to-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-xs font-black text-primary uppercase tracking-widest mb-2">Meet Your Mentors</p>
            <h2 class="text-3xl font-black text-gray-900">Faculty Who'll Teach You</h2>
            <p class="text-gray-500 text-sm mt-2">Learn from India's top educators with proven track records</p>
        </div>

        {{-- Faculty carousel — works for 1–5 cards --}}
        @php $facultyList = array_slice($course->faculty, 0, 5); @endphp

        <div class="relative" x-data="{ activeF: 0, totalF: {{ count($facultyList) }} }">
            {{-- Cards wrapper --}}
            <div class="overflow-hidden">
                <div class="flex gap-6 transition-transform duration-500"
                     :style="`transform: translateX(calc(-${activeF} * (100% / ${totalF >= 3 ? 3 : totalF} + 24px / ${totalF >= 3 ? 3 : totalF})))`"
                     id="faculty-track">
                    @foreach($facultyList as $f)
                    <div class="shrink-0 w-full {{ count($facultyList) >= 3 ? 'md:w-[calc(33.333%-16px)]' : (count($facultyList) == 2 ? 'md:w-[calc(50%-12px)]' : 'md:w-full max-w-sm mx-auto') }}">
                        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                            {{-- Photo / avatar --}}
                            <div class="h-52 bg-gradient-to-br from-primary-50 via-primary-50 to-primary-50 flex items-end justify-center relative overflow-hidden">
                                {{-- Subtle pattern bg --}}
                                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #3b82f6 1px, transparent 1px); background-size: 20px 20px;"></div>
                                @if(!empty($f['photo']))
                                    <img src="{{ asset('storage/' . $f['photo']) }}"
                                         alt="{{ $f['name'] }}"
                                         class="h-full w-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                                @else
                                    {{-- Initials avatar --}}
                                    <div class="w-28 h-28 rounded-full bg-primary/20 border-4 border-white shadow-lg flex items-center justify-center mb-4 relative z-10">
                                        <span class="text-4xl font-black text-primary">
                                            {{ strtoupper(substr($f['name'], 0, 1)) }}{{ strtoupper(substr(strrchr($f['name'], ' ') ?: '', 1, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-6">
                                <h4 class="text-lg font-black text-gray-900 mb-0.5">{{ $f['name'] }}</h4>
                                @if(!empty($f['designation']))
                                <p class="text-xs font-bold text-primary mb-3">{{ $f['designation'] }}</p>
                                @endif

                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if(!empty($f['subject']))
                                    <span class="bg-primary-50 border border-primary-100 text-primary-700 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $f['subject'] }}</span>
                                    @endif
                                    @if(!empty($f['experience']))
                                    <span class="bg-green-50 border border-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $f['experience'] }}</span>
                                    @endif
                                    @if(!empty($f['students']))
                                    <span class="bg-primary-50 border border-primary-100 text-primary-700 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $f['students'] }} students</span>
                                    @endif
                                </div>

                                @if(!empty($f['about']))
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $f['about'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Navigation dots + arrows (only show if more than 3) --}}
            @if(count($facultyList) > 3)
            <div class="flex items-center justify-center gap-4 mt-8">
                <button @click="activeF = Math.max(0, activeF-1)"
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="flex gap-2">
                    @for($i = 0; $i < count($facultyList) - 2; $i++)
                    <button @click="activeF = {{ $i }}"
                        :class="activeF === {{ $i }} ? 'w-6 bg-primary' : 'w-2 bg-gray-300'"
                        class="h-2 rounded-full transition-all duration-300"></button>
                    @endfor
                </div>
                <button @click="activeF = Math.min({{ count($facultyList) - 3 }}, activeF+1)"
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ===== RELATED COURSES ===== --}}
@if($related->count() > 0)
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-black text-primary uppercase tracking-widest mb-2">Related</p>
                <h2 class="text-2xl font-black text-gray-900">You Might Also Like</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $rc)
            <a href="{{ route('course.detail', $rc->slug) }}"
               class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-primary/30 transition-all group overflow-hidden flex flex-col">
                <div class="h-32 bg-gradient-to-br from-primary-50 via-primary-50 to-primary-50 relative flex items-center justify-center border-b border-gray-100 overflow-hidden">
                    @if($rc->hero_image)
                    <img src="{{ asset('storage/' . $rc->hero_image) }}" alt="{{ $rc->name }}" class="w-full h-full object-cover opacity-80 mix-blend-multiply">
                    @else
                    <h4 class="text-3xl font-black text-primary-200 uppercase tracking-tighter">{{ strtoupper(substr($rc->name,0,4)) }}</h4>
                    @endif
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="font-bold text-gray-900 group-hover:text-primary transition-colors text-base mb-1">{{ $rc->name }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $rc->description }}</p>
                    @php $cheapest = $rc->batches->where('is_upcoming', false)->sortBy('price')->first(); @endphp
                    @if($cheapest)
                    <div class="mt-auto flex items-center justify-between">
                        <p class="text-lg font-black text-gray-900">₹{{ number_format($cheapest->price) }}</p>
                        <span class="text-xs font-bold text-primary group-hover:underline">View Course</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    gsap.registerPlugin(ScrollTrigger);

    // Hero entrance
    gsap.from('#hero-left > *', { opacity:0, y:30, stagger:0.1, duration:0.7, ease:'power3.out' });
    gsap.from('#hero-graphic', { opacity:0, x:40, scale:0.95, duration:0.9, ease:'power3.out', delay:0.4 });
    gsap.from('#sticky-sidebar', { opacity:0, x:40, duration:0.8, ease:'power3.out', delay:0.3 });

    // Section reveals
    gsap.utils.toArray('.course-section').forEach(el => {
        gsap.from(el, { opacity:0, y:40, duration:0.7, ease:'power2.out',
            scrollTrigger: { trigger: el, start: 'top 88%' }
        });
    });
});
</script>
@endpush
@endsection
