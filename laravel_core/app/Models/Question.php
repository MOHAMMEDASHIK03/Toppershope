<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_section_id', 'question_text', 'question_image_path', 'order'];

    public function section()
    {
        return $this->belongsTo(QuizSection::class, 'quiz_section_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
