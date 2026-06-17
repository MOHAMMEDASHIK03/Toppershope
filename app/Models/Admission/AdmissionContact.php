<?php

namespace App\Models\Admission;

use App\Models\Ads\AdLead;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdmissionContact extends Model
{
    protected $table = 'admission_contacts';

    protected $fillable = [
        'source_type', 'ad_lead_id', 'user_id', 'assigned_to',
        'call_status', 'outcome', 'last_called_at', 'trial_issued_at', 'needs_followup',
    ];

    protected $casts = [
        'last_called_at'  => 'datetime',
        'trial_issued_at' => 'datetime',
        'needs_followup'  => 'boolean',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function adLead()
    {
        return $this->belongsTo(AdLead::class, 'ad_lead_id');
    }

    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(AdmissionUser::class, 'assigned_to');
    }

    public function remarks()
    {
        return $this->hasMany(AdmissionRemark::class, 'contact_id')->latest();
    }

    public function trial()
    {
        return $this->hasOne(TrialAccess::class, 'contact_id')->latest();
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Get the display name regardless of source.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->adLead) return $this->adLead->name ?? '—';
        if ($this->registeredUser) return $this->registeredUser->name ?? '—';
        return '—';
    }

    /**
     * Get the email regardless of source.
     */
    public function getDisplayEmailAttribute(): string
    {
        if ($this->adLead) return $this->adLead->email ?? '—';
        if ($this->registeredUser) return $this->registeredUser->email ?? '—';
        return '—';
    }

    /**
     * Get the phone regardless of source.
     */
    public function getDisplayPhoneAttribute(): string
    {
        if ($this->adLead) return $this->adLead->phone ?? '—';
        if ($this->registeredUser) return $this->registeredUser->phone ?? '—';
        return '—';
    }

    public function getCallStatusBadgeAttribute(): array
    {
        return match($this->call_status) {
            'answered'    => ['label' => 'Answered',    'class' => 'bg-green-100 text-green-700'],
            'no_response' => ['label' => 'No Response', 'class' => 'bg-yellow-100 text-yellow-700'],
            default       => ['label' => 'Not Called',  'class' => 'bg-slate-100 text-slate-500'],
        };
    }

    public function getOutcomeBadgeAttribute(): array
    {
        return match($this->outcome) {
            'will_join' => ['label' => 'Will Join',  'class' => 'bg-blue-100 text-blue-700'],
            'rejected'  => ['label' => 'Rejected',   'class' => 'bg-red-100 text-red-600'],
            default     => ['label' => 'Pending',    'class' => 'bg-orange-100 text-orange-600'],
        };
    }
}
