@props([
    'show' => 'showModal',
    'title',
    'subtitle' => null,
    'formAction',
    'submitLabel' => 'Create',
    'closeMethod' => 'closeModal()',
    'maxWidth' => 'max-w-lg',
    'layoutView' => null,
])


<template x-teleport="body">
    <div
        x-show="{{ $show }}"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
            x-show="{{ $show }}"
            x-transition.opacity
            @click="{{ $closeMethod }}"
        ></div>

        <div
            class="relative w-full {{ $maxWidth }} bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[min(90vh,720px)]"
            x-show="{{ $show }}"
            x-transition
            @click.stop
        >
            <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white shrink-0">
                <div>
                    @if(isset($eyebrow))
                        <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">{{ $eyebrow }}</p>
                    @endif
                    <h3 class="text-lg font-black text-slate-800 leading-tight">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="text-xs text-slate-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
                <button type="button" @click="{{ $closeMethod }}" class="shrink-0 w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/></svg>
                </button>
            </div>

            <form action="{{ $formAction }}" method="POST" class="flex flex-col min-h-0 flex-1">
                @csrf
                <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">
                    {{ $slot }}
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" @click="{{ $closeMethod }}" class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl font-bold text-sm">
                        {{ $submitLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
