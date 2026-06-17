<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'interview_date' => 'required|date',
            'interview_time' => 'nullable|date_format:H:i',
            'meeting_link' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        Interview::create([
            'job_application_id' => $request->job_application_id,
            'interviewer_id' => Auth::guard('hr')->id(),
            'scheduled_at' => Interview::composeScheduledAt(
                $request->interview_date,
                $request->filled('interview_time') ? $request->interview_time : null
            ),
            'location_or_link' => $request->meeting_link,
            'status' => 'scheduled',
            'feedback' => $request->notes,
        ]);

        return back()->with('success', 'Interview scheduled successfully.');
    }

    public function update(Request $request, Interview $interview)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'feedback' => 'nullable|string',
        ]);

        $interview->update([
            'status' => $request->status,
            'feedback' => $request->feedback ?? $interview->feedback,
        ]);

        return back()->with('success', 'Interview status updated.');
    }
}
