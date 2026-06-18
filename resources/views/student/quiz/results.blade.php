@extends('student.layouts.app')
@section('title', 'Results — ' . $quiz->title)
@section('page_title', 'Quiz Results')

@section('content')
@php
    $totalQuestions = 0;
    $totalCorrect = 0;
    $totalWrong = 0;
    $totalSkipped = 0;
    $totalMarksObtained = 0;
    $totalMaxMarks = 0;
    $sectionResults = [];

    foreach ($quiz->sections as $section) {
        $sCorrect = 0; $sWrong = 0; $sSkipped = 0; $sMarks = 0;
        $maxSectionMarks = $section->questions->count() * $section->marks_per_question;

        foreach ($section->questions as $question) {
            $totalQuestions++;
            $userAnswer = $attempt->answers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);

            if (!$userAnswer) {
                $sSkipped++;
                $totalSkipped++;
            } elseif ($correctOption && $userAnswer == $correctOption->id) {
                $sCorrect++;
                $totalCorrect++;
                $sMarks += $section->marks_per_question;
                $totalMarksObtained += $section->marks_per_question;
            } else {
                $sWrong++;
                $totalWrong++;
                $sMarks -= $section->negative_marks_per_question;
                $totalMarksObtained -= $section->negative_marks_per_question;
            }
        }

        $totalMaxMarks += $maxSectionMarks;
        $sectionResults[] = [
            'name' => $section->name,
            'correct' => $sCorrect,
            'wrong' => $sWrong,
            'skipped' => $sSkipped,
            'marks' => $sMarks,
            'max' => $maxSectionMarks,
            'section' => $section,
        ];
    }

    $percentage = $totalMaxMarks > 0 ? round(max(0, $totalMarksObtained) / $totalMaxMarks * 100, 1) : 0;
@endphp

