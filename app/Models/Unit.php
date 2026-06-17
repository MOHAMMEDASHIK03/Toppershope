<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'name', 'order'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
