<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleToken extends Model
{
    protected $fillable = [
        'user_id', 'access_token', 'refresh_token',
        'token_type', 'expires_at', 'google_email',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Is the access token still valid? */
    public function isExpired(): bool
    {
        return $this->expires_at && now()->gte($this->expires_at);
    }
}
