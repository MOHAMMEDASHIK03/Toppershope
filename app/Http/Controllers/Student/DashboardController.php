<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Batch;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get enrolled batches with course info
        $enrollments = Enrollment::with(['batch.course'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        // Recent quiz attempts
        $recentQuizzes = QuizAttempt::with('quiz')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Stats
        $totalCourses = $enrollments->count();
        $totalQuizzes = QuizAttempt::where('user_id', $user->id)->count();
        $avgScore = QuizAttempt::where('user_id', $user->id)->avg('score') ?? 0;

        return view('student.dashboard', compact(
            'user', 'enrollments', 'recentQuizzes',
            'totalCourses', 'totalQuizzes', 'avgScore'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'target_exam' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'target_exam' => $request->filled('target_exam')
                ? strtolower($request->string('target_exam')->toString())
                : null,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
