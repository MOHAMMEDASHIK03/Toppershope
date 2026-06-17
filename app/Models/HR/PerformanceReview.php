<?php

namespace App\Models\HR;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id',
        'kpi_id',
        'reviewer_id',
        'review_period',
        'review_date',
        'rating',
        'manager_feedback',
        'employee_feedback',
        'status',
        'feedback',
        'goals_for_next_period',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
