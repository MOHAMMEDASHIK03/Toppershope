<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Unit;

class CourseManagerController extends Controller
{
    /**
     * Ensure the user has access to this course (faculty_head has global access, regular faculty must be assigned)
     */
    private function getAuthorizedCourse($courseId)
    {
        $user = Auth::user();
        if ($user->isFacultyHead()) {
            return Course::findOrFail($courseId);
        }

        return $user->courses()->findOrFail($courseId);
    }

    /**
     * Display the Curriculum structure (Subjects -> Chapters -> Units)
     */
    public function curriculum($courseId)
    {
        $course = $this->getAuthorizedCourse($courseId);
        
        // Eager load the entire curriculum
        $course->load(['subjects.chapters.units.videos', 'subjects.chapters.units.notes', 'subjects.chapters.units.quizzes']);

        return view('faculty.courses.curriculum', compact('course'));
    }

    /**
     * Define a new Subject
     */
    public function storeSubject(Request $request, $courseId)
    {
        $course = $this->getAuthorizedCourse($courseId);
        $request->validate(['name' => 'required|string|max:255']);

        $course->subjects()->create([
            'name' => $request->name,
            'order' => $course->subjects()->count() + 1
        ]);

        return back()->with('success', 'Subject added successfully.');
    }

    /**
     * Define a new Chapter
     */
    public function storeChapter(Request $request, $courseId, $subjectId)
    {
        $this->getAuthorizedCourse($courseId); // Authorization check
        $subject = Subject::where('course_id', $courseId)->findOrFail($subjectId);

        $request->validate(['name' => 'required|string|max:255']);

        $subject->chapters()->create([
            'name' => $request->name,
            'order' => $subject->chapters()->count() + 1
        ]);

        return back()->with('success', 'Chapter added successfully.');
    }

    /**
     * Define a new Unit (Topic)
     */
    public function storeUnit(Request $request, $courseId, $subjectId, $chapterId)
    {
        $this->getAuthorizedCourse($courseId); // Authorization check
        $chapter = Chapter::whereHas('subject', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->findOrFail($chapterId);

        $request->validate(['name' => 'required|string|max:255']);

        $chapter->units()->create([
            'name' => $request->name,
            'order' => $chapter->units()->count() + 1
        ]);

        return back()->with('success', 'Unit added successfully.');
    }

    /**
     * Delete logic handles cascading safely based on DB foreign keys
     */
    public function destroySubject($courseId, $subjectId)
    {
        $this->getAuthorizedCourse($courseId);
        Subject::where('course_id', $courseId)->findOrFail($subjectId)->delete();
        return back()->with('success', 'Subject and all its contents deleted.');
    }

    public function destroyChapter($courseId, $subjectId, $chapterId)
    {
        $this->getAuthorizedCourse($courseId);
        $chapter = Chapter::whereHas('subject', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->findOrFail($chapterId)->delete();
        return back()->with('success', 'Chapter and all its contents deleted.');
    }

    public function destroyUnit($courseId, $subjectId, $chapterId, $unitId)
    {
        $this->getAuthorizedCourse($courseId);
        $unit = Unit::whereHas('chapter.subject', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->findOrFail($unitId)->delete();
        return back()->with('success', 'Unit and all its contents deleted.');
    }
}
