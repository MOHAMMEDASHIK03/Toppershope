@extends('student.layouts.app')
@section('title', $video->title)
@section('page_title', $video->title)

@push('styles')
<style>
    /* Custom Player Styles */
    .custom-player-wrapper {
        position: relative;
        width: 100%;
        background: #000;
        overflow: hidden;
        border-radius: 1rem;
        user-select: none;
        -webkit-user-select: none;
    }

    #customVideoPlayer {
        width: 100%;
        height: 100%;
        aspect-ratio: 16 / 9;
        display: block;
        pointer-events: auto; /* Ensure video can be clicked/interacted with via JS */
    }

    /* Watermark - secure but click-through */
    .watermark-overlay {
        position: absolute; inset: 0; pointer-events: none; z-index: 5;
        background: repeating-linear-gradient(
            -45deg,
            transparent, transparent 200px,
            rgba(255,255,255,0.01) 200px, rgba(255,255,255,0.01) 201px
        );
        display: flex; align-items: center; justify-content: center;
    }
    .watermark-text {
        color: rgba(255,255,255,0.05); font-size: 20px; font-weight: 900;
        transform: rotate(-30deg); white-space: nowrap; text-transform: uppercase;
        letter-spacing: 4px; pointer-events: none;
    }

    /* Controls Overlay */
    .player-controls {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
        padding: 40px 20px 15px;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex; flex-direction: column; gap: 10px;
    }
    
    .custom-player-wrapper:hover .player-controls,
    .custom-player-wrapper.paused .player-controls,
    .custom-player-wrapper.active .player-controls {
        opacity: 1;
    }

    /* Timeline Bar */
    .timeline-container {
        width: 100%; height: 5px; background: rgba(255,255,255,0.2);
        border-radius: 3px; cursor: pointer; position: relative;
        transition: height 0.1s ease;
    }
    .timeline-container:hover { height: 8px; }
    .timeline-progress {
        height: 100%; background: #6366f1; border-radius: 3px;
        width: 0%; pointer-events: none; position: relative;
    }
    .timeline-thumb {
        position: absolute; right: -6px; top: 50%; transform: translateY(-50%) scale(0);
        width: 12px; height: 12px; background: #fff; border-radius: 50%;
        transition: transform 0.1s ease; pointer-events: none;
        box-shadow: 0 0 10px rgba(99,102,241,0.5);
    }
    .timeline-container:hover .timeline-thumb { transform: translateY(-50%) scale(1); }
    .timeline-buffer {
        position: absolute; top: 0; left: 0; height: 100%;
        background: rgba(255,255,255,0.4); border-radius: 3px; z-index: -1; width: 0%; pointer-events: none;
    }

    /* Buttons */
    .control-btn {
        background: transparent; border: none; color: #fff;
        cursor: pointer; width: 40px; height: 40px;
        display: flex; items: center; justify-content: center;
        border-radius: 50%; transition: all 0.2s;
    }
    .control-btn:hover { background: rgba(255,255,255,0.1); color: #818cf8; }
    .control-btn svg { width: 22px; height: 22px; }

    /* Volume */
    .volume-container { display: flex; items: center; gap: 5px; }
    .volume-slider-wrapper { width: 0px; overflow: hidden; transition: width 0.3s ease; display: flex; align-items: center; }
    .volume-container:hover .volume-slider-wrapper { width: 80px; }
    .volume-slider { width: 100%; cursor: pointer; accent-color: #6366f1; height: 4px; }

    /* Time Display */
    .time-display { font-size: 13px; font-weight: 500; font-family: 'Inter', sans-serif; color: #cbd5e1; user-select: none; }

    /* Settings Menu */
    .settings-menu {
        position: absolute; bottom: 70px; right: 20px;
        background: rgba(15,22,41,0.95); backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
        padding: 8px 0; min-width: 150px; z-index: 20;
        display: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5);
    }
    .settings-menu.active { display: block; }
    .settings-item {
        padding: 10px 20px; display: flex; justify-content: space-between; items: center;
        cursor: pointer; color: #e2e8f0; font-size: 13px; font-weight: 500;
        transition: all 0.2s;
    }
    .settings-item:hover { background: rgba(99,102,241,0.2); color: #fff; }

    /* Loading Spinner */
    .loading-spinner {
        position: absolute; inset: 0; display: none;
        align-items: center; justify-content: center;
        background: rgba(0,0,0,0.5); z-index: 8;
    }
    .custom-player-wrapper.loading .loading-spinner { display: flex; }
    
    /* Center Play/Pause Indicator */
    .center-state-indicator {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.5);
        width: 60px; height: 60px; background: rgba(0,0,0,0.6); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; z-index: 9; opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: none;
    }
    .center-state-indicator.animate { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    .center-state-indicator svg { width: 30px; height: 30px; }

    /* Native player fallback styles */
    video::-webkit-media-controls { display: none !important; }

    /* YouTube Iframe strict sizing */
    #youtubePlayerContainer iframe {
        width: 100% !important;
        height: 100% !important;
        border-radius: 1rem;
    }

    /* Fullscreen specific fixes */
    .custom-player-wrapper:fullscreen,
    .custom-player-wrapper:-webkit-full-screen {
        width: 100vw !important;
        height: 100vh !important;
        max-width: none !important;
        border-radius: 0 !important;
        background-color: #000 !important;
    }
    .custom-player-wrapper:fullscreen #youtubePlayerContainer iframe,
    .custom-player-wrapper:-webkit-full-screen #youtubePlayerContainer iframe {
        border-radius: 0 !important;
    }

</style>
@endpush

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('student.my-courses.show', $enrollment->id) }}" class="hover:text-primary-700 transition-colors">← Back to Course</a>
</div>

