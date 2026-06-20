@extends('student.layouts.app')

@section('title', $note->title . ' — Notes')

@section('page_title', $note->title)



@push('styles')

<style>

    @media print {

        body { display: none !important; }

    }



    body.screenshot-blur * {

        filter: blur(20px) !important;

        transition: filter 0s;

    }



    .pdf-viewer-wrapper {

        position: relative;

        width: 100%;

        background: #f1f5f9;

        overflow: hidden;

        user-select: none;

        -webkit-user-select: none;

    }



    html.dark .pdf-viewer-wrapper {

        background: #0f172a;

    }



    .screenshot-shield {

        position: absolute;

        inset: 0;

        z-index: 5;

        pointer-events: none;

        background: transparent;

    }



    .pdf-toolbar {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 1rem;

        padding: 12px 20px;

        background: #fff;

        border-bottom: 1px solid #e2e8f0;

    }



    html.dark .pdf-toolbar {
        background: #111114;
        border-bottom-color: #2a2a32;

    }



    .pdf-toolbar__title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.35;

    }



    html.dark .pdf-toolbar__title {

        color: #f8fafc;

    }



    .pdf-toolbar__desc {

        font-size: 0.75rem;

        color: #64748b;

        margin-top: 0.125rem;

    }



    html.dark .pdf-toolbar__desc {
        color: #94a3b8;
    }



    .pdf-toolbar__actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .pdf-toolbar-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }



    .pdf-toolbar-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #7723D6;
    }

    html.dark .pdf-toolbar-btn {
        background: #18181c;
        border-color: #2a2a32;
        color: #cbd5e1;
    }

    html.dark .pdf-toolbar-btn:hover {
        background: #1f1f24;
        border-color: #3f3f46;
        color: #c084fc;
    }



    .pdf-toolbar-btn--icon {
        width: 36px;
        height: 36px;
        padding: 0;
    }

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
    <a href="{{ route('student.my-courses.show', $enrollment->id) }}" class="hover:text-primary-700 transition-colors">← Back to Course</a>
</div>



<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-[#111114] border border-slate-200 dark:border-[#2a2a32] rounded-xl shadow-sm overflow-hidden">
        <div class="pdf-toolbar">
            <div class="min-w-0">
                <h1 class="pdf-toolbar__title truncate">{{ $note->title }}</h1>
                @if($note->description)
                    <p class="pdf-toolbar__desc truncate">{{ $note->description }}</p>
                @endif

            </div>

            <div class="pdf-toolbar__actions">

                @if($signedUrl && ($downloadUrl ?? null))

                    <a href="{{ $downloadUrl }}" class="pdf-toolbar-btn pdf-toolbar-btn--icon" title="Download PDF" download="{{ $note->downloadFilename() }}">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,152v56a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V152a16,16,0,0,1,16-16H80a8,8,0,0,1,0,16H48v56H208V152H176a8,8,0,0,1,0-16h32A16,16,0,0,1,224,152ZM93.66,77.66,120,51.31V128a8,8,0,0,0,16,0V51.31l26.34,26.35a8,8,0,0,0,11.32-11.32l-40-40a8,8,0,0,0-11.32,0l-40,40A8,8,0,0,0,93.66,77.66Z"/></svg>

                    </a>

                @endif

                <button type="button" class="pdf-toolbar-btn" id="pdfFullscreenBtn" title="Fullscreen">

                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M216,48V88a8,8,0,0,1-16,0V56H168a8,8,0,0,1,0-16h40A8,8,0,0,1,216,48ZM88,200H56V168a8,8,0,0,0-16,0v40a8,8,0,0,0,8,8H88a8,8,0,0,0,0-16Zm120-40a8,8,0,0,0-8,8v32H168a8,8,0,0,0,0,16h40a8,8,0,0,0,8-8V168A8,8,0,0,0,208,160ZM88,40H48a8,8,0,0,0-8,8V88a8,8,0,0,0,16,0V56H88a8,8,0,0,0,0-16Z"/></svg>

                    Fullscreen

                </button>

            </div>

        </div>



        <div class="pdf-viewer-wrapper" id="pdfViewerWrapper">

            @if($signedUrl)

                <iframe

                    src="{{ $signedUrl }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"

                    class="w-full"

                    style="height: 82vh; border: none; display: block;"

                    title="{{ $note->title }}"

                ></iframe>

            @else

                <div class="p-20 text-center bg-white dark:bg-[#111114]">

                    <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="text-slate-600" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34Z"/></svg>

                    </div>

                    <p class="text-slate-500 dark:text-slate-400 font-semibold">No PDF file uploaded yet.</p>

                    <p class="text-slate-600 dark:text-slate-500 text-sm mt-2">The instructor hasn't uploaded notes for this item.</p>

                </div>

            @endif



            <div class="screenshot-shield" id="screenshotShield"></div>

        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

(function() {

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

