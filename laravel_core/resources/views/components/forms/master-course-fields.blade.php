@props([
    'categories' => null,
])

<div>
    <x-form.field label="Course title" name="title" :required="true" placeholder="e.g. IIT JEE Complete Preparation" />
</div>
<div>
    <x-form.field label="Description" name="description" type="textarea" :required="true" rows="3" placeholder="Brief scope of this master course..." />
</div>
<div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 space-y-4 shadow-sm">
    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Classification</p>
    <x-category-fields :categories="$categories" :subcategory-required="false" layout="stacked" />
</div>
