<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeLive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, ?int $departmentId)
    {
        return $query->where(function ($q) use ($departmentId) {
            $q->whereNull('department_id');
            if ($departmentId) {
                $q->orWhere('department_id', $departmentId);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(HrUser::class, 'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
