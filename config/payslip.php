<?php

return [
    'company_name' => env('PAYSLIP_COMPANY_NAME', "Topper's Hope"),
    'company_tagline' => env('PAYSLIP_COMPANY_TAGLINE', 'Educational Institution'),

    // Fallback when payroll has no HR issuer (legacy rows) or issuer phone is empty
    'phone' => env('PAYSLIP_PHONE', '+91 76392 76646'),
    'email' => env('PAYSLIP_EMAIL', 'hr@toppershope.com'),

    'system_name' => env('PAYSLIP_SYSTEM_NAME', "Topper's Hope HRMS"),
];
