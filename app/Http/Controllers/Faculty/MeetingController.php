<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Mail\MeetingInviteMail;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\GoogleToken;
use App\Models\Meeting;
use App\Models\MeetingInvitee;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MeetingController extends Controller
{
    public function __construct(private GoogleCalendarService $calendarService) {}

    /**
     * List all meetings for the current faculty.
     */
    public function index()
    {
        $faculty  = Auth::user();
        $token    = GoogleToken::where('user_id', $faculty->id)->first();

        $meetings = Meeting::where('faculty_id', $faculty->id)
            ->with(['batch', 'course', 'invitees'])
            ->orderBy('start_at', 'desc')
            ->get();

        $upcoming = $meetings->filter(fn($m) => $m->status === 'scheduled' && $m->start_at->isFuture());
        $past     = $meetings->filter(fn($m) => $m->status !== 'scheduled' || $m->start_at->isPast());

        // Faculty head can pick any course; regular faculty only their assigned ones
        if ($faculty->isFacultyHead()) {
            $courses = Course::orderBy('name')->get(['id', 'name']);
        } else {
            $courses = $faculty->courses()->orderBy('name')->get(['courses.id', 'name']);
        }

        return view('faculty.meetings.index', compact('token', 'upcoming', 'past', 'courses'));
    }

    /**
     * Get batches for a course (AJAX dropdown refresh).
     */
    public function batchesForCourse(Course $course)
    {
        return response()->json($course->batches()->orderBy('name')->get(['id', 'name']));
    }

    /**
     * Get students in a batch for one-on-one search (AJAX).
     */
    public function studentsForBatch(Batch $batch)
    {
        $students = Enrollment::where('batch_id', $batch->id)
            ->where('status', 'active')
            ->with('user:id,name,email')
            ->get()
            ->map(fn($e) => ['id' => $e->user_id, 'name' => $e->user->name, 'email' => $e->user->email]);

        return response()->json($students);
    }

    /**
     * Schedule a new meeting.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_at'    => 'required|date|after:now',
            'duration'    => 'required|integer|min:15|max:480',
            'type'        => 'required|in:batch,one_on_one',
            'batch_id'    => 'required_if:type,batch|nullable|exists:batches,id',
            'student_ids' => 'required_if:type,one_on_one|nullable|array',
            'student_ids.*' => 'exists:users,id',
            'course_id'   => 'nullable|exists:courses,id',
        ]);

        $faculty  = Auth::user();
        $startAt  = \Carbon\Carbon::parse($request->start_at);
        $endAt    = $startAt->copy()->addMinutes((int) $request->duration);

        // ---- Collect attendees ----
        $attendees = [];

        if ($request->type === 'batch') {
            $batch      = Batch::findOrFail($request->batch_id);
            $enrollments = Enrollment::where('batch_id', $batch->id)
                ->where('status', 'active')
                ->with('user:id,name,email')
                ->get();

            foreach ($enrollments as $e) {
                if ($e->user?->email) {
                    $attendees[] = ['email' => $e->user->email, 'name' => $e->user->name, 'user_id' => $e->user_id];
                }
            }
        } else {
            // one_on_one: one or more specific students
            foreach ((array) $request->student_ids as $sid) {
                $student = User::find($sid);
                if ($student?->email) {
                    $attendees[] = ['email' => $student->email, 'name' => $student->name, 'user_id' => $student->id];
                }
            }
        }

        if (empty($attendees)) {
            return back()->with('error', 'No students found to invite. Make sure the batch has active enrollments.')->withInput();
        }

        // ---- Create Google Calendar event ----
        $googleResult = $this->calendarService->createMeeting($faculty, [
            'title'       => $request->title,
            'description' => $request->description ?? '',
            'start_at'    => $startAt,
            'end_at'      => $endAt,
        ], $attendees);

        if (!$googleResult) {
            return back()
                ->with('error', 'Failed to create Google Meet. Make sure your Google account is linked.')
                ->withInput();
        }

        // ---- Save meeting record ----
        $meeting = Meeting::create([
            'faculty_id'      => $faculty->id,
            'course_id'       => $request->course_id ?? ($request->type === 'batch' ? Batch::find($request->batch_id)?->course_id : null),
            'batch_id'        => $request->type === 'batch' ? $request->batch_id : null,
            'title'           => $request->title,
            'description'     => $request->description,
            'start_at'        => $startAt,
            'end_at'          => $endAt,
            'google_event_id' => $googleResult['google_event_id'],
            'meet_link'       => $googleResult['meet_link'],
            'type'            => $request->type,
            'status'          => 'scheduled',
        ]);

        // ---- Save invitees + send email invites ----
        foreach ($attendees as $a) {
            MeetingInvitee::create([
                'meeting_id' => $meeting->id,
                'user_id'    => $a['user_id'] ?? null,
                'email'      => $a['email'],
                'name'       => $a['name'] ?? null,
                'status'     => 'invited',
            ]);

            // Send custom email invite (in addition to Google Calendar invite)
            try {
                Mail::to($a['email'])->send(new MeetingInviteMail($meeting, $a['name'] ?? 'Student'));
            } catch (\Exception $e) {
                \Log::warning('Meeting invite email failed for ' . $a['email'] . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('faculty.meetings.index')
            ->with('success', 'Meeting scheduled! ' . count($attendees) . ' invitations sent.');
    }

    /**
     * Cancel (delete) a meeting.
     */
    public function destroy(Meeting $meeting)
    {
        $faculty = Auth::user();

        // Only the creator or faculty_head can cancel
        if ($meeting->faculty_id !== $faculty->id && !$faculty->isFacultyHead()) {
            abort(403);
        }

        // Cancel on Google Calendar
        if ($meeting->google_event_id) {
            $this->calendarService->cancelMeeting(
                User::find($meeting->faculty_id),
                $meeting->google_event_id
            );
        }

        $meeting->update(['status' => 'cancelled']);

        return redirect()->route('faculty.meetings.index')
            ->with('success', 'Meeting "' . $meeting->title . '" has been cancelled.');
    }
}
