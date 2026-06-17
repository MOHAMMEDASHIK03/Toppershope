<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Doubt;
use Illuminate\Http\Request;

class DoubtManagerController extends Controller
{
    /**
     * Check if the authenticated faculty is assigned to this course.
     */
    private function checkAccess(Course $course)
    {
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Course $course)
    {
        $this->checkAccess($course);

        // Fetch doubts with their associated students
        $doubts = Doubt::with('student')
            ->where('course_id', $course->id)
            ->orderBy('is_resolved', 'asc') // Unresolved first
            ->orderBy('created_at', 'desc')
            ->get();

        return view('faculty.courses.doubts', compact('course', 'doubts'));
    }

    public function reply(Request $request, Course $course, Doubt $doubt)
    {
        $this->checkAccess($course);
        if ($doubt->course_id !== $course->id) abort(403);

        $request->validate([
            'faculty_reply' => 'required|string',
        ]);

        $doubt->update([
            'faculty_reply' => $request->faculty_reply,
            'is_resolved' => true,
        ]);

        return redirect()->back()->with('success', 'Reply sent and doubt resolved!');
    }
}
