<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'title', 'description', 'file_path', 'original_filename'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /** Filename shown when students download the PDF. */
    public function downloadFilename(): string
    {
        if (filled($this->original_filename)) {
            return self::sanitizeFilename((string) $this->original_filename);
        }

        $slug = Str::slug((string) $this->title);

        return self::sanitizeFilename(($slug ?: 'notes').'.pdf');
    }

    public static function sanitizeFilename(string $name): string
    {
        $name = basename(str_replace('\\', '/', trim($name)));

        if ($name === '') {
            return 'notes.pdf';
        }

        if (! str_ends_with(strtolower($name), '.pdf')) {
            $name .= '.pdf';
        }

        $clean = preg_replace('/[^\w\s\-.]/u', '', $name) ?? $name;
        $clean = preg_replace('/\s+/', ' ', trim($clean)) ?? $clean;

        return $clean !== '' ? $clean : 'notes.pdf';
    }
}
