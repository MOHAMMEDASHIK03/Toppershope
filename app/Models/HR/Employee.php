<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    protected $casts = [
        'joining_date' => 'date',
        'date_of_birth' => 'date',
        'confirmation_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class);
    }

    // --- New Modular Relationships ---

    public function personal()
    {
        return $this->hasOne(EmployeePersonal::class);
    }

    public function contact()
    {
        return $this->hasOne(EmployeeContact::class);
    }

    public function identity()
    {
        return $this->hasOne(EmployeeIdentity::class);
    }

    public function payrollDetails()
    {
        return $this->hasOne(EmployeePayrollDetail::class);
    }

    public function education()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function skills()
    {
        return $this->hasMany(EmployeeSkill::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function assets()
    {
        return $this->hasMany(EmployeeAsset::class);
    }

    public function exitDetails()
    {
        return $this->hasOne(EmployeeExit::class);
    }

    public function account()
    {
        return $this->morphTo();
    }

    public function reportingManager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager_id');
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Resolve designation when FK is missing (e.g. legacy rows named "Admission Head").
     */
    public function resolvedDesignation(): ?Designation
    {
        if ($this->designation) {
            return $this->designation;
        }

        $candidates = array_unique(array_filter([
            trim("{$this->first_name} {$this->last_name}"),
            trim((string) $this->first_name),
            trim((string) $this->last_name),
        ]));

        foreach ($candidates as $candidate) {
            $byName = Designation::query()
                ->with('department')
                ->where('name', $candidate)
                ->first();

            if ($byName) {
                return $byName;
            }
        }

        return null;
    }

    public function getDesignationDisplayAttribute(): string
    {
        return $this->resolvedDesignation()?->name
            ?? $this->designation?->name
            ?? 'Not assigned';
    }

    public function getDepartmentDisplayAttribute(): string
    {
        if ($this->department?->name) {
            return $this->department->name;
        }

        $viaDesignation = $this->resolvedDesignation()?->department?->name
            ?? $this->designation?->department?->name;

        return $viaDesignation ?? 'Not assigned';
    }
}

