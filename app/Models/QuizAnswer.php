<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuizAttempt;
use App\Models\Question;
class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id', 'question_id', 'option_id', 'subjective_answer', 'is_correct'
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
