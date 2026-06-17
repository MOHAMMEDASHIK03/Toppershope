@extends('student.layouts.app')
@section('title', 'Browse Courses')
@section('page_title', 'Browse Courses')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Course catalog</h2>
        <p class="text-sm text-slate-500 mt-1">Explore our premium courses and find the right one for your preparation.</p>
    </div>

    {{-- Category filters --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-3">Categories</p>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('student.catalog') }}"
               class="filter-chip {{ !$categorySlug ? 'active' : '' }}">
                All courses
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('student.catalog', ['category' => $cat->slug]) }}"
                   class="filter-chip {{ ($categorySlug ?? null) === $cat->slug ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        @if($activeCategory && $activeCategory->activeSubcategories->isNotEmpty())
            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mt-4 mb-2">Subcategories</p>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('student.catalog', ['category' => $activeCategory->slug]) }}"
                   class="filter-chip filter-chip-sm {{ !$subcategorySlug ? 'active' : '' }}">
                    All
                </a>
                @foreach($activeCategory->activeSubcategories as $sub)
                    <a href="{{ route('student.catalog', ['category' => $activeCategory->slug, 'subcategory' => $sub->slug]) }}"
                       class="filter-chip filter-chip-sm {{ ($subcategorySlug ?? null) === $sub->slug ? 'active' : '' }}">
                        {{ $sub->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Search --}}
    <form action="{{ route('student.catalog') }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-xl">
        @if($categorySlug)
            <input type="hidden" name="category" value="{{ $categorySlug }}">
        @endif
        @if($subcategorySlug)
            <input type="hidden" name="subcategory" value="{{ $subcategorySlug }}">
        @endif
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search courses..."
            class="admin-input flex-1 rounded-lg">
        <button type="submit" class="btn-primary px-5 py-2.5 rounded-lg text-sm font-semibold shrink-0">
            <i class="ph ph-magnifying-glass"></i> Search
        </button>
    </form>

    @if($search)
        <p class="text-sm text-slate-500">
            Showing results for <span class="font-semibold text-slate-700">&ldquo;{{ $search }}&rdquo;</span>
            <a href="{{ route('student.catalog', array_filter(['category' => $categorySlug, 'subcategory' => $subcategorySlug])) }}" class="text-orange-600 font-semibold hover:text-orange-700 ml-2">Clear search</a>
        </p>
    @endif

    {{-- Course grid --}}
    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($courses as $course)
                <article class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden hover:border-orange-200 hover:shadow-md transition-all group flex flex-col">
                    <a href="{{ route('student.catalog.show', $course->slug) }}" class="block h-40 bg-gradient-to-br from-orange-50 to-amber-50 relative overflow-hidden">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-4xl font-bold text-orange-200">{{ strtoupper(substr($course->name, 0, 2)) }}</span>
                            </div>
                        @endif
                        @if($course->category)
                            <span class="absolute top-3 left-3 px-2 py-0.5 rounded-md bg-white/95 text-[10px] font-semibold text-orange-700 border border-orange-100 shadow-sm uppercase">
                                {{ $course->category->name }}
                            </span>
                        @endif
                        @if($course->batches_count > 0)
                            <span class="absolute top-3 right-3 px-2 py-0.5 rounded-md bg-slate-900/75 text-[10px] font-semibold text-white">
                                {{ $course->batches_count }} batch{{ $course->batches_count > 1 ? 'es' : '' }}
                            </span>
                        @endif
                    </a>

                    <div class="p-5 flex flex-col flex-1">
                        <a href="{{ route('student.catalog.show', $course->slug) }}" class="block">
                            <h3 class="font-semibold text-slate-900 text-base leading-snug group-hover:text-orange-600 transition-colors">{{ $course->name }}</h3>
                        </a>
                        @if($course->description)
                            <p class="text-xs text-slate-500 line-clamp-2 mt-2 flex-1">{{ $course->description }}</p>
                        @endif

                        <div class="flex items-center justify-between gap-2 mt-4 pt-4 border-t border-slate-100">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @if($course->subcategory)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-semibold uppercase">{{ $course->subcategory->name }}</span>
                                @endif
                                @if($course->language)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-semibold">{{ $course->language }}</span>
                                @endif
                            </div>
                            @if($course->batches_count > 0)
                                @php $minPrice = $course->batches->min('price'); @endphp
                                @if($minPrice)
                                    <span class="text-emerald-600 font-bold text-sm tabular-nums">₹{{ number_format($minPrice) }}</span>
                                @else
                                    <span class="text-emerald-600 font-semibold text-xs">Free</span>
                                @endif
                            @endif
                        </div>

                        <a href="{{ route('student.catalog.show', $course->slug) }}"
                           class="btn-primary w-full mt-4 py-2.5 rounded-lg text-xs font-semibold justify-center">
                            View course details
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <x-admin.empty-state
            :title="$search ? 'No courses found' : 'No courses available'"
            :description="$search ? 'Try a different search term or browse another category.' : 'No courses are listed in this category yet. Check back soon.'"
        >
            <x-slot:action>
                <a href="{{ route('student.catalog') }}" class="btn-primary mt-4 inline-flex text-sm py-2.5 px-5 rounded-lg">Browse all courses</a>
            </x-slot:action>
        </x-admin.empty-state>
    @endif
</div>
@endsection
