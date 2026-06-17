@php
    $batch = $batch ?? null;
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm py-2.5 px-3 outline-none';
@endphp

<div class="space-y-6">
    <div>
        <label for="course_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Course <span class="text-red-500">*</span></label>
        <select name="course_id" id="course_id" required class="{{ $inputClass }}">
            <option value="">— Select a course —</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" 
                        data-category-id="{{ $course->category_id }}" 
                        data-subcategory-id="{{ $course->subcategory_id }}"
                        @selected(old('course_id', $batch?->course_id) == $course->id)>
                    {{ $course->name }}@if($course->category) ({{ $course->category->name }})@endif
                </option>
            @endforeach
        </select>
        @error('course_id')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Batch name <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $batch?->name) }}" required
            placeholder="e.g. Ignite JEE Target 2026"
            class="{{ $inputClass }}">
        @error('name')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="price" class="block text-sm font-semibold text-slate-700 mb-1.5">Selling price (₹) <span class="text-red-500">*</span></label>
            <input type="number" name="price" id="price" value="{{ old('price', $batch?->price) }}" required min="0"
                placeholder="4999" class="{{ $inputClass }}">
            @error('price')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="original_price" class="block text-sm font-semibold text-slate-700 mb-1.5">Original / MRP (₹)</label>
            <input type="number" name="original_price" id="original_price" value="{{ old('original_price', $batch?->original_price) }}" min="0"
                placeholder="9999" class="{{ $inputClass }}">
            @error('original_price')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="total_seats" class="block text-sm font-semibold text-slate-700 mb-1.5">Total seats</label>
            <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats', $batch?->total_seats ?? 100) }}" min="1"
                placeholder="100" class="{{ $inputClass }}">
            @error('total_seats')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Start date</label>
            <input type="date" name="start_date" id="start_date"
                value="{{ old('start_date', $batch?->start_date?->format('Y-m-d')) }}"
                class="{{ $inputClass }}">
            @error('start_date')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="mode" class="block text-sm font-semibold text-slate-700 mb-1.5">Mode</label>
            <select name="mode" id="mode" class="{{ $inputClass }}">
                @foreach(['Online Live', 'Offline', 'Hybrid', 'Recorded'] as $mode)
                    <option value="{{ $mode }}" @selected(old('mode', $batch?->mode ?? 'Online Live') === $mode)>{{ $mode }}</option>
                @endforeach
            </select>
            @error('mode')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
            <select name="status" id="status" class="{{ $inputClass }}">
                <option value="active" @selected(old('status', $batch?->status ?? 'active') === 'active')>Active / Enrolling</option>
                <option value="filling_fast" @selected(old('status', $batch?->status) === 'filling_fast')>Filling fast</option>
                <option value="closed" @selected(old('status', $batch?->status) === 'closed')>Closed</option>
            </select>
            @error('status')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <x-category-fields
        :categories="$categories"
        :category-id="old('category_id', $batch?->category_id)"
        :subcategory-id="old('subcategory_id', $batch?->subcategory_id)"
        :subcategory-required="true"
        :input-class="$inputClass"
    />

    <label class="flex items-start gap-3 cursor-pointer p-4 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100/80 transition-colors">
        <input type="checkbox" name="is_upcoming" value="1"
            @checked(old('is_upcoming', $batch?->is_upcoming))
            class="mt-0.5 rounded border-amber-300 text-orange-500 focus:ring-orange-500">
        <div>
            <p class="text-sm font-semibold text-amber-900">Mark as &ldquo;Coming soon&rdquo;</p>
            <p class="text-xs text-amber-700 mt-0.5">Shows in the upcoming section. Students register interest instead of enrolling.</p>
        </div>
    </label>
</div>
