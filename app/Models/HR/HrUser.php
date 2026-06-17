<?php

namespace App\Models\HR;

use Illuminate\Foundation\Auth\User as Authenticatable;

class HrUser extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    public function isManager(): bool
    {
        return $this->role === 'hr_manager';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
