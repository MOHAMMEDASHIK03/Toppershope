<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExit extends Model
{
    use HasFactory;

    protected $table = 'employee_exit';
    protected $guarded = [];

    protected $casts = [
        'resignation_date' => 'date',
        'last_working_day' => 'date',
        'assets_returned' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
