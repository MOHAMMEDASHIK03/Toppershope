<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Enrollment;
use App\Models\Batch;
use App\Models\Video;
use App\Models\Note;
use App\Models\Quiz;
use App\Models\Unit;

class MyCoursesController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['batch.course'])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('student.my-courses.index', compact('enrollments'));
    }

    public function show($enrollmentId)
    {
        $enrollment = Enrollment::with([
            'batch.course.subjects.chapters.units.videos',
            'batch.course.subjects.chapters.units.notes',
            'batch.course.subjects.chapters.units.quizzes'
        ])
        ->where('user_id', Auth::id())
        ->where('id', $enrollmentId)
        ->firstOrFail();

        $course = $enrollment->batch->course;

        return view('student.my-courses.show', compact('enrollment', 'course'));
    }

    public function watchVideo($enrollmentId, $videoId)
    {
        // Verify enrollment
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('id', $enrollmentId)
            ->firstOrFail();

        $video = Video::findOrFail($videoId);

        // Generate signed URL for the video
        $signedUrl = null;
        if ($video->video_path) {
            $signedUrl = URL::temporarySignedRoute(
                'media.video', now()->addHours(2), ['filename' => basename($video->video_path)]
            );
        }

        // Extract YouTube ID for the Iframe API
        $youtubeId = null;
        if ($video->video_url) {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video->video_url, $match)) {
                $youtubeId = $match[1];
            }
        }

        return view('student.player.video', compact('enrollment', 'video', 'signedUrl', 'youtubeId'));
    }

    public function viewNote($enrollmentId, $noteId)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('id', $enrollmentId)
            ->firstOrFail();

        $note = Note::findOrFail($noteId);

        $signedUrl = null;
        if ($note->file_path) {
            $signedUrl = URL::temporarySignedRoute(
                'media.pdf', now()->addHours(2), ['filename' => $note->file_path]
            );
        }

        return view('student.player.notes', compact('enrollment', 'note', 'signedUrl'));
    }
}
