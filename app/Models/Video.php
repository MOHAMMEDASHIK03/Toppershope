<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'title', 'description', 'video_url', 'video_path', 'duration_minutes'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
