<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'title', 'description', 'is_published'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function sections()
    {
        return $this->hasMany(QuizSection::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
