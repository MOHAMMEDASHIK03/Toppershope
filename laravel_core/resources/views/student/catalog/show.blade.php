@extends('student.layouts.app')
@section('title', $course->name)
@section('page_title', 'Course details')

@section('content')
<div class="max-w-6xl mx-auto">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('student.catalog') }}" class="hover:text-orange-600 font-medium transition-colors">Courses</a>
        <i class="ph ph-caret-right text-xs"></i>
        <span class="text-slate-700 font-medium truncate">{{ $course->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="h-52 bg-gradient-to-br from-orange-50 to-amber-50 relative">
                    @if($course->hero_image || $course->thumbnail)
                        <img src="{{ asset('storage/' . ($course->hero_image ?? $course->thumbnail)) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-5xl font-bold text-orange-200">{{ strtoupper(substr($course->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    @if($course->category)
                        <span class="absolute top-4 left-4 px-2.5 py-1 rounded-md bg-white/95 text-xs font-semibold text-orange-700 border border-orange-100 uppercase">{{ $course->category->name }}</span>
                    @endif
                </div>
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $course->name }}</h1>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $course->description }}</p>

                    @if($course->about)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">About this course</h3>
                            <div class="text-sm text-slate-600 leading-relaxed prose prose-sm max-w-none">{{ $course->about }}</div>
                        </div>
                    @endif
                </div>
            </div>

            @if($course->what_you_learn && count($course->what_you_learn) > 0)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">What you&rsquo;ll learn</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($course->what_you_learn as $item)
                            <div class="flex items-start gap-2.5">
                                <i class="ph-fill ph-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
                                <span class="text-sm text-slate-700">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($course->subjects->count() > 0)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Course curriculum</h3>
                    <div class="space-y-3">
                        @foreach($course->subjects as $subject)
                            <div class="border border-slate-100 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 flex items-center justify-between">
                                    <span class="font-semibold text-sm text-slate-800">{{ $subject->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $subject->chapters->count() }} chapters</span>
                                </div>
                                @foreach($subject->chapters as $ch)
                                    <div class="px-4 py-2 border-t border-slate-100 text-xs text-slate-600 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400 shrink-0"></span>
                                        {{ $ch->name }}
                                        <span class="ml-auto text-slate-400">{{ $ch->units->count() }} topics</span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 px-1">Available batches</p>
            @forelse($course->batches as $batch)
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 {{ in_array($batch->id, $enrolledBatchIds) ? 'ring-2 ring-emerald-200 border-emerald-200' : '' }}">
                    <div class="flex items-center justify-between mb-2 gap-2">
                        <h4 class="font-semibold text-slate-900 text-sm">{{ $batch->name }}</h4>
                        @if($batch->is_upcoming)
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded-md border border-amber-200">Coming soon</span>
                        @endif
                    </div>

                    @if($batch->mentor_name)
                        <p class="text-xs text-slate-500 mb-1">Mentor: <span class="text-slate-700">{{ $batch->mentor_name }}</span></p>
                    @endif
                    @if($batch->start_date)
                        <p class="text-xs text-slate-500 mb-1">Starts: <span class="text-slate-700">{{ $batch->start_date->format('d M Y') }}</span></p>
                    @endif
                    @if($batch->schedule)
                        <p class="text-xs text-slate-500 mb-3">Schedule: <span class="text-slate-700">{{ $batch->schedule }}</span></p>
                    @endif

                    @if($batch->total_seats > 0)
                        <div class="mb-3">
                            <div class="flex justify-between text-[10px] font-semibold text-slate-500 mb-1">
                                <span>{{ $batch->filled_seats }}/{{ $batch->total_seats }} enrolled</span>
                                <span>{{ $batch->seats_remaining }} left</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $batch->fill_percent > 80 ? 'bg-rose-500' : ($batch->fill_percent > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                     style="width: {{ $batch->fill_percent }}%"></div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-end justify-between gap-3 mt-4 pt-3 border-t border-slate-100">
                        <div>
                            <p class="text-xl font-bold text-slate-900 tabular-nums">₹{{ number_format($batch->price) }}</p>
                            @if($batch->original_price && $batch->original_price > $batch->price)
                                <p class="text-xs text-slate-400 line-through">₹{{ number_format($batch->original_price) }}</p>
                            @endif
                        </div>

                        @if(in_array($batch->id, $enrolledBatchIds))
                            <span class="badge-active shrink-0">Enrolled</span>
                        @elseif($batch->seats_remaining <= 0)
                            <span class="px-3 py-2 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold border border-rose-200 shrink-0">Sold out</span>
                        @else
                            <a href="{{ route('checkout.show', $batch->uuid) }}" class="btn-primary text-xs py-2 px-4 rounded-lg shrink-0">
                                Buy now
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-center text-sm text-slate-500">
                    No batches available yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
