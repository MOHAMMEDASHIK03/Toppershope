<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HR\JobPosting;
use App\Models\HR\JobApplication;

class CareersController extends Controller
{
    public function index()
    {
        // Fetch all open job postings with their department details
        $jobs = JobPosting::with('department')
            ->where('status', 'open')
            ->latest()
            ->get();

        return view('pages.careers', compact('jobs'));
    }

    public function apply(JobPosting $jobPosting)
    {
        if ($jobPosting->status !== 'open') {
            abort(404, 'This job posting is no longer active.');
        }

        return view('pages.careers_apply', compact('jobPosting'));
    }

    public function storeApplication(Request $request, JobPosting $jobPosting)
    {
        if ($jobPosting->status !== 'open') {
            abort(404, 'This job posting is no longer active.');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'notes' => 'nullable|string',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        JobApplication::create([
            'job_posting_id' => $jobPosting->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'resume_path' => $resumePath,
            'notes' => $data['notes'] ?? null,
            'status' => 'applied',
        ]);

        return redirect()->route('careers')->with('success', 'Your application has been submitted successfully! Our HR team will contact you shortly.');
    }
}
