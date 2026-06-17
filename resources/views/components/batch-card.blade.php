@props(['batch'])

@php
    $course = $batch->course;
    $title = $batch->name ?? 'Untitled Batch';
    $description = $course->description ?? 'Structured preparation program with live classes, tests, and doubt-solving support.';
    $targetExam = $batch->category?->name ?? $course->category?->name ?? 'General';
    $subcategoryLabel = $batch->subcategory?->name;
    $price = (float) ($batch->price ?? 0);
    $mrp = (float) ($batch->original_price ?? 0);
    $seatsFilled = (int) data_get($batch, 'filled_seats', 0);
    $imageUrl = $batch->thumbnail
        ? asset('storage/' . $batch->thumbnail)
        : ($course && $course->thumbnail ? asset('storage/' . $course->thumbnail) : null);
@endphp

<article class="group relative rounded-2xl p-[1px] bg-gradient-to-br from-indigo-100 via-sky-100 to-violet-100 hover:from-indigo-300 hover:to-sky-300 transition-all duration-300 h-full">
    <div class="relative h-full rounded-2xl border border-white/70 bg-white/85 backdrop-blur-sm overflow-hidden shadow-[0_10px_28px_rgba(15,23,42,0.08)] group-hover:shadow-[0_18px_35px_rgba(79,70,229,0.22)] group-hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="relative h-44 overflow-hidden">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-indigo-50 via-blue-50 to-violet-100 flex items-center justify-center">
                    <div class="text-center px-4">
                        <p class="text-3xl font-black text-primary uppercase tracking-tight">{{ strtoupper(substr($title, 0, 4)) }}</p>
                        <p class="text-xs text-slate-500 font-semibold mt-1">{{ $targetExam }} Batch</p>
                    </div>
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/20 to-transparent"></div>

            <div class="absolute top-3 left-3 flex items-center gap-2 flex-wrap">
                @if($subcategoryLabel)
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-600/90 text-white shadow-sm">{{ $subcategoryLabel }}</span>
                @endif
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-white/90 text-slate-700 border border-white/70 shadow-sm">{{ $targetExam }}</span>
            </div>
        </div>

        <div class="p-5 flex flex-col flex-1">
            <h3 class="text-base font-black text-slate-900 leading-tight mb-2 line-clamp-2">{{ $title }}</h3>
            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4">{{ $description }}</p>

            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-4">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ number_format($seatsFilled) }} Seats Filled
                </span>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100 flex items-end justify-between gap-3">
                <div>
                    <p class="text-[11px] text-slate-400 font-semibold line-through">{{ ($mrp > 0) ? '₹' . number_format($mrp) : '' }}</p>
                    <p class="text-2xl font-black text-slate-900 leading-none">{{ $price > 0 ? '₹' . number_format($price) : 'Free' }}</p>
                </div>

                @if($batch->uuid)
                    <a href="{{ route('checkout.show', $batch->uuid) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-primary to-indigo-600 shadow-[0_8px_22px_rgba(79,70,229,0.35)] hover:shadow-[0_12px_24px_rgba(79,70,229,0.45)] transition-all">
                        Enroll Now
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <span class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-extrabold text-slate-400 bg-slate-100 border border-slate-200">Unavailable</span>
                @endif
            </div>

        </div>
    </div>
</article>
