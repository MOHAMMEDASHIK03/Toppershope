<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Unit;

class QuizController extends Controller
{
    /**
     * List all available quizzes from enrolled courses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filters
        $search = $request->query('search');
        $courseId = $request->query('course_id');
        $batchId = $request->query('batch_id');
        $subjectId = $request->query('subject_id');
        $chapterId = $request->query('chapter_id');
        $unitId = $request->query('unit_id');

        // Get all enrolled batch IDs & Course IDs
        $enrolledBatchIds = Enrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('batch_id');

        $enrolledCourseIds = Batch::whereIn('id', $enrolledBatchIds)->pluck('course_id');

        // Build Quiz Query
        $query = Quiz::where('is_published', true)
            ->with(['unit.chapter.subject.course', 'sections.questions']);

        // First, restrict to enrolled courses
        $query->whereHas('unit.chapter.subject.course', function ($q) use ($enrolledCourseIds) {
            $q->whereIn('id', $enrolledCourseIds);
        });

        // Search by Title
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Apply filters
        if ($batchId) {
            // Re-resolve course_id from batch if batch is selected
            $batch = Batch::find($batchId);
            if ($batch) {
                $courseId = $batch->course_id;
            }
        }

        if ($courseId || $subjectId || $chapterId || $unitId) {
            $query->whereHas('unit', function ($qUnit) use ($courseId, $subjectId, $chapterId, $unitId) {
                if ($unitId) {
                    $qUnit->where('id', $unitId);
                }
                
                if ($courseId || $subjectId || $chapterId) {
                    $qUnit->whereHas('chapter', function ($qChapter) use ($courseId, $subjectId, $chapterId) {
                        if ($chapterId) {
                            $qChapter->where('id', $chapterId);
                        }

                        if ($courseId || $subjectId) {
                            $qChapter->whereHas('subject', function ($qSubject) use ($courseId, $subjectId) {
                                if ($subjectId) {
                                    $qSubject->where('id', $subjectId);
                                }

                                if ($courseId) {
                                    $qSubject->where('course_id', $courseId);
                                }
                            });
                        }
                    });
                }
            });
        }

        $quizzes = $query->orderByDesc('created_at')->get();

        // Get attempt counts per quiz
        $attemptCounts = QuizAttempt::where('user_id', $user->id)
            ->selectRaw('quiz_id, count(*) as total, max(score) as best_score')
            ->groupBy('quiz_id')
            ->get()
            ->keyBy('quiz_id');

        // --- Dependent Dropdown Data ---
        $filterCourses = Course::whereIn('id', $enrolledCourseIds)->where('is_published', true)->select('id', 'name')->get();
        
        $filterBatches = collect();
        if ($courseId) {
            $filterBatches = Batch::where('course_id', $courseId)->where('status', '!=', 'closed')->select('id', 'name')->get();
        }

        $filterSubjects = collect();
        if ($courseId) {
            $filterSubjects = Subject::where('course_id', $courseId)->select('id', 'name')->get();
        }

        $filterChapters = collect();
        if ($subjectId) {
            $filterChapters = Chapter::where('subject_id', $subjectId)->select('id', 'name')->get();
        }

        $filterUnits = collect();
        if ($chapterId) {
            $filterUnits = Unit::where('chapter_id', $chapterId)->select('id', 'name')->get();
        }

        return view('student.quiz.index', compact(
            'quizzes', 'attemptCounts',
            'search', 'courseId', 'batchId', 'subjectId', 'chapterId', 'unitId',
            'filterCourses', 'filterBatches', 'filterSubjects', 'filterChapters', 'filterUnits'
        ));
    }

    /**
     * Show quiz instructions page.
     */
    public function show($quizId)
    {
        $quiz = Quiz::with(['sections.questions.options'])
            ->where('is_published', true)
            ->findOrFail($quizId);

        // Get all past attempts for this quiz
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.quiz.show', compact('quiz', 'attempts'));
    }

    /**
     * Actual exam page (opens in new tab, fullscreen).
     */
    public function attempt($quizId)
    {
        $quiz = Quiz::with(['sections.questions.options'])
            ->where('is_published', true)
            ->findOrFail($quizId);

        return view('student.quiz.attempt', compact('quiz'));
    }

    /**
     * Submit quiz answers — supports multiple attempts.
     */
    public function submit(Request $request, $quizId)
    {
        $quiz = Quiz::with(['sections.questions.options'])->findOrFail($quizId);

        $answers = $request->input('answers', []);

        // Create attempt (multiple allowed)
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'score' => 0,
        ]);

        $totalScore = 0;

        foreach ($quiz->sections as $section) {
            foreach ($section->questions as $question) {
                $selectedOptionId = $answers[$question->id] ?? null;
                $isCorrect = false;

                if ($selectedOptionId) {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && $correctOption->id == $selectedOptionId) {
                        $isCorrect = true;
                        $totalScore += $section->marks_per_question;
                    } else {
                        $totalScore -= $section->negative_marks_per_question;
                    }
                }

                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        $attempt->update(['score' => $totalScore]);

        return redirect()->route('student.quiz.results', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Show detailed quiz results for a specific attempt.
     */
    public function results($quizId, Request $request)
    {
        $quiz = Quiz::with(['sections.questions.options'])->findOrFail($quizId);

        // If attempt ID is specified, show that attempt; otherwise show the latest
        $attemptQuery = QuizAttempt::with(['answers'])
            ->where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id());

        if ($request->has('attempt')) {
            $attempt = $attemptQuery->where('id', $request->input('attempt'))->firstOrFail();
        } else {
            $attempt = $attemptQuery->latest()->firstOrFail();
        }

        // Build a lookup: question_id => option_id
        $attempt->answers = $attempt->answers->pluck('option_id', 'question_id')->toArray();

        return view('student.quiz.results', compact('quiz', 'attempt'));
    }
}
