<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Note;
use App\Models\Unit;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentManagerController extends Controller
{
    /**
     * Display the content manager interface.
     */
    public function index(Course $course)
    {
        // faculty_head bypasses — regular faculty must be assigned to this course
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized to manage this course.');
        }

        // Load the full curriculum tree with content
        $course->load(['subjects.chapters.units.videos', 'subjects.chapters.units.notes']);

        return view('faculty.courses.content', compact('course'));
    }

    /**
     * Upload a video to a unit.
     */
    public function storeVideo(Request $request, Course $course, Unit $unit)
    {
        // faculty_head bypasses — regular faculty must be assigned
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized');
        }

        // Validate that this unit actually belongs to this course
        if ($unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Unit does not belong to this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:300',
            'video_url' => 'nullable|url|max:255|required_without:video_file',
            'video_file' => 'nullable|file|mimes:mp4,mkv,avi,mov|max:512000|required_without:video_url', // 500MB max
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('course_videos', 'public');
        }

        $unit->videos()->create([
            'title' => $request->title,
            'description' => $request->description,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'duration_minutes' => $request->duration_minutes,
        ]);

        return redirect()->back()->with('success', 'Video added to topic successfully.');
    }

    /**
     * Delete a video.
     */
    public function destroyVideo(Course $course, Video $video)
    {
        // faculty_head bypasses — regular faculty must be assigned
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized');
        }

        if ($video->unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Video does not belong to this course.');
        }

        // Delete purely local file if exists
        if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->delete();

        return redirect()->back()->with('success', 'Video removed.');
    }

    /**
     * Upload a note (PDF) to a unit.
     */
    public function storeNote(Request $request, Course $course, Unit $unit)
    {
        // faculty_head bypasses — regular faculty must be assigned
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized');
        }

        // Validate course mapping
        if ($unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Unit does not belong to this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:300',
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max PDF
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('course_notes', 'public');
            
            $unit->notes()->create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Note document uploaded successfully.');
    }

    /**
     * Delete a note document.
     */
    public function destroyNote(Course $course, Note $note)
    {
        // faculty_head bypasses — regular faculty must be assigned
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized');
        }

        if ($note->unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Note does not belong to this course.');
        }

        // Delete the physical file First
        if ($note->file_path && Storage::disk('public')->exists($note->file_path)) {
            Storage::disk('public')->delete($note->file_path);
        }

        $note->delete();

        return redirect()->back()->with('success', 'Note document removed.');
    }
}
