<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
