<?php

namespace App\Models\Ads;

use Illuminate\Database\Eloquent\Model;

class AdLead extends Model
{
    protected $table = 'ad_leads';

    protected $fillable = [
        'campaign_id', 'name', 'email', 'phone', 'city',
        'message', 'enquiry_type', 'ip_address',
    ];

    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'campaign_id');
    }

    public function admissionContact()
    {
        return $this->hasOne(\App\Models\Admission\AdmissionContact::class, 'ad_lead_id');
    }
}
