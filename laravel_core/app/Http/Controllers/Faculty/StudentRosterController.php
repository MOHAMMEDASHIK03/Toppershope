<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Batch;

class StudentRosterController extends Controller
{
    private function checkAccess(Course $course)
    {
        // faculty_head has global access — regular faculty must be assigned to the course
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized to view this course.');
        }
    }

    public function index(Request $request, Course $course)
    {
        $this->checkAccess($course);

        // All batches that belong to this course
        $batches = $course->batches;
        
        // Fetch enrollments related to this course's batches
        $query = Enrollment::with(['user', 'batch'])
            ->whereHas('batch', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->where('status', 'active');

        // Apply Batch Filter if requested
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Get the enrollments to list students
        $enrollments = $query->latest()->get();

        return view('faculty.courses.students', compact('course', 'batches', 'enrollments'));
    }
}
