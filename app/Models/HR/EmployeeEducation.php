<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;

    protected $table = 'employee_education';
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
