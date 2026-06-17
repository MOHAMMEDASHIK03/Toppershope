<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingInvitee extends Model
{
    protected $fillable = ['meeting_id', 'user_id', 'email', 'name', 'status'];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
