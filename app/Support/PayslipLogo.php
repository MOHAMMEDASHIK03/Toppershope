<?php

namespace App\Support;

class PayslipLogo
{
    private static ?string $screenCache = null;

    private static ?string $pdfCache = null;

    /** Logo for on-screen payslip (PNG preferred). */
    public static function dataUri(): ?string
    {
        if (self::$screenCache !== null) {
            return self::$screenCache !== '' ? self::$screenCache : null;
        }

        self::$screenCache = self::encodePaths(self::screenCandidatePaths()) ?? '';

        return self::$screenCache !== '' ? self::$screenCache : null;
    }

    /**
     * Logo for Dompdf PDF export.
     * JPEG works without the PHP GD extension; PNG requires GD in Dompdf.
     */
    public static function dataUriForPdf(): ?string
    {
        if (self::$pdfCache !== null) {
            return self::$pdfCache !== '' ? self::$pdfCache : null;
        }

        self::$pdfCache = self::encodePaths(self::pdfCandidatePaths()) ?? '';

        return self::$pdfCache !== '' ? self::$pdfCache : null;
    }

    /** @param  array<int, string>  $paths */
    private static function encodePaths(array $paths): ?string
    {
        foreach ($paths as $path) {
            if (! is_file($path)) {
                continue;
            }

            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                default => 'image/png',
            };

            return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
        }

        return null;
    }

    /** @return array<int, string> */
    private static function screenCandidatePaths(): array
    {
        return [
            public_path('images/brand/logo-icon.png'),
            public_path('images/brand/logo-icon.jpg'),
            public_path('images/brand/logo-full.png'),
        ];
    }

    /** @return array<int, string> */
    private static function pdfCandidatePaths(): array
    {
        $paths = [
            public_path('images/brand/logo-icon.jpg'),
        ];

        // PNG/GIF/WebP in Dompdf require GD; only include when available.
        if (extension_loaded('gd')) {
            $paths[] = public_path('images/brand/logo-icon.png');
            $paths[] = public_path('images/brand/logo-full.png');
        }

        return $paths;
    }
}
