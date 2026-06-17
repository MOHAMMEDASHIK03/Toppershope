<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GenerateDummyMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-dummy-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a dummy notes.pdf and demo.mp4 in the private storage folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating dummy media for testing...');

        // 1. Generate Dummy PDF
        Storage::makeDirectory('private/pdfs');
        $pdfPath = Storage::path('private/pdfs/notes.pdf');
        
        if (!file_exists($pdfPath)) {
            $this->info('Creating base PDF template...');
            $pdf = new \setasign\Fpdi\Fpdi();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 24);
            $pdf->Cell(0, 20, 'Toppers Hope LMS - Class Notes', 0, 1, 'C');
            
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Target Exam: JEE / NEET (Advanced Tracking)', 0, 1, 'C');
            
            $pdf->Ln(20);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Chapter 1: Rotational Mechanics', 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 10, "1. Moment of Inertia is the rotational analogue of mass.\n2. Torque is the rotational analogue of force.\n3. Angular Momentum is conserved when net external torque is zero.\n\n\n\n(This document is intentionally left short for demonstration purposes.)");
            
            $pdf->Output('F', $pdfPath);
            $this->info('Base PDF notes.pdf created.');
        } else {
            $this->info('Base PDF already exists.');
        }

        // 2. Download Dummy Video (Small sample ~1MB)
        Storage::makeDirectory('private/videos');
        $videoPath = Storage::path('private/videos/demo.mp4');

        if (!file_exists($videoPath)) {
            $this->info('Downloading tiny sample mp4...');
            $videoUrl = 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
            
            $response = Http::timeout(30)->get($videoUrl);
            
            if ($response->successful()) {
                file_put_contents($videoPath, $response->body());
                $this->info('Sample demo.mp4 downloaded successfully.');
            } else {
                $this->error('Failed to download sample video.');
            }
        } else {
            $this->info('Sample video already exists.');
        }

        $this->info('All dummy media prepared!');
    }
}
