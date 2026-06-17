<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Employee;
use App\Models\HR\Payroll;
use App\Models\HR\Leave;
use App\Models\HR\JobPosting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees    = Employee::where('is_active', true)->count();
        $pendingLeaves     = Leave::where('status', 'pending')->count();
        $openJobPostings   = JobPosting::where('status', 'open')->count();
        $thisMonthPayroll  = Payroll::where('month_year', Carbon::now()->format('m-Y'))->sum('net_payable');

        return view('hr.dashboard.index', compact(
            'totalEmployees', 'pendingLeaves', 'openJobPostings', 'thisMonthPayroll'
        ));
    }
}
