<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
