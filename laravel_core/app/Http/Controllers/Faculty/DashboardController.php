<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        if (\Illuminate\Support\Facades\Auth::user()->isFacultyHead()) {
            // Compute Quick Stats for Admin
            $totalCourses = Course::count();
            $totalBatches = Batch::count();
            $upcomingBatches = Batch::where('is_upcoming', true)->count();
            
            // Total Enrollments
            $totalEnrollments = class_exists(Enrollment::class) ? Enrollment::where('status', 'active')->count() : 0;
            
            // Recent Batches
            $recentBatches = Batch::with('course')->latest()->take(5)->get();

            return view('faculty.dashboard', compact(
                'totalCourses',
                'totalBatches',
                'upcomingBatches',
                'totalEnrollments',
                'recentBatches'
            ));
        }

        // Regular Faculty Logic (Assigned Courses)
        $courses = \Illuminate\Support\Facades\Auth::user()->courses()->withCount('batches')->get();
        return view('faculty.assigned_courses', compact('courses'));
    }

    /**
     * Dedicated "My Teaching" page — same assigned courses view,
     * accessible to both faculty AND faculty_head.
     */
    public function myCourses()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        // faculty_head can manage ALL courses — regular faculty only sees assigned ones
        if ($user->isFacultyHead()) {
            $courses = Course::withCount('batches')->orderBy('name')->get();
        } else {
            $courses = $user->courses()->withCount('batches')->get();
        }

        return view('faculty.assigned_courses', compact('courses'));
    }
}
