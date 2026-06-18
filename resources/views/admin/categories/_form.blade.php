@php
    $meta = old('landing_meta', $category->landing_meta ?? []);
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all';
    $labelClass = 'block text-sm font-semibold text-slate-700 mb-1.5';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="{{ $labelClass }}">Category name <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="{{ $inputClass }}" placeholder="e.g. IIT JEE">
    </div>
    <div>
        <label class="{{ $labelClass }}">URL slug</label>
        <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" class="{{ $inputClass }}" placeholder="auto-generated if empty">
    </div>
    <div class="md:col-span-2">
        <label class="{{ $labelClass }}">Description</label>
        <textarea name="description" rows="2" class="{{ $inputClass }} resize-none" placeholder="Short description for admins and faculty">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="{{ $labelClass }}">Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" class="{{ $inputClass }}">
    </div>
    <div class="flex items-center gap-3 pt-7">
        <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-primary focus:ring-primary" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-slate-700">Active (visible on website & filters)</label>
    </div>
</div>

<div class="mt-8 pt-6 border-t border-slate-200">
    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-4">Public landing page (optional)</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="{{ $labelClass }}">Landing subtitle</label>
            <input type="text" name="landing_meta[subtitle]" value="{{ $meta['subtitle'] ?? '' }}" class="{{ $inputClass }}" placeholder="Shown on category page hero">
        </div>
        <div class="md:col-span-2">
            <label class="{{ $labelClass }}">Landing about</label>
            <textarea name="landing_meta[about]" rows="3" class="{{ $inputClass }} resize-none" placeholder="Longer description on the public category page">{{ $meta['about'] ?? '' }}</textarea>
        </div>
        <div>
            <label class="{{ $labelClass }}">Hero gradient classes</label>
            <input type="text" name="landing_meta[hero_bg]" value="{{ $meta['hero_bg'] ?? '' }}" placeholder="from-primary-50 to-primary-50" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="{{ $labelClass }}">Icon URL</label>
            <input type="text" name="landing_meta[icon_url]" value="{{ $meta['icon_url'] ?? '' }}" class="{{ $inputClass }}" placeholder="https://...">
        </div>
    </div>
</div>
