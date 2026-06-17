<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\JobApplication;

class JobApplicationController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $applications = $this->paginateListing(
            JobApplication::query()
                ->with('jobPosting')
                ->withCount('interviews')
                ->when($request->filled('job'), fn ($q) => $q->where('job_posting_id', $request->integer('job')))
                ->latest(),
            $request
        );

        return view('hr.job_applications.index', compact('applications'));
    }

    // Usually applications are created by a public-facing page, but we'll mock creating one via HR too.
    public function create()
    {
        return view('hr.job_applications.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'applicant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string',
        ]);

        $nameParts = preg_split('/\s+/', trim($data['applicant_name']), 2);

        JobApplication::create([
            'job_posting_id' => $data['job_posting_id'],
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'notes' => $data['cover_letter'] ?? null,
            'status' => 'applied',
        ]);

        return redirect()->route('hr.job-applications.index')->with('success', 'Application registered manually.');
    }

    public function edit(JobApplication $jobApplication)
    {
        $jobApplication->load(['jobPosting', 'interviews' => fn ($q) => $q->latest('scheduled_at')]);

        return view('hr.job_applications.edit', compact('jobApplication'));
    }

    public function update(Request $request, JobApplication $jobApplication)
    {
        $request->validate([
            'status' => 'required|in:applied,shortlisted,interviewed,offered,rejected,hired',
        ]);

        $jobApplication->update(['status' => $request->status]);

        return redirect()->route('hr.job-applications.index')->with('success', 'Application status updated.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();
        return redirect()->route('hr.job-applications.index')->with('success', 'Application deleted.');
    }
}