<div class="max-w-5xl mx-auto">
    {{-- Score Header --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 mb-6">
        <div class="flex flex-col lg:flex-row items-center gap-8">
            {{-- Pie Chart --}}
            <div style="width:200px;height:200px;flex-shrink:0;position:relative;">
                <canvas id="pieChart" width="200" height="200"></canvas>
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;">
                    <span style="font-size:28px;font-weight:900;color:white;">{{ $percentage }}%</span>
                    <span style="font-size:10px;color:#64748b;font-weight:600;">SCORE</span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex-1 w-full">
                <h1 class="text-2xl font-bold text-slate-900 mb-1">{{ $quiz->title }}</h1>
                <p class="text-sm text-slate-400 mb-6">Submitted {{ $attempt->created_at->format('d M Y, h:i A') }}</p>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-white">{{ max(0, $totalMarksObtained) }}<span class="text-xs text-slate-500">/{{ $totalMaxMarks }}</span></p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase">Marks</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-emerald-400">{{ $totalCorrect }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase">Correct</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-rose-400">{{ $totalWrong }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase">Wrong</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-amber-400">{{ $totalSkipped }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase">Skipped</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-primary-700">{{ $totalQuestions }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase">Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section-wise Breakdown --}}
    @if(count($sectionResults) > 1)
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-6">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Section-wise Breakdown</h3>
            <div class="overflow-x-auto">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:8px 12px;font-size:11px;color:#64748b;font-weight:700;border-bottom:1px solid #1e293b;">Section</th>
                            <th style="text-align:center;padding:8px 12px;font-size:11px;color:#22c55e;font-weight:700;border-bottom:1px solid #1e293b;">Correct</th>
                            <th style="text-align:center;padding:8px 12px;font-size:11px;color:#f87171;font-weight:700;border-bottom:1px solid #1e293b;">Wrong</th>
                            <th style="text-align:center;padding:8px 12px;font-size:11px;color:#f59e0b;font-weight:700;border-bottom:1px solid #1e293b;">Skipped</th>
                            <th style="text-align:center;padding:8px 12px;font-size:11px;color:#a5b4fc;font-weight:700;border-bottom:1px solid #1e293b;">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sectionResults as $sr)
                            <tr>
                                <td style="padding:10px 12px;font-size:13px;color:white;font-weight:600;border-bottom:1px solid #0f1629;">{{ $sr['name'] }}</td>
                                <td style="text-align:center;padding:10px 12px;font-size:13px;color:#22c55e;font-weight:700;border-bottom:1px solid #0f1629;">{{ $sr['correct'] }}</td>
                                <td style="text-align:center;padding:10px 12px;font-size:13px;color:#f87171;font-weight:700;border-bottom:1px solid #0f1629;">{{ $sr['wrong'] }}</td>
                                <td style="text-align:center;padding:10px 12px;font-size:13px;color:#f59e0b;font-weight:700;border-bottom:1px solid #0f1629;">{{ $sr['skipped'] }}</td>
                                <td style="text-align:center;padding:10px 12px;font-size:13px;color:#a5b4fc;font-weight:700;border-bottom:1px solid #0f1629;">{{ max(0, $sr['marks']) }}/{{ $sr['max'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Question Review --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-6">Question-wise Review</h3>

        @php $globalQ = 0; @endphp
        @foreach($quiz->sections as $section)
            @if($quiz->sections->count() > 1)
                <div class="mb-4 mt-6 first:mt-0">
                    <h4 class="text-xs font-bold text-primary-700 uppercase tracking-wide border-b border-primary-500/10 pb-2">{{ $section->name }}</h4>
                </div>
            @endif

            @foreach($section->questions as $question)
                @php
                    $globalQ++;
                    $userAnswer = $attempt->answers[$question->id] ?? null;
                    $correctOption = $question->options->firstWhere('is_correct', true);
                    $isCorrect = $correctOption && $userAnswer == $correctOption->id;
                    $isSkipped = !$userAnswer;
                @endphp

                <div class="mb-5 p-4 rounded-xl border {{ $isSkipped ? 'border-slate-200 bg-slate-100/20' : ($isCorrect ? 'border-emerald-200 bg-primary-500/5' : 'border-primary-500/20 bg-primary-500/5') }}">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-black
                            {{ $isSkipped ? 'bg-slate-100 text-slate-400' : ($isCorrect ? 'bg-primary-500/20 text-emerald-400' : 'bg-primary-500/20 text-rose-400') }}">
                            {{ $globalQ }}
                        </span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md
                            {{ $isSkipped ? 'bg-amber-500/10 text-amber-400' : ($isCorrect ? 'bg-primary-50 text-emerald-700' : 'bg-primary-500/10 text-rose-400') }}">
                            {{ $isSkipped ? 'Skipped' : ($isCorrect ? '+ ' . $section->marks_per_question : '− ' . $section->negative_marks_per_question) }}
                        </span>
                    </div>

                    <p class="text-sm text-slate-800 mb-2">{{ $question->question_text }}</p>
                    @if($question->question_image_path)
                        <img src="{{ asset('storage/' . $question->question_image_path) }}" class="max-w-xs rounded-lg border border-slate-700 mb-3" alt="Q{{ $globalQ }}">
                    @endif

                    <div class="space-y-2 mt-3">
                        @foreach($question->options as $oIdx => $option)
                            @php
                                $isUserChoice = $userAnswer == $option->id;
                                $isCorrectOpt = $option->is_correct;
                                $optLabel = chr(65 + $oIdx);
                            @endphp
                            <div class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                                @if($isCorrectOpt) bg-primary-500/10 border border-emerald-200
                                @elseif($isUserChoice && !$isCorrectOpt) bg-primary-500/10 border border-primary-500/20
                                @else bg-slate-100/30 border border-transparent
                                @endif">
                                <span class="text-xs font-bold {{ $isCorrectOpt ? 'text-emerald-400' : ($isUserChoice ? 'text-rose-400' : 'text-slate-500') }}">
                                    @if($isCorrectOpt) ✓
                                    @elseif($isUserChoice) ✕
                                    @else {{ $optLabel }}.
                                    @endif
                                </span>
                                <div>
                                    @if($option->option_text)
                                        <span class="{{ $isCorrectOpt ? 'text-emerald-300' : ($isUserChoice && !$isCorrectOpt ? 'text-rose-300' : 'text-slate-400') }}">
                                            {{ $option->option_text }}
                                        </span>
                                    @endif
                                    @if($option->option_image_path)
                                        <img src="{{ asset('storage/' . $option->option_image_path) }}" class="max-w-[200px] rounded mt-1 border border-slate-700" alt="Opt">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('student.quizzes') }}" class="px-6 py-3 rounded-xl btn-primary text-white text-sm font-bold transition-all inline-block">← Back to Test Series</a>
    </div>
</div>

<script>
// Pie Chart
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('pieChart');
    const ctx = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    canvas.width = 200 * dpr;
    canvas.height = 200 * dpr;
    ctx.scale(dpr, dpr);

    const data = [
        { value: {{ $totalCorrect }}, color: '#22c55e' },
        { value: {{ $totalWrong }}, color: '#ef4444' },
        { value: {{ $totalSkipped }}, color: '#f59e0b' },
    ];

    const total = data.reduce((s, d) => s + d.value, 0) || 1;
    const cx = 100, cy = 100, outerR = 90, innerR = 62;
    let startAngle = -Math.PI / 2;

    data.forEach(d => {
        if (d.value === 0) return;
        const slice = (d.value / total) * 2 * Math.PI;
        ctx.beginPath();
        ctx.arc(cx, cy, outerR, startAngle, startAngle + slice);
        ctx.arc(cx, cy, innerR, startAngle + slice, startAngle, true);
        ctx.closePath();
        ctx.fillStyle = d.color;
        ctx.fill();
        startAngle += slice;
    });
});
</script>
@endsection
