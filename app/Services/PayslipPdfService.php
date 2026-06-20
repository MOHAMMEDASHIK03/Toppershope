<?php

namespace App\Services;

use App\Support\DompdfAutoload;
use App\Support\PayslipPresenter;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class PayslipPdfService
{
    public function download(PayslipPresenter $payslip): Response
    {
        DompdfAutoload::register();

        if (! class_exists(Dompdf::class)) {
            abort(500, 'PDF engine is not available. Run: composer dump-autoload');
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $html = View::make('hr.payroll.print', ['payslip' => $payslip])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $payslip->downloadFilename() . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }
}
