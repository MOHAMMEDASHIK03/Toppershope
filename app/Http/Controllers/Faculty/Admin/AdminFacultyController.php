<?php

namespace App\Http\Controllers\Faculty\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AdminFacultyController extends Controller
{
    /**
     * Show all faculty users and their course assignments.
     */
    public function index()
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        // All faculty (non-head) users
        $facultyUsers = User::where('role', 'faculty')
            ->orderBy('name')
            ->with('courses')
            ->get();

        // All courses for the assignment dropdown
        $allCourses = Course::with('category')
            ->orderBy('name')
            ->get(['id', 'name', 'category_id', 'is_published']);

        return view('faculty.admin.faculties.index', compact('facultyUsers', 'allCourses'));
    }

    public function createAssign(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) {
            abort(403);
        }

        $facultyUsers = User::where('role', 'faculty')->orderBy('name')->get(['id', 'name', 'email']);
        $allCourses = Course::with('category')->orderBy('name')->get(['id', 'name', 'category_id']);

        return view('faculty.admin.faculties.assign', compact('facultyUsers', 'allCourses'));
    }

    /**
     * Assign a faculty member to a course.
     */
    public function assign(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $user   = User::findOrFail($request->user_id);
        $course = Course::findOrFail($request->course_id);

        // Ensure target is actually a faculty user
        if (!$user->isFaculty()) {
            return back()->with('error', 'The selected user is not a faculty member.');
        }

        // Sync attach (prevent duplicate)
        $user->courses()->syncWithoutDetaching([$course->id]);

        return redirect()
            ->route('faculty.head.faculties.index')
            ->with('success', $user->name . ' has been assigned to "' . $course->name . '".');
    }

    /**
     * Remove a faculty member from a course.
     */
    public function unassign(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->courses()->detach($request->course_id);

        return back()->with('success', 'Assignment removed successfully.');
    }
}
