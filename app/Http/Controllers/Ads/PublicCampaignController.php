<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Models\Ads\AdCampaign;
use App\Models\Ads\AdLead;
use App\Models\Ads\AdPopupGlobal;
use Illuminate\Http\Request;

class PublicCampaignController extends Controller
{
    /**
     * Render the public campaign landing page.
     */
    public function show(string $slug)
    {
        $campaign = AdCampaign::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Resolve popup: custom > global > none
        $popup = null;
        if ($campaign->popup_type === 'custom' && $campaign->popup_image) {
            $popup = (object) [
                'image'          => $campaign->popup_image,
                'delay_seconds'  => $campaign->popup_delay_seconds,
                'link_url'       => null,
                'link_text'      => 'Learn More',
            ];
        } elseif ($campaign->popup_type === 'global') {
            $globalPopup = AdPopupGlobal::getInstance();
            if ($globalPopup->is_active && $globalPopup->image) {
                $popup = $globalPopup;
            }
        }

        return view('pages.campaign', compact('campaign', 'popup'));
    }

    /**
     * Store a lead enquiry from the public campaign page.
     */
    public function storeLead(Request $request, string $slug)
    {
        $campaign = AdCampaign::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'email'        => ['required', 'email', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'city'         => ['nullable', 'string', 'max:100'],
            'message'      => ['nullable', 'string', 'max:1000'],
            'enquiry_type' => ['required', 'in:enrol,interest'],
        ]);

        $data['campaign_id'] = $campaign->id;
        $data['ip_address']  = $request->ip();

        AdLead::create($data);

        return back()->with('lead_success', true)->with('lead_type', $data['enquiry_type']);
    }
}
