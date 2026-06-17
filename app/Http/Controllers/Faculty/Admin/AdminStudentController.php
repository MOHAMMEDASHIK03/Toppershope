<?php

namespace App\Http\Controllers\Faculty\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $query = Enrollment::with(['user', 'batch.course'])
            ->whereHas('user')
            ->orderBy('enrollments.created_at', 'desc');

        // Filter: by course (via batch)
        if ($request->filled('course_id')) {
            $query->whereHas('batch', fn($q) =>
                $q->where('course_id', $request->course_id)
            );
        }

        // Filter: by batch
        if ($request->filled('batch_id')) {
            $query->where('enrollments.batch_id', $request->batch_id);
        }

        // Filter: by enrollment status
        if ($request->filled('status')) {
            $query->where('enrollments.status', $request->status);
        }

        // Search: by student name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
            );
        }

        $enrollments = $this->paginateListing($query, $request);

        // For the filter dropdowns
        $courses = Course::orderBy('name')->select('id', 'name')->get();
        $batches = $request->filled('course_id')
            ? Batch::where('course_id', $request->course_id)->orderBy('name')->get()
            : Batch::orderBy('name')->select('id', 'name', 'course_id')->get();

        // Summary stats — use DB to avoid ambiguity
        $totalStudents = Enrollment::distinct('enrollments.user_id')->count('enrollments.user_id');
        $activeCount   = Enrollment::where('enrollments.status', 'active')->count();
        $totalBatches  = Batch::count();
        $totalRevenue  = \DB::table('enrollments')
            ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
            ->where('enrollments.status', 'active')
            ->sum('batches.price');

        return view('faculty.admin.students.index', compact(
            'enrollments', 'courses', 'batches',
            'totalStudents', 'activeCount', 'totalBatches', 'totalRevenue'
        ));
    }
}
