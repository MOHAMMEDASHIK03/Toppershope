<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $path = 'course_videos/'.$filename;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Video not found.');
        }

        $fullPath = Storage::disk('public')->path($path);

        return response()->file($fullPath, [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Content-Type' => 'video/mp4',
            'Accept-Ranges' => 'bytes',
        ]);
    }

    /**
     * Stream or download a PDF securely (signed URL required).
     */
    public function downloadPdf(Request $request, $filename)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired PDF link.');
        }

        $path = $filename;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'PDF not found.');
        }

        if (! Auth::check()) {
            abort(401, 'Unauthorized access.');
        }

        $fullPath = Storage::disk('public')->path($path);
        $downloadName = $this->resolvePdfDownloadName($request, $path);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.$downloadName.'"',
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    private function resolvePdfDownloadName(Request $request, string $storagePath): string
    {
        $name = $request->query('name');

        if (is_string($name) && $name !== '') {
            return \App\Models\Note::sanitizeFilename($name);
        }

        return basename($storagePath);
    }
}
