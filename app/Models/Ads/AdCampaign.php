<?php

namespace App\Models\Ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdCampaign extends Model
{
    protected $table = 'ad_campaigns';

    protected $fillable = [
        'uuid', 'slug', 'title', 'subtitle', 'description', 'course_name',
        'badge_text', 'hero_image', 'features', 'fee', 'original_fee',
        'brochure_pdf', 'cta_button_text', 'interest_button_text',
        'primary_color', 'secondary_color', 'accent_color', 'bg_color', 'text_color',
        'popup_type', 'popup_image', 'popup_delay_seconds', 'is_active', 'created_by',
        // New rich sections
        'faculty_name', 'faculty_title', 'faculty_bio', 'faculty_photo', 'faculty_experience',
        'stats', 'testimonials', 'faqs',
    ];

    protected $casts = [
        'features'     => 'array',
        'stats'        => 'array',
        'testimonials' => 'array',
        'faqs'         => 'array',
        'is_active'    => 'boolean',
        'fee'          => 'decimal:2',
        'original_fee' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($campaign) {
            if (empty($campaign->uuid)) {
                $campaign->uuid = (string) Str::uuid();
            }
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title) . '-' . Str::random(6);
            }
        });
    }

    public function leads()
    {
        return $this->hasMany(AdLead::class, 'campaign_id');
    }

    public function creator()
    {
        return $this->belongsTo(AdsUser::class, 'created_by');
    }

    public function publicUrl(): string
    {
        return url('/c/' . $this->slug);
    }

    public function discountPercent(): ?int
    {
        if ($this->original_fee && $this->original_fee > 0 && $this->fee) {
            return (int) round((($this->original_fee - $this->fee) / $this->original_fee) * 100);
        }
        return null;
    }
}
