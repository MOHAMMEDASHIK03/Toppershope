<?php

namespace App\Models\Admission;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdmissionUser extends Authenticatable
{
    protected $table = 'admission_users';

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_active' => 'boolean', 'password' => 'hashed'];

    public function isHead(): bool
    {
        return $this->role === 'head';
    }

    public function contacts()
    {
        return $this->hasMany(AdmissionContact::class, 'assigned_to');
    }

    public function remarks()
    {
        return $this->hasMany(AdmissionRemark::class, 'admission_user_id');
    }

    public function trialsIssued()
    {
        return $this->hasMany(TrialAccess::class, 'issued_by');
    }
}
