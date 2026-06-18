<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} — Exam | Topper's Hope</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0a0e1a; color: #e2e8f0; user-select: none; -webkit-user-select: none; overflow: hidden; height: 100vh; }

        /* Exam Layout */
        .exam-header { height: 56px; background: #0f1629; border-bottom: 1px solid rgba(99,102,241,0.1); display: flex; align-items: center; padding: 0 20px; gap: 16px; }
        .exam-body { display: flex; height: calc(100vh - 56px); }
        .exam-main { flex: 1; overflow-y: auto; padding: 24px 32px; }
        .exam-sidebar { width: 280px; background: #0f1629; border-left: 1px solid rgba(99,102,241,0.1); display: flex; flex-direction: column; overflow-y: auto; }

        /* Section Tabs */
        .section-tabs { display: flex; gap: 4px; padding: 12px; overflow-x: auto; border-bottom: 1px solid rgba(99,102,241,0.06); }
        .section-tab { padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s; white-space: nowrap; border: 1px solid transparent; }
        .section-tab.active { background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.15)); color: #a5b4fc; border-color: rgba(99,102,241,0.3); }
        .section-tab:not(.active) { background: #1e293b; color: #64748b; }
        .section-tab:not(.active):hover { color: #94a3b8; background: #263047; }

        /* Timer */
        .timer-box { padding: 6px 14px; border-radius: 10px; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 800; font-variant-numeric: tabular-nums; }
        .timer-total { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .timer-section { background: rgba(99,102,241,0.1); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.2); }
        .timer-warning { animation: pulse 1s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

        /* Question */
        .question-box { max-width: 800px; }
        .question-number { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; font-size: 12px; font-weight: 900; flex-shrink: 0; }
        .question-text { font-size: 15px; line-height: 1.7; color: #f1f5f9; font-weight: 500; }
        .question-image { max-width: 500px; margin-top: 16px; border-radius: 12px; border: 1px solid rgba(99,102,241,0.1); }

        /* Options */
        .option-item { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 12px; border: 1px solid #1e293b; cursor: pointer; transition: all 0.2s; margin-bottom: 8px; }
        .option-item:hover { background: rgba(99,102,241,0.04); border-color: rgba(99,102,241,0.2); }
        .option-item.selected { background: rgba(99,102,241,0.08); border-color: rgba(99,102,241,0.4); }
        .option-radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid #475569; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; transition: all 0.2s; }
        .option-item.selected .option-radio { border-color: #6366f1; background: #6366f1; }
        .option-radio-dot { width: 8px; height: 8px; border-radius: 50%; background: white; transform: scale(0); transition: transform 0.15s; }
        .option-item.selected .option-radio-dot { transform: scale(1); }
        .option-text { font-size: 14px; color: #cbd5e1; line-height: 1.6; }
        .option-image { max-width: 300px; margin-top: 8px; border-radius: 8px; border: 1px solid #1e293b; }

        /* Question Navigator */
        .nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; padding: 12px; }
        .nav-btn { width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.15s; border: none; }
        .nav-btn.unanswered { background: #1e293b; color: #64748b; }
        .nav-btn.answered { background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.25); }
        .nav-btn.flagged { background: rgba(168,85,247,0.15); color: #a855f7; border: 1px solid rgba(168,85,247,0.25); }
        .nav-btn.current { outline: 2px solid #6366f1; outline-offset: 1px; }
        .nav-btn.answered.flagged { background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(168,85,247,0.15)); color: #a855f7; border: 1px solid rgba(168,85,247,0.3); }

        /* Bottom Bar */
        .bottom-bar { position: fixed; bottom: 0; left: 0; right: 280px; height: 56px; background: #0f1629; border-top: 1px solid rgba(99,102,241,0.1); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; z-index: 20; }
        .btn { padding: 8px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-ghost { background: transparent; color: #94a3b8; border: 1px solid #334155; }
        .btn-ghost:hover { background: #1e293b; color: white; }
        .btn-primary { background: linear-gradient(135deg, #6B21C8, #7723D6); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, #4338ca, #6d28d9); }
        .btn-danger { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-success { background: linear-gradient(135deg, #059669, #0d9488); color: white; }
        .btn-success:hover { background: linear-gradient(135deg, #047857, #0f766e); }
        .btn-warning { background: rgba(168,85,247,0.15); color: #a855f7; border: 1px solid rgba(168,85,247,0.2); }
        .btn-warning:hover { background: rgba(168,85,247,0.25); }

        /* Legend */
        .legend-item { display: flex; align-items: center; gap: 8px; font-size: 10px; color: #64748b; font-weight: 600; }
        .legend-dot { width: 14px; height: 14px; border-radius: 4px; flex-shrink: 0; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

        /* Violation overlay */
        .violation-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 1000; align-items: center; justify-content: center; }
        .violation-overlay.active { display: flex; }

        /* Submit Confirmation Modal */
        .confirm-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 900; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .confirm-modal.active { display: flex; }
        .confirm-modal-box { background: #1e293b; border: 1px solid rgba(99,102,241,0.2); border-radius: 20px; padding: 32px; max-width: 420px; width: 90%; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
    </style>
</head>
<body>

{{-- Quiz Data --}}
@php
    $allQuestions = [];
    $sectionMeta = [];
    foreach ($quiz->sections as $sIdx => $section) {
        $sectionMeta[] = [
            'id' => $section->id,
            'name' => $section->name,
            'time' => ($section->time_limit_minutes ?: 30) * 60,
            'marks' => $section->marks_per_question,
            'negative' => $section->negative_marks_per_question,
            'startIdx' => count($allQuestions),
            'count' => $section->questions->count(),
        ];
        foreach ($section->questions as $q) {
            $allQuestions[] = [
                'id' => $q->id,
                'text' => $q->question_text,
                'image' => $q->question_image_path ? asset('storage/' . $q->question_image_path) : null,
                'sectionIdx' => $sIdx,
                'options' => $q->options->map(fn($o) => [
                    'id' => $o->id,
                    'text' => $o->option_text,
                    'image' => $o->option_image_path ? asset('storage/' . $o->option_image_path) : null,
                ])->toArray(),
            ];
        }
    }
    $totalTime = collect($sectionMeta)->sum('time');
@endphp

{{-- Violation Overlay --}}
<div class="violation-overlay" id="violationOverlay">
    <div style="text-align: center;">
        <div style="font-size: 64px; margin-bottom: 20px;">⚠️</div>
        <h1 style="font-size: 24px; font-weight: 900; color: #f87171; margin-bottom: 8px;">Exam Terminated</h1>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px;">You left the exam window. Your answers have been auto-submitted.</p>
        <p style="color: #64748b; font-size: 12px;" id="violationMsg"></p>
    </div>
</div>

{{-- ✅ Fullscreen Splash — Triggers true fullscreen on user click --}}
<div id="fullscreenSplash" style="position:fixed;inset:0;background:#0a0e1a;z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:24px;">
    <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Topper's Hope" style="width:64px;height:64px;border-radius:16px;object-fit:cover;margin-bottom:8px;">
    <div style="text-align:center;">
        <div style="font-size:22px;font-weight:900;color:white;margin-bottom:6px;">{{ $quiz->title }}</div>
        <div style="font-size:13px;color:#64748b;">Click the button below to enter fullscreen and begin your exam</div>
    </div>
    <div style="display:flex;gap:12px;align-items:center;padding:12px 20px;border-radius:14px;background:rgba(119,35,214,0.08);border:1px solid rgba(119,35,214,0.2);">
        <span style="font-size:12px;color:#D6BCFA;">⏱ Total Time:</span>
        <span style="font-size:14px;font-weight:800;color:#f87171;">{{ $quiz->duration_minutes }} Min</span>
    </div>
    <button onclick="startExamFullscreen()" style="padding:16px 48px;border-radius:14px;background:#7723D6;color:white;font-size:15px;font-weight:800;border:none;cursor:pointer;letter-spacing:0.5px;box-shadow:0 8px 25px rgba(119,35,214,0.4);transition:all 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
        ⛶ &nbsp;Enter Fullscreen &amp; Begin Exam
    </button>
    <div style="font-size:11px;color:#334155;text-align:center;max-width:400px;line-height:1.6;">
        Exiting fullscreen or switching tabs will <span style="color:#f87171;font-weight:700;">automatically submit your exam.</span>
    </div>
</div>

{{-- Submit Confirmation Modal --}}
<div class="confirm-modal" id="confirmModal">
    <div class="confirm-modal-box">
        <div style="font-size:48px;margin-bottom:16px;">📝</div>
        <h2 style="font-size:18px;font-weight:900;color:white;margin-bottom:8px;">Submit Exam?</h2>
        <p style="font-size:13px;color:#94a3b8;margin-bottom:6px;" id="confirmText"></p>
        <p style="font-size:12px;color:#f87171;margin-bottom:24px;" id="confirmUnanswered"></p>
        <div style="display:flex;gap:12px;justify-content:center;">
            <button class="btn btn-ghost" onclick="closeConfirmModal()" style="padding:10px 24px;">Go Back</button>
            <button class="btn btn-success" onclick="doSubmit()" style="padding:10px 24px;">Yes, Submit</button>
        </div>
    </div>
</div>

{{-- Header --}}
<div class="exam-header">
    <div style="display:flex;align-items:center;gap:10px;">
        <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="TH" style="width:32px;height:32px;border-radius:8px;object-fit:cover;">
        <div>
            <div style="font-size:13px;font-weight:800;color:white;">{{ $quiz->title }}</div>
            <div style="font-size:10px;color:#64748b;" id="currentSectionLabel">Section: {{ $quiz->sections->first()->name ?? 'General' }}</div>
        </div>
    </div>
    <div style="flex:1;"></div>
    <div class="timer-box timer-section" id="sectionTimer">
        <span style="font-size:10px;opacity:0.7;">SECTION</span> <span id="sectionTimerDisplay">00:00</span>
    </div>
    <div class="timer-box timer-total" id="totalTimer">
        <span style="font-size:10px;opacity:0.7;">TOTAL</span> <span id="totalTimerDisplay">00:00</span>
    </div>
    <button class="btn btn-success" onclick="confirmSubmit()" style="margin-left:8px;">Submit Exam</button>
</div>

{{-- Body --}}
<div class="exam-body">
    {{-- Main Question Area --}}
    <div class="exam-main" style="padding-bottom: 80px;">
        {{-- Section Tabs --}}
        <div style="display:flex;gap:4px;margin-bottom:24px;flex-wrap:wrap;" id="sectionTabsContainer">
            @foreach($quiz->sections as $sIdx => $section)
                <button class="section-tab {{ $sIdx === 0 ? 'active' : '' }}" onclick="switchSection({{ $sIdx }})">{{ $section->name }}</button>
            @endforeach
        </div>

        {{-- Question Display --}}
        <div class="question-box">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <span class="question-number" style="background:rgba(99,102,241,0.1);color:#818cf8;" id="qNumBadge">1</span>
                <span style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;" id="qMeta">Question 1 of 0</span>
                <span id="qMarks" style="font-size:10px;color:#22c55e;font-weight:700;margin-left:auto;">+4 / -1</span>
            </div>
            <div class="question-text" id="qText"></div>
            <img id="qImage" class="question-image" style="display:none;" alt="Question Image">

            <div style="margin-top:24px;" id="optionsContainer"></div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="exam-sidebar">
        {{-- Profile --}}
        <div style="padding:16px;border-bottom:1px solid rgba(99,102,241,0.06);display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#6B21C8,#7723D6);display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:12px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div style="font-size:12px;font-weight:700;color:white;">{{ auth()->user()->name }}</div>
                <div style="font-size:10px;color:#64748b;">{{ auth()->user()->email }}</div>
            </div>
        </div>

        {{-- Legend --}}
        <div style="padding:12px 16px;border-bottom:1px solid rgba(99,102,241,0.06);display:flex;flex-wrap:wrap;gap:10px;">
            <div class="legend-item"><div class="legend-dot" style="background:rgba(34,197,94,0.2);border:1px solid rgba(34,197,94,0.3);"></div> Answered <span id="countAnswered" style="color:#22c55e;">0</span></div>
            <div class="legend-item"><div class="legend-dot" style="background:#1e293b;border:1px solid #334155;"></div> Not Answered <span id="countUnanswered" style="color:#94a3b8;">0</span></div>
            <div class="legend-item"><div class="legend-dot" style="background:rgba(168,85,247,0.2);border:1px solid rgba(168,85,247,0.3);"></div> Flagged <span id="countFlagged" style="color:#a855f7;">0</span></div>
        </div>

        {{-- Section Label --}}
        <div style="padding:10px 16px;font-size:11px;font-weight:800;color:#a5b4fc;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid rgba(99,102,241,0.06);" id="navSectionLabel">Questions</div>

        {{-- Navigator --}}
        <div class="nav-grid" id="questionNav"></div>
    </div>
</div>

{{-- Bottom Bar --}}
<div class="bottom-bar">
    <div style="display:flex;gap:8px;">
        <button class="btn btn-warning" onclick="toggleFlag()">⚑ Mark for Review</button>
        <button class="btn btn-danger" onclick="clearResponse()">✕ Clear Response</button>
    </div>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-ghost" onclick="prevQuestion()">← Prev</button>
        <button class="btn btn-primary" onclick="saveAndNext()">Save & Next →</button>
    </div>
</div>

{{-- Hidden Form --}}
<form id="quizForm" action="{{ route('student.quiz.submit', $quiz->id) }}" method="POST" style="display:none;">
    @csrf
    <div id="hiddenAnswers"></div>
</form>

<script>
// === DATA ===
const questions = @json($allQuestions);
const sections = @json($sectionMeta);
const totalTimeInit = {{ $totalTime }};
const submitUrl = "{{ route('student.quiz.submit', $quiz->id) }}";
const csrfToken = "{{ csrf_token() }}";

// === STATE ===
let currentQ = 0;
let currentSection = 0;
let answers = {};          // questionId -> optionId
let flagged = new Set();   // set of global question indices
let totalTimeLeft = totalTimeInit;
let sectionTimers = sections.map(s => s.time);
let examEnded = false;
let violationCount = 0;

// === RENDER QUESTION ===
function renderQuestion() {
    const q = questions[currentQ];
    const sec = sections[q.sectionIdx];
    document.getElementById('qNumBadge').textContent = currentQ + 1;
    document.getElementById('qMeta').textContent = `Question ${currentQ - sec.startIdx + 1} of ${sec.count}`;
    document.getElementById('qMarks').textContent = `+${sec.marks} / -${sec.negative}`;
    document.getElementById('currentSectionLabel').textContent = 'Section: ' + sec.name;
    document.getElementById('qText').textContent = q.text;

    const img = document.getElementById('qImage');
    if (q.image) { img.src = q.image; img.style.display = 'block'; }
    else { img.style.display = 'none'; }

    // Options
    const container = document.getElementById('optionsContainer');
    container.innerHTML = '';
    q.options.forEach((opt, idx) => {
        const selected = answers[q.id] === opt.id;
        const label = String.fromCharCode(65 + idx);
        const div = document.createElement('div');
        div.className = 'option-item' + (selected ? ' selected' : '');
        div.onclick = () => selectOption(q.id, opt.id);
        div.innerHTML = `
            <div class="option-radio"><div class="option-radio-dot"></div></div>
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:12px;font-weight:800;color:#6366f1;">${label}.</span>
                    ${opt.text ? `<span class="option-text">${opt.text}</span>` : ''}
                </div>
                ${opt.image ? `<img src="${opt.image}" class="option-image" alt="Option ${label}">` : ''}
            </div>
        `;
        container.appendChild(div);
    });

    // Update nav
    updateNavigator();
    updateSection(q.sectionIdx);
    updateCounts();
}

// === SELECT OPTION ===
function selectOption(qId, optId) {
    answers[qId] = optId;
    renderQuestion();
}

// === CLEAR RESPONSE ===
function clearResponse() {
    const q = questions[currentQ];
    delete answers[q.id];
    renderQuestion();
}

// === NAV ===
function saveAndNext() {
    if (currentQ < questions.length - 1) { currentQ++; renderQuestion(); }
}
function prevQuestion() {
    if (currentQ > 0) { currentQ--; renderQuestion(); }
}
function goToQuestion(idx) {
    currentQ = idx;
    renderQuestion();
}

// === FLAG ===
function toggleFlag() {
    if (flagged.has(currentQ)) flagged.delete(currentQ);
    else flagged.add(currentQ);
    renderQuestion();
}

// === SWITCH SECTION ===
function switchSection(sIdx) {
    currentSection = sIdx;
    currentQ = sections[sIdx].startIdx;
    renderQuestion();
}
function updateSection(sIdx) {
    currentSection = sIdx;
    document.querySelectorAll('.section-tab').forEach((t, i) => t.classList.toggle('active', i === sIdx));
    document.getElementById('navSectionLabel').textContent = sections[sIdx].name;
}

// === NAVIGATOR ===
function updateNavigator() {
    const nav = document.getElementById('questionNav');
    const sec = sections[currentSection];
    nav.innerHTML = '';
    for (let i = sec.startIdx; i < sec.startIdx + sec.count; i++) {
        const q = questions[i];
        const btn = document.createElement('button');
        btn.className = 'nav-btn';
        btn.textContent = i - sec.startIdx + 1;
        if (answers[q.id]) btn.classList.add('answered');
        else btn.classList.add('unanswered');
        if (flagged.has(i)) btn.classList.add('flagged');
        if (i === currentQ) btn.classList.add('current');
        btn.onclick = () => goToQuestion(i);
        nav.appendChild(btn);
    }
}

// === COUNTS ===
function updateCounts() {
    const sec = sections[currentSection];
    let answered = 0, unanswered = 0, flaggedCount = 0;
    for (let i = sec.startIdx; i < sec.startIdx + sec.count; i++) {
        if (answers[questions[i].id]) answered++;
        else unanswered++;
        if (flagged.has(i)) flaggedCount++;
    }
    document.getElementById('countAnswered').textContent = answered;
    document.getElementById('countUnanswered').textContent = unanswered;
    document.getElementById('countFlagged').textContent = flaggedCount;
}

// === TIMERS ===
function formatTime(s) {
    const h = Math.floor(s / 3600);
    const m = Math.floor((s % 3600) / 60);
    const sec = s % 60;
    if (h > 0) return `${h}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
    return `${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
}

function tickTimers() {
    if (examEnded) return;

    totalTimeLeft--;
    sectionTimers[currentSection]--;

    document.getElementById('totalTimerDisplay').textContent = formatTime(totalTimeLeft);
    document.getElementById('sectionTimerDisplay').textContent = formatTime(sectionTimers[currentSection]);

    // Warning animation
    if (totalTimeLeft <= 300) document.getElementById('totalTimer').classList.add('timer-warning');
    if (sectionTimers[currentSection] <= 120) document.getElementById('sectionTimer').classList.add('timer-warning');
    else document.getElementById('sectionTimer').classList.remove('timer-warning');

    // Auto-submit on total time expiry
    if (totalTimeLeft <= 0) { autoSubmit('Time is up!'); return; }

    // Auto-move to next section if section time expires
    if (sectionTimers[currentSection] <= 0 && currentSection < sections.length - 1) {
        switchSection(currentSection + 1);
    }

    setTimeout(tickTimers, 1000);
}

// === FULLSCREEN ===
function enterFullscreen() {
    const el = document.documentElement;
    if (el.requestFullscreen) el.requestFullscreen();
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    else if (el.msRequestFullscreen) el.msRequestFullscreen();
}

document.addEventListener('fullscreenchange', function() {
    if (!document.fullscreenElement && !examEnded) {
        autoSubmit('You exited fullscreen mode.');
    }
});

// === TAB SWITCH DETECTION ===
document.addEventListener('visibilitychange', function() {
    if (document.hidden && !examEnded) {
        autoSubmit('You switched away from the exam tab.');
    }
});

// === SUBMIT ===
function confirmSubmit() {
    const totalQ = questions.length;
    const answeredQ = Object.keys(answers).length;
    const unansweredQ = totalQ - answeredQ;
    document.getElementById('confirmText').textContent = `You have answered ${answeredQ} out of ${totalQ} questions.`;
    document.getElementById('confirmUnanswered').textContent = unansweredQ > 0 ? `⚠ ${unansweredQ} questions are still unanswered!` : 'All questions answered ✓';
    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('active');
}

function autoSubmit(reason) {
    if (examEnded) return;
    examEnded = true;
    document.getElementById('violationMsg').textContent = reason;
    document.getElementById('violationOverlay').classList.add('active');
    doSubmit();
    setTimeout(() => { window.close(); }, 3000);
}

function doSubmit() {
    examEnded = true;
    const form = document.getElementById('quizForm');
    const container = document.getElementById('hiddenAnswers');
    container.innerHTML = '';
    for (const [qId, optId] of Object.entries(answers)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `answers[${qId}]`;
        input.value = optId;
        container.appendChild(input);
    }
    form.submit();
}

// === INIT ===
function startExamFullscreen() {
    const splash = document.getElementById('fullscreenSplash');
    // Request fullscreen triggered by user click — browsers allow this
    const el = document.documentElement;
    const req = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen || el.msRequestFullscreen;
    if (req) {
        const p = req.call(el);
        if (p) p.catch(() => {}); // Ignore if user denies fullscreen
    }
    splash.style.display = 'none';
    renderQuestion();
    document.getElementById('totalTimerDisplay').textContent = formatTime(totalTimeLeft);
    document.getElementById('sectionTimerDisplay').textContent = formatTime(sectionTimers[0]);
    setTimeout(tickTimers, 1000);
}

window.addEventListener('load', function() {
    // Don't auto-start — wait for fullscreen splash click
    // (Browsers block auto-fullscreen on page load)
    document.getElementById('totalTimerDisplay').textContent = formatTime(totalTimeLeft);
    document.getElementById('sectionTimerDisplay').textContent = formatTime(sectionTimers[0]);
});
</script>
</body>
</html>
