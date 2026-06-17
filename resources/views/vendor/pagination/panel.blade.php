@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="panel-pagination-nav">
        <span class="inline-flex rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-2.5 py-2 text-sm text-slate-300 bg-white border-r border-slate-200 cursor-not-allowed" aria-disabled="true">
                    <i class="ph ph-caret-left text-base"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center px-2.5 py-2 text-sm text-slate-600 bg-white border-r border-slate-200 hover:bg-slate-50 transition-colors"
                   aria-label="Previous page">
                    <i class="ph ph-caret-left text-base"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center px-3 py-2 text-sm text-slate-500 bg-white border-r border-slate-200">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex items-center px-3.5 py-2 text-sm font-semibold text-slate-800 bg-slate-100 border-r border-slate-200">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-600 bg-white border-r border-slate-200 hover:bg-slate-50 transition-colors"
                               aria-label="Go to page {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center px-2.5 py-2 text-sm text-slate-600 bg-white hover:bg-slate-50 transition-colors"
                   aria-label="Next page">
                    <i class="ph ph-caret-right text-base"></i>
                </a>
            @else
                <span class="inline-flex items-center px-2.5 py-2 text-sm text-slate-300 bg-white cursor-not-allowed" aria-disabled="true">
                    <i class="ph ph-caret-right text-base"></i>
                </span>
            @endif
        </span>
    </nav>
@endif
