<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeIdentity extends Model
{
    use HasFactory;

    protected $table = 'employee_identity';
    protected $guarded = [];

    protected $casts = [
        'passport_expiry' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
