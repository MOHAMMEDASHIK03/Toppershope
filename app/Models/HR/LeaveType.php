<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $guarded = [];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
