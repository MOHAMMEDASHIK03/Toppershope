<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizResultsController extends Controller
{
    private function checkAccess(Course $course)
    {
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Course $course)
    {
        $this->checkAccess($course);

        // Fetch all quiz attempts for quizzes belonging to this course
        // Eager load the Quiz and Student to prevent N+1 queries
        $attempts = QuizAttempt::with(['quiz', 'student'])
            ->whereHas('quiz', function ($query) use ($course) {
                // A quiz belongs to a unit, which belongs to a chapter, which belongs to a subject, which belongs to a course
                $query->whereHas('unit.chapter.subject', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('faculty.courses.results', compact('course', 'attempts'));
    }

    public function updateRemarks(Request $request, Course $course, QuizAttempt $attempt)
    {
        $this->checkAccess($course);

        // Ensure the attempt actually belongs to a quiz in this course
        if ($attempt->quiz->unit->chapter->subject->course_id !== $course->id) {
            abort(403);
        }

        $request->validate([
            'faculty_remarks' => 'required|string',
        ]);

        $attempt->update([
            'faculty_remarks' => $request->faculty_remarks,
        ]);

        return redirect()->back()->with('success', 'Remarks updated successfully.');
    }
}
