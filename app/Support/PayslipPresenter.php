<?php

namespace App\Support;

use App\Models\HR\Payroll;
use App\Models\HR\SalaryStructure;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PayslipPresenter
{
    public function __construct(
        public Payroll $payroll,
        public array $company,
        public string $periodLabel,
        public string $periodShort,
        public string $payslipNumber,
        public string $generatedAt,
        public string $statusLabel,
        public string $statusClass,
        public array $employee,
        public array $earnings,
        public array $deductions,
        public float $grossEarnings,
        public float $totalDeductions,
        public float $netPayable,
        public string $amountInWords,
        public array $payment,
    ) {}

    public static function forPayrollId(int $id): self
    {
        $payroll = Payroll::with([
            'employee.department',
            'employee.designation.department',
            'employee.branch',
            'employee.payrollDetails',
            'employee.identity',
        ])->findOrFail($id);

        return self::fromPayroll($payroll);
    }

    public static function fromPayroll(Payroll $payroll): self
    {
        $employee = $payroll->employee;
        $structure = SalaryStructure::where('employee_id', $employee->id)->first();
        $payrollDetails = $employee->payrollDetails;
        $identity = $employee->identity;

        [$month, $year] = array_pad(explode('-', $payroll->month_year, 2), 2, null);
        $periodDate = ($month && $year)
            ? Carbon::createFromDate((int) $year, (int) $month, 1)
            : now();
        $periodLabel = $periodDate->format('F Y');
        $periodShort = $periodDate->format('M Y');

        $basic = (float) $payroll->basic_salary;
        $hra = (float) ($structure->hra ?? 0);
        $otherAllowances = (float) ($structure->other_allowances ?? 0);
        $allowancesTotal = (float) $payroll->allowances;

        if ($hra <= 0 && $otherAllowances <= 0 && $allowancesTotal > 0) {
            $otherAllowances = $allowancesTotal;
        }

        $tax = (float) ($structure->tax_deductions ?? 0);
        $otherDeductions = (float) ($structure->other_deductions ?? 0);
        $deductionsTotal = (float) $payroll->deductions;

        if ($tax <= 0 && $otherDeductions <= 0 && $deductionsTotal > 0) {
            $tax = $deductionsTotal;
        }

        $earnings = self::compactLines([
            ['label' => 'Basic Salary', 'amount' => $basic],
            ['label' => 'HRA', 'amount' => $hra],
            ['label' => 'Special Allowance', 'amount' => 0],
            ['label' => 'Bonus', 'amount' => 0],
            ['label' => 'Incentives', 'amount' => 0],
            ['label' => 'Other Allowances', 'amount' => $otherAllowances],
        ], alwaysKeepFirst: true);

        $deductions = self::compactLines([
            ['label' => 'Provident Fund (PF)', 'amount' => 0],
            ['label' => 'ESI', 'amount' => 0],
            ['label' => 'Professional Tax', 'amount' => 0],
            ['label' => 'TDS / Tax', 'amount' => $tax],
            ['label' => 'Loan Recovery', 'amount' => 0],
            ['label' => 'Other Deductions', 'amount' => $otherDeductions],
        ], alwaysKeepFirst: false);

        if ($deductions === []) {
            $deductions = [['label' => 'No deductions', 'amount' => 0.0]];
        }

        $gross = (float) ($payroll->basic_salary + $payroll->allowances);
        $totalDed = (float) $payroll->deductions;
        $net = (float) $payroll->net_payable;

        $account = $payrollDetails?->bank_account_number;
        $maskedAccount = $account
            ? 'XXXX' . substr(preg_replace('/\D/', '', $account), -4)
            : null;

        $paymentMethod = $payrollDetails?->payment_method
            ? Str::headline(str_replace('_', ' ', $payrollDetails->payment_method))
            : 'Bank Transfer';

        return new self(
            payroll: $payroll,
            company: config('payslip'),
            periodLabel: $periodLabel,
            periodShort: $periodShort,
            payslipNumber: sprintf('PSL-%05d-%s', $payroll->id, str_replace('-', '', $payroll->month_year)),
            generatedAt: $payroll->created_at->format('d M Y, h:i A'),
            statusLabel: $payroll->status === 'paid' ? 'PAID' : 'PENDING',
            statusClass: $payroll->status === 'paid' ? 'paid' : 'pending',
            employee: [
                'name' => trim("{$employee->first_name} {$employee->last_name}"),
                'id' => $employee->employee_id,
                'department' => $employee->department_display,
                'designation' => $employee->designation_display,
                'branch' => $employee->branch?->name ?? '—',
                'joining_date' => $employee->joining_date?->format('d M Y') ?? '—',
                'uan' => $payrollDetails?->uan_number,
                'pan' => $identity?->pan_number,
                'bank_name' => $payrollDetails?->bank_name,
                'bank_account' => $maskedAccount,
                'ifsc' => $payrollDetails?->ifsc_code ?? $payrollDetails?->bank_ifsc_code ?? null,
            ],
            earnings: $earnings,
            deductions: $deductions,
            grossEarnings: $gross,
            totalDeductions: $totalDed,
            netPayable: $net,
            amountInWords: IndianAmountInWords::format($net),
            payment: [
                'date' => $payroll->payment_date?->format('d M Y'),
                'method' => $paymentMethod,
                'bank_name' => $payrollDetails?->bank_name,
                'transaction_ref' => $payroll->transaction_id,
            ],
        );
    }

    public function downloadFilename(): string
    {
        $slug = Str::slug($this->employee['name']);

        return "payslip-{$slug}-{$this->periodShort}.pdf";
    }

    public function formatMoney(float $amount): string
    {
        return '₹' . number_format($amount, 2);
    }

    /**
     * @param  array<int, array{label: string, amount: float}>  $lines
     * @return array<int, array{label: string, amount: float}>
     */
    private static function compactLines(array $lines, bool $alwaysKeepFirst = false): array
    {
        $out = [];

        foreach ($lines as $index => $line) {
            if (($alwaysKeepFirst && $index === 0) || $line['amount'] > 0) {
                $out[] = $line;
            }
        }

        return $out;
    }
}
