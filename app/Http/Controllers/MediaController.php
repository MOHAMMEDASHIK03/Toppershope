<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Securely stream a video file in chunks.
     * Prevents caching and requires a valid signed route.
     */
    public function streamVideo(Request $request, $filename)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired video link.');
        }

        $path = 'course_videos/' . $filename;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Video not found.');
        }

        $fullPath = Storage::disk('public')->path($path);
        
        // Let Laravel handle the chunked response natively and cleanly via StreamedResponse
        // But we need to enforce no-cache headers.
        
        return response()->file($fullPath, [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Content-Type' => 'video/mp4',
            'Accept-Ranges' => 'bytes'
        ]);
        
        // Note: Laravel's response()->file() automatically handles HTTP_RANGE / 206 Partial Content queries.
    }

    /**
     * Download a PDF securely with a dynamically generated watermark.
     */
    public function downloadPdf(Request $request, $filename)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired PDF link.');
        }

        // file_path is stored as e.g. 'course_notes/filename.pdf'
        $path = $filename; // The signed URL carries the full relative path

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'PDF not found.');
        }

        $fullPath = Storage::disk('public')->path($path);
        
        // If user is not logged in, we cannot watermark with their info
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized access.');
        }

        // Create new FPDI instance
        $pdf = new Fpdi();
        
        try {
            $pageCount = $pdf->setSourceFile($fullPath);
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                // Determine orientation automatically
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                
                $pdf->useTemplate($templateId);
                
                // Add Watermark
                $pdf->SetFont('Arial', 'B', 18);
                // Use light grey color to act as a watermark without obscuring text entirely
                $pdf->SetTextColor(200, 200, 200); 
                
                // Position across the center
                $pdf->SetXY(0, $size['height'] / 2);
                $watermarkText = $user->name . ' | ' . $user->email . ' | ' . now()->format('Y-m-d H:i');
                
                $pdf->Cell(0, 10, $watermarkText, 0, 0, 'C');
            }
            
            // Output the generated PDF securely
            return response(
                $pdf->Output('S'), // 'S' returns the document as a string
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="secure_' . $filename . '"',
                    'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
                    'Pragma' => 'no-cache',
                ]
            );

        } catch (\Exception $e) {
            abort(500, 'Error processing PDF: ' . $e->getMessage());
        }
    }
}
