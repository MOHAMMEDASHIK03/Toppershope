<?php

namespace App\Support;

use Composer\Autoload\ClassLoader;

/**
 * Registers DomPDF packages when Composer's generated autoload map is stale.
 */
final class DompdfAutoload
{
    private static bool $registered = false;

    public static function register(): void
    {
        if (self::$registered || class_exists(\Dompdf\Dompdf::class)) {
            self::$registered = true;

            return;
        }

        $loader = self::composerLoader();
        if (! $loader) {
            return;
        }

        $vendor = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'vendor';

        $loader->addPsr4('Dompdf\\', "{$vendor}/dompdf/dompdf/src");
        $loader->addClassMap([
            'Dompdf\\Cpdf' => "{$vendor}/dompdf/dompdf/lib/Cpdf.php",
        ]);
        $loader->addPsr4('FontLib\\', "{$vendor}/dompdf/php-font-lib/src/FontLib");
        $loader->addPsr4('Svg\\', "{$vendor}/dompdf/php-svg-lib/src/Svg");
        $loader->addPsr4('Masterminds\\', "{$vendor}/masterminds/html5/src");
        $loader->addPsr4('Sabberworm\\CSS\\', "{$vendor}/sabberworm/php-css-parser/src");
        $loader->addPsr4('Barryvdh\\DomPDF\\', "{$vendor}/barryvdh/laravel-dompdf/src");

        self::loadSafeFunctions("{$vendor}/thecodingmachine/safe");

        self::$registered = true;
    }

    private static function composerLoader(): ?ClassLoader
    {
        static $loader = null;

        if ($loader instanceof ClassLoader) {
            return $loader;
        }

        $instance = require dirname(__DIR__, 2) . '/vendor/autoload.php';

        $loader = $instance instanceof ClassLoader ? $instance : null;

        return $loader;
    }

    private static function loadSafeFunctions(string $safeDir): void
    {
        $generated = "{$safeDir}/generated";
        if (is_dir($generated)) {
            foreach (glob("{$generated}/*.php") ?: [] as $file) {
                require_once $file;
            }
        }

        $special = "{$safeDir}/lib/special_cases.php";
        if (is_file($special)) {
            require_once $special;
        }
    }
}
