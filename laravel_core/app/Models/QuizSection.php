<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 
        'name', 
        'time_limit_minutes', 
        'marks_per_question', 
        'negative_marks_per_question', 
        'order'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
}
