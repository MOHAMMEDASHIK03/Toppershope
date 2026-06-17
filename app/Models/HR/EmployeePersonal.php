<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonal extends Model
{
    use HasFactory;

    protected $table = 'employee_personal';
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
