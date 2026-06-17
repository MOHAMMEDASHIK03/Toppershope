<?php

namespace App\Models\Admission;

use App\Models\Batch;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class TrialAccess extends Authenticatable
{
    protected $table = 'trial_accesses';

    protected $fillable = [
        'uuid', 'contact_id', 'name', 'email', 'phone',
        'trial_email', 'trial_password', 'plain_password', 'batch_id',
        'issued_by', 'expires_at', 'is_expired',
    ];

    protected $hidden = ['trial_password', 'remember_token'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_expired' => 'boolean',
        'trial_password' => 'hashed',
    ];

    // Laravel auth uses 'password' field by default — map it
    public function getAuthPassword(): string
    {
        return $this->trial_password;
    }

    protected static function booted(): void
    {
        static::creating(function ($trial) {
            if (empty($trial->uuid)) $trial->uuid = (string) Str::uuid();
        });
    }

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(AdmissionUser::class, 'issued_by');
    }

    public function contact()
    {
        return $this->belongsTo(AdmissionContact::class, 'contact_id');
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return !$this->is_expired && $this->expires_at->isFuture();
    }

    public function daysLeft(): int
    {
        if ($this->is_expired) return 0;
        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }

    /**
     * Returns IDs of chapters the trial student is allowed to access.
     * Rule: only the first chapter (lowest order) per subject in the assigned batch's course.
     */
    public function allowedChapterIds(): array
    {
        return \App\Models\Subject::where('course_id', $this->batch->course_id)
            ->with(['chapters' => fn($q) => $q->orderBy('order')->limit(1)])
            ->get()
            ->flatMap(fn($subject) => $subject->chapters->pluck('id'))
            ->toArray();
    }
}
