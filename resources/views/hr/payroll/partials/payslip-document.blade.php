{{-- @var \App\Support\PayslipPresenter $payslip --}}
<div class="payslip-doc">
    <div class="payslip-doc__sheet">

        <div class="payslip-doc__header">
            <div class="payslip-doc__header-left">
                <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Topper's Hope" class="payslip-doc__logo" style="width:56px;height:56px;object-fit:contain;border-radius:8px;">
                <h1 class="payslip-doc__company-name">{{ strtoupper($payslip->company['company_name']) }}</h1>
                <p class="payslip-doc__company-tag">{{ $payslip->company['company_tagline'] }}</p>
                <div class="payslip-doc__company-meta">
                    {{ $payslip->company['address_line_1'] }} · {{ $payslip->company['address_line_2'] }}<br>
                    {{ $payslip->company['phone'] }} · {{ $payslip->company['email'] }}
                </div>
            </div>
            <div class="payslip-doc__header-right">
                <p class="payslip-doc__title">PAYSLIP</p>
                <p class="payslip-doc__period">{{ $payslip->periodLabel }}</p>
                <p class="payslip-doc__meta-line"><strong>No:</strong> {{ $payslip->payslipNumber }}</p>
                <p class="payslip-doc__meta-line"><strong>Generated:</strong> {{ $payslip->generatedAt }}</p>
                <span class="payslip-doc__badge payslip-doc__badge--{{ $payslip->statusClass }}">{{ $payslip->statusLabel }}</span>
            </div>
        </div>

        <p class="payslip-doc__section-title">Employee Information</p>
        <div class="payslip-doc__employee-grid">
            <div class="payslip-doc__employee-row">
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Name</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['name'] }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Employee ID</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['id'] }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Department</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['department'] }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Designation</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['designation'] }}</div>
                </div>
            </div>
            <div class="payslip-doc__employee-row">
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Branch</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['branch'] }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">Joining Date</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['joining_date'] }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">UAN</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['uan'] ?? '—' }}</div>
                </div>
                <div class="payslip-doc__employee-cell">
                    <div class="payslip-doc__label">PAN</div>
                    <div class="payslip-doc__value">{{ $payslip->employee['pan'] ?? '—' }}</div>
                </div>
            </div>
        </div>

        <p class="payslip-doc__section-title">Salary Breakdown</p>
        <div class="payslip-doc__tables">
            <div class="payslip-doc__table-col">
                <table class="payslip-doc__table">
                    <thead>
                        <tr><th>Earnings</th><th>Amount (₹)</th></tr>
                    </thead>
                    <tbody>
                        @foreach($payslip->earnings as $line)
                        <tr>
                            <td>{{ $line['label'] }}</td>
                            <td>{{ number_format($line['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Gross Earnings</td>
                            <td>{{ number_format($payslip->grossEarnings, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="payslip-doc__table-col">
                <table class="payslip-doc__table payslip-doc__table--deductions">
                    <thead>
                        <tr><th>Deductions</th><th>Amount (₹)</th></tr>
                    </thead>
                    <tbody>
                        @foreach($payslip->deductions as $line)
                        <tr>
                            <td>{{ $line['label'] }}</td>
                            <td>{{ number_format($line['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total Deductions</td>
                            <td>{{ number_format($payslip->totalDeductions, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="payslip-doc__bottom">
            <div class="payslip-doc__net-bar">
                <div class="payslip-doc__net-label">Net Pay</div>
                <div class="payslip-doc__net-value">{{ $payslip->formatMoney($payslip->netPayable) }}</div>
            </div>

            <div class="payslip-doc__words">
                <strong>Amount in Words:</strong> {{ $payslip->amountInWords }}
            </div>

            <p class="payslip-doc__section-title">Payment Details</p>
            <div class="payslip-doc__payment">
                <div class="payslip-doc__payment-cell">
                    <div class="payslip-doc__label">Payment Date</div>
                    <div class="payslip-doc__value">{{ $payslip->payment['date'] ?? ($payslip->statusLabel === 'PAID' ? '—' : 'Pending') }}</div>
                </div>
                <div class="payslip-doc__payment-cell">
                    <div class="payslip-doc__label">Method</div>
                    <div class="payslip-doc__value">{{ $payslip->payment['method'] }}</div>
                </div>
                <div class="payslip-doc__payment-cell">
                    <div class="payslip-doc__label">Bank</div>
                    <div class="payslip-doc__value">{{ $payslip->payment['bank_name'] ?? '—' }}</div>
                </div>
                <div class="payslip-doc__payment-cell">
                    <div class="payslip-doc__label">Account / Ref</div>
                    <div class="payslip-doc__value">
                        @if($payslip->employee['bank_account'])
                            {{ $payslip->employee['bank_account'] }}
                        @else
                            {{ $payslip->payment['transaction_ref'] ?? '—' }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="payslip-doc__footer">
                <p><strong>This is a system-generated payslip and does not require a physical signature.</strong></p>
                <p>Generated on {{ now()->format('d M Y, h:i A') }} · {{ $payslip->company['system_name'] }} · Confidential</p>
            </div>
        </div>

    </div>
</div>
