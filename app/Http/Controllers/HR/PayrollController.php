<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Services\PayslipPdfService;
use App\Support\PayslipPresenter;
use Illuminate\Http\Request;
use App\Models\HR\Employee;
use App\Models\HR\Payroll;
use App\Models\HR\SalaryStructure;
use Carbon\Carbon;

class PayrollController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $monthYear = $request->get('month_year', Carbon::now()->format('m-Y'));

        $payrolls = $this->paginateListing(
            Payroll::with('employee')->where('month_year', $monthYear)->latest(),
            $request
        );

        return view('hr.payroll.index', compact('payrolls', 'monthYear'));
    }

    public function settings()
    {
        $employees = Employee::where('is_active', true)
            ->with(['department', 'designation'])
            ->orderBy('first_name')
            ->get();

        $structures = SalaryStructure::with('employee')->get()->keyBy('employee_id');

        return view('hr.payroll.settings', compact('employees', 'structures'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'employee_id'      => 'required|exists:employees,id',
            'basic_salary'     => 'required|numeric|min:0',
            'hra'              => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'tax_deductions'   => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);

        $basic    = $request->basic_salary;
        $hra      = $request->hra ?? 0;
        $other    = $request->other_allowances ?? 0;
        $taxDed   = $request->tax_deductions ?? 0;
        $otherDed = $request->other_deductions ?? 0;
        $net      = ($basic + $hra + $other) - ($taxDed + $otherDed);

        SalaryStructure::updateOrCreate(
            ['employee_id' => $request->employee_id],
            [
                'basic_salary'     => $basic,
                'hra'              => $hra,
                'other_allowances' => $other,
                'tax_deductions'   => $taxDed,
                'other_deductions' => $otherDed,
                'net_salary'       => $net,
            ]
        );

        return back()->with('success', 'Salary structure saved.')->with('last_employee_id', $request->employee_id);
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->whereHas('salaryStructure')
            ->with(['department', 'designation'])
            ->orderBy('first_name')
            ->get();

        $defaultMonthYear = Carbon::now()->subMonth()->format('m-Y');

        return view('hr.payroll.create', compact('employees', 'defaultMonthYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month_year'  => 'required|regex:/^\d{2}-\d{4}$/',
        ]);

        $empId     = $request->employee_id;
        $monthYear = $request->month_year;

        // Prevent duplicate
        if (Payroll::where('employee_id', $empId)->where('month_year', $monthYear)->exists()) {
            return back()->with('error', 'Payroll already generated for this employee for the selected period.');
        }

        $structure = SalaryStructure::where('employee_id', $empId)->first();
        if (!$structure) {
            return back()->with('error', 'Salary structure not defined for this employee. Configure it in Payroll Settings first.');
        }

        $allowances  = $structure->hra + $structure->other_allowances;
        $deductions  = $structure->tax_deductions + $structure->other_deductions;
        $netPayable  = $structure->basic_salary + $allowances - $deductions;

        Payroll::create([
            'employee_id'   => $empId,
            'generated_by'  => auth('hr')->id(),
            'month_year'    => $monthYear,
            'basic_salary'  => $structure->basic_salary,
            'allowances'    => $allowances,
            'deductions'    => $deductions,
            'net_payable'   => $netPayable,
            'status'        => 'pending',
        ]);

        return redirect()->route('hr.payroll.index', ['month_year' => $monthYear])
            ->with('success', 'Payslip generated for ' . $monthYear);
    }

    public function show($id)
    {
        $payslip = PayslipPresenter::forPayrollId((int) $id);

        return view('hr.payroll.show', compact('payslip'));
    }

    public function pdf($payroll, PayslipPdfService $payslipPdf)
    {
        $payslip = PayslipPresenter::forPayrollId((int) $payroll);

        return $payslipPdf->download($payslip);
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate(['status' => 'required|in:pending,paid']);

        $payroll->update([
            'status'       => $request->status,
            'payment_date' => $request->status === 'paid' ? now() : null,
            'transaction_id' => $request->transaction_id,
        ]);

        return back()->with('success', 'Payroll status updated.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Payroll record deleted.');
    }
}
