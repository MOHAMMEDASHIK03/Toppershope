<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminPasswordResetOtp extends Model
{
    protected $fillable = [
        'email',
        'otp_hash',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attempts'   => 'integer',
    ];
}
