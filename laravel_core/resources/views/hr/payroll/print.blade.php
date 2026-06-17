<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payslip {{ $payslip->payslipNumber }}</title>
    @include('hr.payroll.partials.payslip-styles')
</head>
<body class="payslip-pdf-body">
    @include('hr.payroll.partials.payslip-document', ['payslip' => $payslip])
</body>
</html>
