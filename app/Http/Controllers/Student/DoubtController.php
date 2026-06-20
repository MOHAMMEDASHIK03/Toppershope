<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Doubt;
use App\Models\DoubtReply;
use App\Models\Enrollment;

class DoubtController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $doubts = Doubt::where('user_id', $userId)
            ->with('course')
            ->withCount('replies')
            ->latest()
            ->get();

        $courseOptions = $this->courseOptionsForStudent($userId);
        $hasEnrollments = collect($courseOptions)->contains(fn (array $option) => $option['enrolled']);

        return view('student.doubts.index', compact('doubts', 'courseOptions', 'hasEnrollments'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $course = Course::where('id', $request->course_id)
            ->where('is_published', true)
            ->first();

        if (!$course) {
            return back()
                ->withErrors(['course_id' => 'Please select a valid published course.'])
                ->withInput();
        }

        $enrollment = Enrollment::with('batch')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->whereHas('batch', fn ($query) => $query->where('course_id', $course->id))
            ->first();

        $allowedCourseIds = collect($this->courseOptionsForStudent($userId))->pluck('course_id');

        if (!$allowedCourseIds->contains($course->id)) {
            return back()
                ->withErrors(['course_id' => 'Please select a valid course.'])
                ->withInput();
        }

        Doubt::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'batch_id' => $enrollment?->batch_id,
            'subject' => $request->title,
            'description' => $request->body,
            'is_resolved' => false,
        ]);

        return back()->with('success', 'Doubt posted successfully! Faculty will respond shortly.');
    }

    /** @return array<int, array{course_id: int, label: string, batch_id: ?int, enrolled: bool}> */
    private function courseOptionsForStudent(int $userId): array
    {
        $enrollments = Enrollment::with('batch.course')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->get()
            ->filter(fn (Enrollment $enrollment) => $enrollment->batch?->course !== null);

        if ($enrollments->isNotEmpty()) {
            return $enrollments
                ->unique(fn (Enrollment $enrollment) => $enrollment->batch->course_id)
                ->map(fn (Enrollment $enrollment) => [
                    'course_id' => $enrollment->batch->course_id,
                    'label' => $enrollment->batch->course->name . ' — ' . $enrollment->batch->name,
                    'batch_id' => $enrollment->batch_id,
                    'enrolled' => true,
                ])
                ->values()
                ->all();
        }

        return Course::query()
            ->where('is_published', true)
            ->orderBy('name')
            ->get()
            ->map(fn (Course $course) => [
                'course_id' => $course->id,
                'label' => $course->name,
                'batch_id' => null,
                'enrolled' => false,
            ])
            ->values()
            ->all();
    }

    public function show($doubtId)
    {
        $doubt = Doubt::with(['replies.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($doubtId);

        return view('student.doubts.show', compact('doubt'));
    }

    public function reply(Request $request, $doubtId)
    {
        $request->validate(['reply_text' => 'required|string']);

        $doubt = Doubt::where('user_id', Auth::id())->findOrFail($doubtId);

        DoubtReply::create([
            'doubt_id' => $doubt->id,
            'user_id' => Auth::id(),
            'reply_text' => $request->reply_text,
        ]);

        return back()->with('success', 'Reply added.');
    }
}