<div class="max-w-5xl mx-auto">
    {{-- Custom Video Player --}}
    <div class="bg-white border border-slate-200 rounded-xl p-1 shadow-sm rounded-3xl mb-6 border border-slate-200 shadow-2xl shadow-primary-500/10">
        <div class="custom-player-wrapper paused" id="playerWrapper">
            
            @if($signedUrl)
                <video id="customVideoPlayer" preload="metadata" playsinline crossorigin="anonymous">
                    <source src="{{ $signedUrl }}" type="video/mp4">
                    Your browser does not support video playback.
                </video>
            @elseif($youtubeId)
                <div id="youtubePlayerContainer" class="w-full h-full aspect-video pointer-events-none">
                    <div id="youtubePlayer"></div>
                </div>
                <!-- Transparent overlay to block YouTube clicks -->
                <div class="absolute inset-0 bg-transparent z-10" id="youtubeOverlay"></div>
            @elseif($video->video_url)
                 {{-- For other iframes, we can't easily skin them --}}
                <iframe src="{{ $video->video_url }}" class="w-full aspect-video rounded-2xl" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            @else
                <div class="w-full aspect-video flex-col gap-4 flex items-center justify-center bg-slate-900 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-slate-600" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,88Zm-32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h48A8,8,0,0,1,144,136Zm32,48a8,8,0,0,1-8,8H88a8,8,0,0,1,0-16h80A8,8,0,0,1,176,184Z"/></svg>
                    <p class="text-slate-500 font-bold">No video uploaded yet.</p>
                </div>
            @endif

            @if($signedUrl || $youtubeId)
            {{-- Watermark --}}
            <div class="watermark-overlay">
                <span class="watermark-text">{{ auth()->user()->name }} • {{ auth()->user()->email }}</span>
            </div>

            {{-- Loading Spinner --}}
            <div class="loading-spinner">
                <svg class="animate-spin text-primary-500 w-12 h-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            {{-- Center Action Indicator --}}
            <div class="center-state-indicator" id="centerIndicator">
                <svg id="centerPlayIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M232.4,114.49,88.32,26.35a16,16,0,0,0-16.2-.3A15.86,15.86,0,0,0,64,40.74V215.26a15.94,15.94,0,0,0,8.12,13.9,16,16,0,0,0,16.2-.3L232.4,141.72a16,16,0,0,0,0-27.23Z"/></svg>
                <svg id="centerPauseIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M216,48V208a16,16,0,0,1-16,16H160a16,16,0,0,1-16-16V48a16,16,0,0,1,16-16h40A16,16,0,0,1,216,48ZM96,32H56A16,16,0,0,0,40,48V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V48A16,16,0,0,0,96,32Z"/></svg>
            </div>

            {{-- Custom Controls UI --}}
            <div class="player-controls">
                {{-- Timeline --}}
                <div class="timeline-container" id="timelineContainer">
                    <div class="timeline-buffer" id="timelineBuffer"></div>
                    <div class="timeline-progress" id="timelineProgress">
                        <div class="timeline-thumb"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center gap-1">
                        {{-- Play/Pause --}}
                        <button class="control-btn" id="playPauseBtn" title="Play (k)">
                            <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M232.4,114.49,88.32,26.35a16,16,0,0,0-16.2-.3A15.86,15.86,0,0,0,64,40.74V215.26a15.94,15.94,0,0,0,8.12,13.9,16,16,0,0,0,16.2-.3L232.4,141.72a16,16,0,0,0,0-27.23Z"/></svg>
                            <svg id="pauseIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M216,48V208a16,16,0,0,1-16,16H160a16,16,0,0,1-16-16V48a16,16,0,0,1,16-16h40A16,16,0,0,1,216,48ZM96,32H56A16,16,0,0,0,40,48V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V48A16,16,0,0,0,96,32Z"/></svg>
                        </button>

                        {{-- -5s --}}
                        <button class="control-btn" id="rewindBtn" title="Rewind 5s (Arrow Left)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M24,128A104,104,0,1,1,128,232,104.11,104.11,0,0,1,24,128Zm104,88a88,88,0,1,0-88-88A88.1,88.1,0,0,0,128,216Zm56-88a8,8,0,0,0-8-8H131.31l18.35-18.34a8,8,0,0,0-11.32-11.32l-32,32a8,8,0,0,0,0,11.32l32,32a8,8,0,0,0,11.32-11.32L131.31,136H176A8,8,0,0,0,184,128Z"/></svg>
                        </button>
                        
                        {{-- +5s --}}
                        <button class="control-btn" id="forwardBtn" title="Forward 5s (Arrow Right)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm21.66-93.66a8,8,0,0,0,0-11.32l-32-32a8,8,0,0,0-11.32,11.32L124.69,108H80a8,8,0,0,0,0,16h44.69l-18.35,18.34a8,8,0,0,0,11.32,11.32Z"/></svg>
                        </button>

                        {{-- Volume --}}
                        <div class="volume-container group ml-1">
                            <button class="control-btn" id="muteBtn" title="Mute (m)">
                                <svg id="volHighIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M80,88H32a8,8,0,0,0-8,8v64a8,8,0,0,0,8,8H80l58.34,58.34A8,8,0,0,0,152,216V40a8,8,0,0,0-13.66-5.66ZM200,128a71.85,71.85,0,0,1-19.49,49.2,8,8,0,0,1-11-11.66A55.85,55.85,0,0,0,184,128a55.85,55.85,0,0,0-14.51-37.54,8,8,0,1,1,11-11.66A71.85,71.85,0,0,1,200,128Zm32,0a103.73,103.73,0,0,1-29.28,72.48,8,8,0,1,1-11.08-11.51,87.75,87.75,0,0,0,24.36-60.97,87.75,87.75,0,0,0-24.36-60.97,8,8,0,1,1,11.08-11.51A103.73,103.73,0,0,1,232,128Z"/></svg>
                                <svg id="volMuteIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M213.66,42.34l-171.32,171.32a8,8,0,0,1-11.32-11.32L53.92,179H32a8,8,0,0,1-8-8V88a8,8,0,0,1,8-8H80l58.34-58.34A8,8,0,0,1,152,40v89.08l61.66-61.66a8,8,0,0,1,11.32,11.32ZM136,193.08v22.92a8,8,0,0,0,13.66,5.66l22.6-22.6Z"/></svg>
                            </button>
                            <div class="volume-slider-wrapper">
                                <input type="range" class="volume-slider" id="volumeSlider" min="0" max="1" step="0.05" value="1">
                            </div>
                        </div>

                        {{-- Timestamps --}}
                        <div class="time-display ml-2">
                            <span id="currentTime">00:00</span> / <span id="duration">00:00</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        {{-- Playback Speed Menu --}}
                        <div class="relative">
                            <button class="control-btn" id="speedBtn" title="Playback Speed">
                                <span class="text-xs font-bold pointer-events-none">1x</span>
                            </button>
                            <div class="settings-menu" id="speedMenu">
                                <div class="settings-item" data-speed="0.5">0.5x</div>
                                <div class="settings-item" data-speed="0.75">0.75x</div>
                                <div class="settings-item text-primary-700" data-speed="1">1x (Normal)</div>
                                <div class="settings-item" data-speed="1.25">1.25x</div>
                                <div class="settings-item" data-speed="1.5">1.5x</div>
                                <div class="settings-item" data-speed="2">2x</div>
                            </div>
                        </div>

                        {{-- Quality Menu --}}
                        <div class="relative">
                            <button class="control-btn" id="qualityBtn" title="Video Quality">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Zm88-29.84q.06-2.16,0-4.32l14.92-18.64a8,8,0,0,0,1.48-7.06,107.21,107.21,0,0,0-10.88-26.25,8,8,0,0,0-6-3.93l-23.72-2.61a89.83,89.83,0,0,0-15.23-26.54L186.27,20a8,8,0,0,0-2.48-6.72,107.9,107.9,0,0,0-26.85-8.87,8,8,0,0,0-7.46,2.86L134.42,26a89.2,89.2,0,0,0-12.84,0L106.52,7.24a8,8,0,0,0-7.46-2.86,107.21,107.21,0,0,0-26.25,10.88,8,8,0,0,0-2.48,6.72L80.89,45.74A89.83,89.83,0,0,0,65.66,72.28L41.94,69.67a8,8,0,0,0-6,3.93A107.21,107.21,0,0,0,25.07,99.85a8,8,0,0,0,1.48,7.06L41.47,125.55A88.58,88.58,0,0,0,40,128a88.58,88.58,0,0,0,1.47,2.45L26.55,149.09a8,8,0,0,0-1.48,7.06,107.21,107.21,0,0,0,10.88,26.25,8,8,0,0,0,6,3.93l23.72,2.61a89.83,89.83,0,0,0,15.23,26.54l-9.66,20.73a8,8,0,0,0,2.48,6.72,107.9,107.9,0,0,0,26.85,8.87,8,8,0,0,0,7.46-2.86L121.58,230a89.2,89.2,0,0,0,12.84,0l15.06,18.78a8,8,0,0,0,7.46,2.86,107.21,107.21,0,0,0,26.25-10.88,8,8,0,0,0,2.48-6.72l-10.46-23.76a89.83,89.83,0,0,0,15.23-26.54l23.72,2.61a8,8,0,0,0,6-3.93,107.21,107.21,0,0,0,10.88-26.25,8,8,0,0,0-1.48-7.06ZM168,128V128a16,16,0,0,1-16,16H104a16,16,0,0,1-16-16v0a16,16,0,0,1,16-16h48A16,16,0,0,1,168,128Z"/></svg>
                            </button>
                            <div class="settings-menu" id="qualityMenu">
                                <div class="settings-item text-primary-700" data-quality="auto">Auto</div>
                            </div>
                        </div>

                        {{-- Picture in Picture --}}
                        <button class="control-btn" id="pipBtn" title="Picture-in-Picture">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M216,48H40A16,16,0,0,0,24,64V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V64A16,16,0,0,0,216,48Zm0,144H40V64H216V192ZM128,152a8,8,0,0,1,8-8h56a8,8,0,0,1,8,8v24a8,8,0,0,1-8,8H136a8,8,0,0,1-8-8Z"/></svg>
                        </button>

                        {{-- Fullscreen --}}
                        <button class="control-btn" id="fullscreenBtn" title="Fullscreen (f)">
                            <svg id="enterFs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M216,48V88a8,8,0,0,1-16,0V56H168a8,8,0,0,1,0-16h40A8,8,0,0,1,216,48ZM88,200H56V168a8,8,0,0,0-16,0v40a8,8,0,0,0,8,8H88a8,8,0,0,0,0-16Zm120-40a8,8,0,0,0-8,8v32H168a8,8,0,0,0,0,16h40a8,8,0,0,0,8-8V168A8,8,0,0,0,208,160ZM88,40H48a8,8,0,0,0-8,8V88a8,8,0,0,0,16,0V56H88a8,8,0,0,0,0-16Z"/></svg>
                            <svg id="exitFs" class="hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M216,96a8,8,0,0,1-8,8H168a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0V88h32A8,8,0,0,1,216,96ZM88,160H48a8,8,0,0,0,0,16H88v32a8,8,0,0,0,16,0V168A8,8,0,0,0,88,160Zm120,0H168v-32a8,8,0,0,0-16,0v40a8,8,0,0,0,8,8h48a8,8,0,0,0,0-16ZM88,40a8,8,0,0,0-8,8V88H48a8,8,0,0,0,0,16H88a8,8,0,0,0,8-8V48A8,8,0,0,0,88,40Z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Video Info Section --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 mb-12 shadow-xl shadow-sm border border-slate-200">
        <h1 class="text-2xl font-bold text-slate-900 mb-3">{{ $video->title }}</h1>
        @if($video->description)
            <p class="text-slate-500 text-sm leading-relaxed max-w-4xl">{{ $video->description }}</p>
        @endif
        
        <div class="mt-6 flex flex-wrap items-center gap-4 text-xs font-medium">
            @if($video->duration_minutes)
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-primary-700" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h56A8,8,0,0,1,192,128Z"/></svg>
                    <span>{{ $video->duration_minutes }} Minutes</span>
                </div>
            @endif
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-50 text-emerald-700 border border-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"/></svg>
                <span>HD Streaming Available</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-500/10 text-sky-400 border border-primary-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M168,104a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,104Zm-8,40H96a8,8,0,0,0,0,16h64a8,8,0,0,0,0-16Zm56-96V208a16,16,0,0,1-16,16H56a16,16,0,0,1-16-16V48A16,16,0,0,1,56,32H200A16,16,0,0,1,216,48ZM200,48H56V208H200Z"/></svg>
                <span>Syllabus Aligned</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Custom Video Player Logic
document.addEventListener('DOMContentLoaded', () => {
    let playerType = 'none';
    const video = document.getElementById('customVideoPlayer');
    
    @if($youtubeId)
        playerType = 'youtube';
    @elseif($signedUrl)
        playerType = 'html5';
    @endif

    if(playerType === 'none') return;

    const wrapper = document.getElementById('playerWrapper');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const centerIndicator = document.getElementById('centerIndicator');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    const centerPlayIcon = document.getElementById('centerPlayIcon');
    const centerPauseIcon = document.getElementById('centerPauseIcon');
    const timelineContainer = document.getElementById('timelineContainer');
    const timelineProgress = document.getElementById('timelineProgress');
    const timelineBuffer = document.getElementById('timelineBuffer');
    const currentTimeEl = document.getElementById('currentTime');
    const durationEl = document.getElementById('duration');
    const muteBtn = document.getElementById('muteBtn');
    const volHighIcon = document.getElementById('volHighIcon');
    const volMuteIcon = document.getElementById('volMuteIcon');
    const volumeSlider = document.getElementById('volumeSlider');
    const speedBtn = document.getElementById('speedBtn');
    const speedMenu = document.getElementById('speedMenu');
    const speedOptions = document.querySelectorAll('#speedMenu .settings-item');
    const qualityBtn = document.getElementById('qualityBtn');
    const qualityMenu = document.getElementById('qualityMenu');
    let qualityOptions = document.querySelectorAll('#qualityMenu .settings-item');
    
    const pipBtn = document.getElementById('pipBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const enterFs = document.getElementById('enterFs');
    const exitFs = document.getElementById('exitFs');
    const rewindBtn = document.getElementById('rewindBtn');
    const forwardBtn = document.getElementById('forwardBtn');

    let isDraggingTimeline = false;
    let hideControlsTimeout;
    
    let ytPlayer = null;
    let isYtPlaying = false;
    let ytTimeInterval;

    // Formatting Time
    function formatTime(seconds) {
        if(isNaN(seconds)) return "00:00";
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        if (h > 0) return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    if (playerType === 'youtube') {
        // Load YouTube API
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        
        // Hide PiP button for YouTube since it's poorly supported for iframes
        pipBtn.style.display = 'none';

        window.onYouTubeIframeAPIReady = function() {
            ytPlayer = new YT.Player('youtubePlayer', {
                height: '100%',
                width: '100%',
                videoId: '{{ $youtubeId }}',
                playerVars: {
                    'playsinline': 1,
                    'controls': 0,
                    'disablekb': 1,
                    'fs': 0,
                    'modestbranding': 1,
                    'rel': 0,
                    'showinfo': 0,
                    'iv_load_policy': 3
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        };
        function onPlayerReady(event) {
            durationEl.textContent = formatTime(ytPlayer.getDuration());
            updateVolumeUI();
            
            // Populate quality menu with standard options (YouTube will auto-downgrade if a level isn't available)
            const qualityLabels = [
                { key: 'hd1080', label: '1080p' },
                { key: 'hd720',  label: '720p' },
                { key: 'large',  label: '480p' },
                { key: 'medium', label: '360p' },
                { key: 'small',  label: '240p' },
            ];
            qualityMenu.innerHTML = '<div class="settings-item text-primary-700" data-quality="default">Auto</div>';
            qualityLabels.forEach(({ key, label }) => {
                qualityMenu.insertAdjacentHTML('beforeend', `<div class="settings-item" data-quality="${key}">${label}</div>`);
            });
            bindQualityEvents();
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.PLAYING) {
                isYtPlaying = true;
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                wrapper.classList.remove('paused');
                wrapper.classList.remove('loading');
                startYtTimeTracker();
            } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
                isYtPlaying = false;
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                wrapper.classList.add('paused');
                clearInterval(ytTimeInterval);
            } else if (event.data === YT.PlayerState.BUFFERING) {
                wrapper.classList.add('loading');
            }
        }
        
        function startYtTimeTracker() {
            clearInterval(ytTimeInterval);
            ytTimeInterval = setInterval(() => {
                if (ytPlayer && isYtPlaying) {
                    const currentTime = ytPlayer.getCurrentTime();
                    const duration = ytPlayer.getDuration();
                    currentTimeEl.textContent = formatTime(currentTime);
                    if (!isDraggingTimeline) {
                        const progressPercent = (currentTime / duration) * 100;
                        timelineProgress.style.width = `${progressPercent}%`;
                    }
                    const loaded = ytPlayer.getVideoLoadedFraction();
                    timelineBuffer.style.width = `${loaded * 100}%`;
                }
            }, 200);
        }

        const ytOverlay = document.getElementById('youtubeOverlay');
        if (ytOverlay) {
            ytOverlay.addEventListener('click', togglePlay);
            ytOverlay.addEventListener('dblclick', toggleFullscreen);
        }
    }

    // Play/Pause
    function togglePlay() {
        if (playerType === 'html5' && video) {
            if (video.paused) {
                video.play();
                showCenterIndicator('play');
            } else {
                video.pause();
                showCenterIndicator('pause');
            }
        } else if (playerType === 'youtube' && ytPlayer) {
            const state = ytPlayer.getPlayerState();
            if (state === YT.PlayerState.PLAYING) {
                ytPlayer.pauseVideo();
                showCenterIndicator('pause');
            } else {
                ytPlayer.playVideo();
                showCenterIndicator('play');
            }
        }
    }

    function showCenterIndicator(state) {
        centerPlayIcon.classList.add('hidden');
        centerPauseIcon.classList.add('hidden');
        
        if(state === 'play') centerPlayIcon.classList.remove('hidden');
        if(state === 'pause') centerPauseIcon.classList.remove('hidden');
        
        centerIndicator.classList.remove('animate');
        void centerIndicator.offsetWidth; // trigger reflow
        centerIndicator.classList.add('animate');
        
        setTimeout(() => {
            centerIndicator.classList.remove('animate');
        }, 500);
    }

    // Event Listeners
    playPauseBtn.addEventListener('click', togglePlay);
    if(video) video.addEventListener('click', togglePlay);
    
    if (video) {
        video.addEventListener('play', () => {
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            wrapper.classList.remove('paused');
        });

        video.addEventListener('pause', () => {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            wrapper.classList.add('paused');
        });

        // Time Update & Buffer
        video.addEventListener('loadedmetadata', () => {
            durationEl.textContent = formatTime(video.duration);
        });

        video.addEventListener('timeupdate', () => {
            currentTimeEl.textContent = formatTime(video.currentTime);
            if (!isDraggingTimeline) {
                const progressPercent = (video.currentTime / video.duration) * 100;
                timelineProgress.style.width = `${progressPercent}%`;
            }
        });

        video.addEventListener('progress', () => {
            if (video.buffered.length > 0) {
                const bufferedEnd = video.buffered.end(video.buffered.length - 1);
                const duration = video.duration;
                const bufferPercent = (bufferedEnd / duration) * 100;
                timelineBuffer.style.width = `${bufferPercent}%`;
            }
        });

        video.addEventListener('waiting', () => wrapper.classList.add('loading'));
        video.addEventListener('playing', () => wrapper.classList.remove('loading'));
        video.addEventListener('canplay', () => wrapper.classList.remove('loading'));
    }

    // Seeking
    function updateTimeline(e) {
        const rect = timelineContainer.getBoundingClientRect();
        const pos = (e.clientX - rect.left) / rect.width;
        
        let duration = 0;
        if(playerType === 'html5' && video) duration = video.duration;
        else if(playerType === 'youtube' && ytPlayer) duration = ytPlayer.getDuration();
        
        const newTime = pos * duration;
        let p = pos * 100;
        if(p < 0) p = 0;
        if(p > 100) p = 100;
        timelineProgress.style.width = `${p}%`;
        return Math.max(0, Math.min(newTime, duration));
    }

    function setSeekTime(newTime) {
        if (playerType === 'html5' && video) {
            video.currentTime = newTime;
        } else if (playerType === 'youtube' && ytPlayer) {
            ytPlayer.seekTo(newTime, true);
        }
    }

    timelineContainer.addEventListener('mousedown', (e) => {
        isDraggingTimeline = true;
        setSeekTime(updateTimeline(e));
    });
    
    document.addEventListener('mousemove', (e) => {
        if (isDraggingTimeline) {
            updateTimeline(e); // visual update only while dragging
        }
    });

    document.addEventListener('mouseup', (e) => {
        if (isDraggingTimeline) {
            isDraggingTimeline = false;
            setSeekTime(updateTimeline(e));
        }
    });

    // Volume
    muteBtn.addEventListener('click', () => {
        if (playerType === 'html5' && video) {
            video.muted = !video.muted;
        } else if (playerType === 'youtube' && ytPlayer) {
            if (ytPlayer.isMuted()) ytPlayer.unMute();
            else ytPlayer.mute();
        }
        updateVolumeUI();
    });

    volumeSlider.addEventListener('input', (e) => {
        const val = parseFloat(e.target.value);
        if (playerType === 'html5' && video) {
            video.volume = val;
            video.muted = val === 0;
        } else if (playerType === 'youtube' && ytPlayer) {
            ytPlayer.setVolume(val * 100);
            if(val === 0) ytPlayer.mute(); else ytPlayer.unMute();
        }
        updateVolumeUI(val);
    });

    function updateVolumeUI(inputVal = null) {
        let isMuted = false;
        let vol = 1;
        
        if (playerType === 'html5' && video) {
            isMuted = video.muted || video.volume === 0;
            vol = video.volume;
        } else if (playerType === 'youtube' && ytPlayer) {
            isMuted = ytPlayer.isMuted() || ytPlayer.getVolume() === 0;
            vol = ytPlayer.getVolume() / 100;
        }

        if(inputVal !== null) vol = inputVal;

        if (isMuted || vol === 0) {
            volHighIcon.classList.add('hidden');
            volMuteIcon.classList.remove('hidden');
            volumeSlider.value = 0;
        } else {
            volHighIcon.classList.remove('hidden');
            volMuteIcon.classList.add('hidden');
            volumeSlider.value = vol;
        }
    }

    // Skip Buttons
    rewindBtn.addEventListener('click', () => { 
        if(playerType === 'html5' && video) video.currentTime -= 5; 
        else if(playerType === 'youtube' && ytPlayer) ytPlayer.seekTo(ytPlayer.getCurrentTime() - 5, true);
    });
    
    forwardBtn.addEventListener('click', () => { 
        if(playerType === 'html5' && video) video.currentTime += 5; 
        else if(playerType === 'youtube' && ytPlayer) ytPlayer.seekTo(ytPlayer.getCurrentTime() + 5, true);
    });

    // Playback Speed
    speedBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        speedMenu.classList.toggle('active');
    });

    speedOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            const speed = e.target.getAttribute('data-speed');
            if (playerType === 'html5' && video) {
                video.playbackRate = parseFloat(speed);
            } else if (playerType === 'youtube' && ytPlayer) {
                ytPlayer.setPlaybackRate(parseFloat(speed));
            }
            
            speedOptions.forEach(opt => opt.classList.remove('text-primary-700'));
            e.target.classList.add('text-primary-700');
            
            speedBtn.querySelector('span').textContent = speed + 'x';
            speedMenu.classList.remove('active');
        });
    });

    // Video Quality
    qualityBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        speedMenu.classList.remove('active');
        qualityMenu.classList.toggle('active');
    });

    function bindQualityEvents() {
        qualityOptions = document.querySelectorAll('#qualityMenu .settings-item');
        qualityOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                const quality = e.target.getAttribute('data-quality');
                
                if (playerType === 'youtube' && ytPlayer) {
                    ytPlayer.setPlaybackQuality(quality);
                }
                
                qualityOptions.forEach(opt => opt.classList.remove('text-primary-700'));
                e.target.classList.add('text-primary-700');
                qualityMenu.classList.remove('active');
            });
        });
    }
    bindQualityEvents(); // Bind initial auto button

    document.addEventListener('click', (e) => {
        if(!e.target.closest('#speedMenu') && !e.target.closest('#speedBtn')) {
            speedMenu.classList.remove('active');
        }
        if(!e.target.closest('#qualityMenu') && !e.target.closest('#qualityBtn')) {
            qualityMenu.classList.remove('active');
        }
    });

    // Picture in Picture
    pipBtn.addEventListener('click', async () => {
        if(playerType !== 'html5' || !video) return;
        try {
            if (document.pictureInPictureElement) {
                await document.exitPictureInPicture();
            } else if (document.pictureInPictureEnabled) {
                await video.requestPictureInPicture();
            }
        } catch (error) {
            console.error(error);
        }
    });

    // Fullscreen
    function toggleFullscreen() {
        if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
            const requestFS = wrapper.requestFullscreen || wrapper.webkitRequestFullscreen || wrapper.mozRequestFullScreen || wrapper.msRequestFullscreen;
            if (requestFS) {
                const promise = requestFS.call(wrapper);
                if (promise) promise.catch(err => console.error("Fullscreen Error:", err));
            }
            enterFs.classList.add('hidden');
            exitFs.classList.remove('hidden');
        } else {
            const exitFS = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
            if (exitFS) {
                exitFS.call(document);
            }
            enterFs.classList.remove('hidden');
            exitFs.classList.add('hidden');
        }
    }

    fullscreenBtn.addEventListener('click', toggleFullscreen);
    if(video) video.addEventListener('dblclick', toggleFullscreen);

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        const activeTag = document.activeElement.tagName.toLowerCase();
        if(activeTag === 'input' || activeTag === 'textarea') return;

        switch(e.key.toLowerCase()) {
            case ' ':
            case 'k':
                e.preventDefault();
                togglePlay();
                break;
            case 'f':
                e.preventDefault();
                toggleFullscreen();
                break;
            case 'm':
                e.preventDefault();
                if (playerType === 'html5' && video) {
                    video.muted = !video.muted;
                } else if (playerType === 'youtube' && ytPlayer) {
                    if (ytPlayer.isMuted()) ytPlayer.unMute(); else ytPlayer.mute();
                }
                updateVolumeUI();
                break;
            case 'arrowright':
                e.preventDefault();
                if(playerType==='html5' && video) video.currentTime += 5;
                else if(playerType==='youtube' && ytPlayer) ytPlayer.seekTo(ytPlayer.getCurrentTime() + 5, true);
                break;
            case 'arrowleft':
                e.preventDefault();
                if(playerType==='html5' && video) video.currentTime -= 5;
                else if(playerType==='youtube' && ytPlayer) ytPlayer.seekTo(ytPlayer.getCurrentTime() - 5, true);
                break;
            case 'arrowup':
                e.preventDefault();
                if (playerType === 'html5' && video) {
                    video.volume = Math.min(1, video.volume + 0.1);
                } else if (playerType === 'youtube' && ytPlayer) {
                    ytPlayer.setVolume(Math.min(100, ytPlayer.getVolume() + 10));
                }
                updateVolumeUI();
                break;
            case 'arrowdown':
                e.preventDefault();
                if (playerType === 'html5' && video) {
                    video.volume = Math.max(0, video.volume - 0.1);
                } else if (playerType === 'youtube' && ytPlayer) {
                    ytPlayer.setVolume(Math.max(0, ytPlayer.getVolume() - 10));
                }
                updateVolumeUI();
                break;
        }
    });

    // Auto-hide controls when playing and mouse is still
    wrapper.addEventListener('mousemove', () => {
        wrapper.classList.add('active');
        clearTimeout(hideControlsTimeout);
        hideControlsTimeout = setTimeout(() => {
            let isPlaying = false;
            if(playerType === 'html5' && video) isPlaying = !video.paused;
            else if(playerType === 'youtube') isPlaying = isYtPlaying;
            
            if (isPlaying) {
                wrapper.classList.remove('active');
                speedMenu.classList.remove('active');
            }
        }, 2500);
    });

    wrapper.addEventListener('mouseleave', () => {
        let isPlaying = false;
        if(playerType === 'html5' && video) isPlaying = !video.paused;
        else if(playerType === 'youtube') isPlaying = isYtPlaying;
        
        if (isPlaying) {
            wrapper.classList.remove('active');
            speedMenu.classList.remove('active');
        }
    });
});
</script>
@endpush
