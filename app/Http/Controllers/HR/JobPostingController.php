<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\JobPosting;

class JobPostingController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $postings = $this->paginateListing(
            JobPosting::with('department')->withCount('applications')->latest(),
            $request
        );

        return view('hr.job_postings.index', compact('postings'));
    }

    public function create()
    {
        return view('hr.job_postings.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedPosting($request);

        JobPosting::create($data);

        return redirect()->route('hr.job-postings.index')->with('success', 'Job posting published successfully.');
    }

    public function edit(JobPosting $jobPosting)
    {
        return view('hr.job_postings.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        $data = $this->validatedPosting($request);

        $jobPosting->update($data);

        return redirect()->route('hr.job-postings.index')->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $jobPosting)
    {
        $jobPosting->delete();

        return redirect()->route('hr.job-postings.index')->with('success', 'Job posting deleted successfully.');
    }

    private function validatedPosting(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,draft',
        ]);
    }
}
