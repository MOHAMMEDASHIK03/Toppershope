<?php

namespace App\Models\Ads;

use Illuminate\Database\Eloquent\Model;

class AdPopupGlobal extends Model
{
    protected $table = 'ad_popup_global';

    protected $fillable = [
        'image', 'is_active', 'delay_seconds', 'link_url', 'link_text', 'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Always fetch or create the single global popup row.
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate([], [
            'is_active'     => false,
            'delay_seconds' => 3,
            'link_text'     => 'Learn More',
        ]);
    }
}
