<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    // A branch can have many employees
    public function employees()
    {
        return $this->hasMany(\App\Models\HR\Employee::class);
    }
}
