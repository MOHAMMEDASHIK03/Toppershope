<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\PerformanceReview;
use App\Models\HR\Employee;
use App\Models\HR\Kpi;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $reviews = $this->paginateListing(
            PerformanceReview::with(['employee', 'kpi', 'reviewer'])->latest(),
            $request
        );
            
        return view('hr.performance_reviews.index', compact('reviews'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->with(['department', 'designation'])
            ->orderBy('first_name')
            ->get();
        $kpis = Kpi::with(['department', 'designation'])->orderBy('title')->get();

        return view('hr.performance_reviews.create', compact('employees', 'kpis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'kpi_id' => 'required|exists:kpis,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_period' => 'required|string|max:255',
            'manager_feedback' => 'required|string',
        ]);

        PerformanceReview::create([
            'employee_id' => $request->employee_id,
            'kpi_id' => $request->kpi_id,
            'reviewer_id' => Auth::guard('hr')->id(),
            'rating' => $request->rating,
            'review_period' => $request->review_period,
            'review_date' => now()->toDateString(),
            'manager_feedback' => $request->manager_feedback,
            'status' => 'published',
        ]);

        return redirect()->route('hr.performance-reviews.index')->with('success', 'Performance review submitted successfully.');
    }

    public function edit(PerformanceReview $performanceReview)
    {
        $performanceReview->load(['employee.department', 'employee.designation', 'kpi', 'reviewer']);

        $employees = Employee::where('is_active', true)
            ->with(['department', 'designation'])
            ->orderBy('first_name')
            ->get();
        $kpis = Kpi::with(['department', 'designation'])->orderBy('title')->get();

        return view('hr.performance_reviews.edit', compact('performanceReview', 'employees', 'kpis'));
    }

    public function update(Request $request, PerformanceReview $performanceReview)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'manager_feedback' => 'required|string',
            'employee_feedback' => 'nullable|string',
            'status' => 'required|in:draft,published,acknowledged'
        ]);

        $performanceReview->update([
            'rating' => $request->rating,
            'manager_feedback' => $request->manager_feedback,
            'employee_feedback' => $request->employee_feedback,
            'status' => $request->status
        ]);

        return redirect()->route('hr.performance-reviews.index')->with('success', 'Performance review updated successfully.');
    }

    public function destroy(PerformanceReview $performanceReview)
    {
        $performanceReview->delete();
        return redirect()->route('hr.performance-reviews.index')->with('success', 'Performance review deleted successfully.');
    }
}
