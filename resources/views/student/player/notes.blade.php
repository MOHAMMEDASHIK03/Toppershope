@extends('student.layouts.app')
@section('title', $note->title . ' — Notes')
@section('page_title', $note->title)

@push('styles')
<style>
    /* ===== Anti-Screenshot via CSS ===== */
    @media print {
        body { display: none !important; }
    }

    /* Blurs the page when PrintScreen key is held */
    body.screenshot-blur * {
        filter: blur(20px) !important;
        transition: filter 0s;
    }

    /* PDF Viewer Container */
    .pdf-viewer-wrapper {
        position: relative;
        width: 100%;
        background: #0a0f1e;
        border-radius: 1rem;
        overflow: hidden;
        user-select: none;
        -webkit-user-select: none;
    }

    /* Anti-screenshot overlay that sits on top and captures Print events */
    .screenshot-shield {
        position: absolute;
        inset: 0;
        z-index: 5;
        pointer-events: none; /* Allows iframe interaction to pass through */
        background: transparent;
    }

    /* Watermark layer */
    .pdf-watermark {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .pdf-watermark span {
        font-size: 22px;
        font-weight: 900;
        color: rgba(255,255,255,0.06);
        transform: rotate(-30deg);
        white-space: nowrap;
        letter-spacing: 5px;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Toolbar */
    .pdf-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        background: rgba(15, 22, 41, 0.95);
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .pdf-toolbar-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .pdf-toolbar-btn.fullscreen {
        background: rgba(99,102,241,0.15);
        color: #818cf8;
        border: 1px solid rgba(99,102,241,0.3);
    }
    .pdf-toolbar-btn.fullscreen:hover {
        background: rgba(99,102,241,0.3);
    }

    /* Fullscreen override */
    .pdf-viewer-wrapper:fullscreen,
    .pdf-viewer-wrapper:-webkit-full-screen {
        width: 100vw;
        height: 100vh;
        border-radius: 0;
    }
    .pdf-viewer-wrapper:fullscreen iframe,
    .pdf-viewer-wrapper:-webkit-full-screen iframe {
        height: calc(100vh - 56px) !important;
    }
</style>
@endpush

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('student.my-courses.show', $enrollment->id) }}" class="hover:text-orange-600 transition-colors">← Back to Course</a>
</div>

<div class="max-w-5xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6 shadow-2xl shadow-sm border border-slate-200">

        {{-- PDF Toolbar --}}
        <div class="pdf-toolbar">
            <div>
                <h1 class="font-semibold text-slate-900 text-sm">{{ $note->title }}</h1>
                @if($note->description)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $note->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200 uppercase tracking-wider">🔒 Protected</span>
                <button class="pdf-toolbar-btn fullscreen" id="pdfFullscreenBtn" title="Fullscreen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M216,48V88a8,8,0,0,1-16,0V56H168a8,8,0,0,1,0-16h40A8,8,0,0,1,216,48ZM88,200H56V168a8,8,0,0,0-16,0v40a8,8,0,0,0,8,8H88a8,8,0,0,0,0-16Zm120-40a8,8,0,0,0-8,8v32H168a8,8,0,0,0,0,16h40a8,8,0,0,0,8-8V168A8,8,0,0,0,208,160ZM88,40H48a8,8,0,0,0-8,8V88a8,8,0,0,0,16,0V56H88a8,8,0,0,0,0-16Z"/></svg>
                    Fullscreen
                </button>
            </div>
        </div>

        {{-- PDF Display --}}
        <div class="pdf-viewer-wrapper" id="pdfViewerWrapper">
            @if($signedUrl)
                {{-- Disable toolbar, download button and nav panes in browser PDF viewer --}}
                <iframe
                    src="{{ $signedUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                    class="w-full"
                    style="height: 82vh; border: none; display: block;"
                ></iframe>
            @else
                <div class="p-20 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="text-slate-600" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34Z"/></svg>
                    </div>
                    <p class="text-slate-400 font-semibold">No PDF file uploaded yet.</p>
                    <p class="text-slate-600 text-sm mt-2">The instructor hasn't uploaded notes for this item.</p>
                </div>
            @endif

            {{-- Screenshot shield (visual only – slows down screenshotters) --}}
            <div class="screenshot-shield" id="screenshotShield"></div>

            {{-- Repeating watermark overlay --}}
            <div class="pdf-watermark">
                <span>{{ auth()->user()->name }} • {{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    // Fullscreen button
    const btn = document.getElementById('pdfFullscreenBtn');
    const wrapper = document.getElementById('pdfViewerWrapper');
    if (btn && wrapper) {
        btn.addEventListener('click', () => {
            const reqFs = wrapper.requestFullscreen || wrapper.webkitRequestFullscreen || wrapper.mozRequestFullScreen;
            if (reqFs) {
                const p = reqFs.call(wrapper);
                if (p) p.catch(err => console.error(err));
            }
        });
    }
})();
</script>
@endpush
