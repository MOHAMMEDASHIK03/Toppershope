<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $guarded = [];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function getApplicantNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getCoverLetterAttribute(): ?string
    {
        return $this->notes;
    }
}
