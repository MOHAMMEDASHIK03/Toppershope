<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\Batch;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get highest priority role (Super Admin > Admin > Faculty > Doubt > Admission > Parent > Student)
        $roleSlugs = DB::table('user_role')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('user_role.user_id', $user->id)
            ->pluck('roles.slug')
            ->toArray();

        if (in_array('admin', $roleSlugs) || in_array('super_admin', $roleSlugs)) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array('faculty', $roleSlugs) || in_array('faculty_head', $roleSlugs)) {
            return redirect()->route('faculty.dashboard');
        }

        if (in_array('admission', $roleSlugs) || in_array('admission_head', $roleSlugs)) {
            return redirect()->route('admission.dashboard');
        }

        if (in_array('hr', $roleSlugs)) {
            return redirect()->route('hr.dashboard');
        }

        if (in_array('ads', $roleSlugs) || in_array('ads_head', $roleSlugs)) {
            return redirect()->route('ads.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    public function showCourse($uuid)
    {
        $batch = Batch::with('course')->where('uuid', $uuid)->firstOrFail();
        
        // Verify user is actually enrolled
        $isEnrolled = Enrollment::where('user_id', Auth::id())
                                ->where('batch_id', $batch->id)
                                ->exists();

        // If not enrolled and not admin/faculty, deny access
        if (!$isEnrolled) {
            return redirect()->route('dashboard')->with('error', 'You are not enrolled in this course.');
        }

        // Generate Secure Temporary Signed URLs valid for 2 hours
        $videoUrl = URL::temporarySignedRoute(
            'media.video', now()->addHours(2), ['filename' => 'demo.mp4']
        );

        $pdfUrl = URL::temporarySignedRoute(
            'media.pdf', now()->addHours(2), ['filename' => 'notes.pdf']
        );

        // Fetch Quizzes for this Batch
        $quizzes = Quiz::where('batch_id', $batch->id)
                                   ->where('is_active', true)
                                   ->get();

        return view('panels.student.course.show', compact('batch', 'enrollment', 'videoUrl', 'pdfUrl', 'quizzes'));
    }
}
