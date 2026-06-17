<?php

namespace App\Models\Ads;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdsUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'ads_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdsHead(): bool
    {
        return $this->role === 'ads_head';
    }

    public function campaigns()
    {
        return $this->hasMany(AdCampaign::class, 'created_by');
    }
}
