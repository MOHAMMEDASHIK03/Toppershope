<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = [
        'title',
        'description',
        'department_id',
        'designation_id',
        'employee_id',
        'target',
        'status',
        'deadline',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }
}
