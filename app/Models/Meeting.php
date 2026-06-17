<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'faculty_id', 'course_id', 'batch_id', 'title', 'description',
        'start_at', 'end_at', 'google_event_id', 'meet_link', 'type', 'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function invitees()
    {
        return $this->hasMany(MeetingInvitee::class);
    }

    public function isUpcoming(): bool
    {
        return $this->start_at->isFuture() && $this->status === 'scheduled';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
