@props(['active' => true])

@if($active)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold text-[10px] uppercase tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
    </span>
@else
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold text-[10px] uppercase tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
    </span>
@endif
