<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
