<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\HR\Payroll;
use App\Models\Admin\ManualExpense;
use Carbon\Carbon;
use App\Traits\LogsAdminActivity;

class DashboardController extends Controller
{
    use LogsAdminActivity;

    public function index()
    {
        // Global Ecosystem Metrics
        $totalEmployees = \App\Models\HR\Employee::where('is_active', true)->count();
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        $totalAdCampaigns = \App\Models\Ads\AdCampaign::where('is_active', true)->count();
        $activeCourses = \App\Models\Course::where('is_published', true)->count();
        // Calculate Total Income (Successful Razorpay Payments)
        $totalIncome = Payment::where('status', 'success')->sum('amount');
        $incomeThisMonth = Payment::where('status', 'success')
                                  ->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->sum('amount');
        $incomeLastMonth = Payment::where('status', 'success')
                                  ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                  ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                  ->sum('amount');
        
        // Calculate Total Payroll Expenses (Paid Salaries + Manual Expenses)
        // payrolls table uses: net_payable, month_year (string e.g. "03-2026")
        $thisMonthYear = Carbon::now()->format('m-Y');
        $lastMonthYear = Carbon::now()->subMonth()->format('m-Y');

        $totalPayrollOnly   = Payroll::sum('net_payable');
        $totalManualExpenses = ManualExpense::sum('amount');
        $totalPayroll = $totalPayrollOnly + $totalManualExpenses;

        $payrollThisMonth = Payroll::where('month_year', $thisMonthYear)->sum('net_payable')
                            + ManualExpense::whereMonth('expense_date', Carbon::now()->month)
                                          ->whereYear('expense_date', Carbon::now()->year)->sum('amount');

        $payrollLastMonth = Payroll::where('month_year', $lastMonthYear)->sum('net_payable')
                            + ManualExpense::whereMonth('expense_date', Carbon::now()->subMonth()->month)
                                          ->whereYear('expense_date', Carbon::now()->subMonth()->year)->sum('amount');

        // Net Margin P&L
        $netMargin = $totalIncome - $totalPayroll;

        // Income Growth %
        $incomeGrowth   = $incomeLastMonth   > 0 ? (($incomeThisMonth   - $incomeLastMonth)   / $incomeLastMonth)   * 100 : ($incomeThisMonth   > 0 ? 100 : 0);
        
        // Expense Growth %
        $expenseGrowth  = $payrollLastMonth  > 0 ? (($payrollThisMonth  - $payrollLastMonth)  / $payrollLastMonth)  * 100 : ($payrollThisMonth  > 0 ? 100 : 0);

        // Fetch recent transactions
        $recentPayments = Payment::with('user', 'batch.course')
                                 ->where('status', 'success')
                                 ->latest()->take(5)->get()
                                 ->map(function($item) {
                                     return (object)[
                                         'type'   => 'income',
                                         'source' => 'Course Payment: ' . ($item->batch->course->name ?? 'Unknown'),
                                         'user'   => $item->user->name ?? 'Student',
                                         'amount' => $item->amount,
                                         'date'   => $item->created_at,
                                         'id'     => $item->id
                                     ];
                                 });

        $recentPayrolls = Payroll::with('employee')
                                 ->latest()->take(5)->get()
                                 ->map(function($item) {
                                     return (object)[
                                         'type'   => 'expense',
                                         'source' => 'Salary: ' . $item->month_year,
                                         'user'   => $item->employee->first_name ?? 'Employee',
                                         'amount' => $item->net_payable,
                                         'date'   => $item->created_at,
                                         'id'     => 'p_' . $item->id
                                     ];
                                 });

        $recentManualExpenses = ManualExpense::latest()
                                 ->take(5)
                                 ->get()
                                 ->map(function($item) {
                                     return (object)[
                                         'type' => 'expense',
                                         'source' => 'Manual: ' . $item->title,
                                         'user' => 'Admin Logged',
                                         'amount' => $item->amount,
                                         'date' => $item->expense_date,
                                         'id' => 'm_'.$item->id
                                     ];
                                 });

        // Merge and sort
        $recentTransactions = collect([...$recentPayments, ...$recentPayrolls, ...$recentManualExpenses])->sortByDesc('date')->take(8);

        return view('admin.dashboard.index', compact(
            'totalIncome', 'incomeGrowth', 
            'totalPayroll', 'expenseGrowth',
            'netMargin', 'recentTransactions',
            'totalEmployees', 'totalStudents', 'totalAdCampaigns', 'activeCourses'
        ));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        ManualExpense::create($request->only(['title', 'description', 'amount', 'expense_date']));

        $this->logAudit('logged_expense', "Added manual expense '{$request->title}' for ₹{$request->amount}");

        return redirect()->route('admin.dashboard')->with('success', 'Manual expense logged successfully.');
    }
    public function impersonate($panel, Request $request)
    {
        $this->logAudit('impersonation', "Super Admin impersonated the {$panel} panel");

        if ($panel === 'hr') {
            $user = \App\Models\HR\HrUser::where('is_active', true)
                ->where('role', 'hr_manager')
                ->first()
                ?? \App\Models\HR\HrUser::where('is_active', true)->first();
            if ($user) {
                auth()->guard('hr')->login($user);

                return redirect('/hr/dashboard');
            }
        } elseif ($panel === 'ads') {
            $user = \App\Models\Ads\AdsUser::first();
            if($user) { auth()->guard('ads')->login($user); return redirect('/ads/dashboard'); }
        } elseif ($panel === 'admission') {
            $user = \App\Models\Admission\AdmissionUser::first();
            if($user) { auth()->guard('admission')->login($user); return redirect('/admission/dashboard'); }
        } elseif ($panel === 'faculty') {
            $user = \App\Models\User::where('role', 'faculty_head')->first() ?? \App\Models\User::where('role', 'faculty')->first();
            if($user) { auth()->guard('web')->login($user); return redirect('/faculty/dashboard'); }
        }

        return back()->with('error', "No active master user found for the {$panel} panel to impersonate.");
    }
}
