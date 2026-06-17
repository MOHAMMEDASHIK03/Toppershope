@props([
    'paginator',
    'class' => '',
])

@if($paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && \App\Support\ListingPagination::shouldShow($paginator))
    @php
        $perPageOptions = \App\Support\ListingPagination::PER_PAGE_OPTIONS;
        $perPageFieldId = 'panel-per-page-' . $paginator->getPageName();
        $currentPerPage = (int) $paginator->perPage();
    @endphp
    <div {{ $attributes->merge(['class' => 'panel-pagination px-4 sm:px-5 py-4 border-t border-slate-100 bg-white ' . $class]) }}>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">
                Showing
                <span class="font-semibold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="font-semibold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold text-slate-800">{{ $paginator->total() }}</span>
                results
            </p>

            <div class="flex flex-wrap items-center gap-3 sm:justify-end">
                <div x-data="panelPerPagePicker()" class="flex items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 whitespace-nowrap">Per page</span>

                    <div class="relative">
                        <button type="button"
                                x-ref="trigger"
                                @click="toggle()"
                                :aria-expanded="open"
                                aria-haspopup="listbox"
                                class="panel-pagination-per-page-trigger">
                            <span>{{ $currentPerPage }}</span>
                            <i class="ph ph-caret-down text-base transition-transform duration-150"
                               :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open"
                             x-cloak
                             x-ref="menu"
                             @click.outside="close()"
                             :style="menuStyle"
                             class="panel-pagination-per-page-menu"
                             role="listbox">
                            @foreach($perPageOptions as $size)
                                <a href="{{ \App\Support\ListingPagination::perPageChangeUrl(request(), $size) }}"
                                   role="option"
                                   id="{{ $perPageFieldId }}-{{ $size }}"
                                   class="panel-pagination-per-page-option {{ $currentPerPage === $size ? 'is-active' : '' }}"
                                   @click="close()">
                                    {{ $size }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{ $paginator->withQueryString()->links('vendor.pagination.panel') }}
            </div>
        </div>
    </div>
@endif
