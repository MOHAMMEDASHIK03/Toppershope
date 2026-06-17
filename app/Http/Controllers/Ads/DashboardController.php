<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Models\Ads\AdCampaign;
use App\Models\Ads\AdLead;
use App\Models\Ads\AdPopupGlobal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $adsUser       = Auth::guard('ads')->user();
        $totalCampaigns = AdCampaign::count();
        $activeCampaigns = AdCampaign::where('is_active', true)->count();
        $totalLeads    = AdLead::count();
        $enrolLeads    = AdLead::where('enquiry_type', 'enrol')->count();
        $interestLeads = AdLead::where('enquiry_type', 'interest')->count();
        $recentLeads   = AdLead::with('campaign')->latest()->take(10)->get();
        $recentCampaigns = AdCampaign::latest()->take(5)->get();
        $popup         = AdPopupGlobal::getInstance();

        return view('ads.dashboard', compact(
            'adsUser', 'totalCampaigns', 'activeCampaigns',
            'totalLeads', 'enrolLeads', 'interestLeads',
            'recentLeads', 'recentCampaigns', 'popup'
        ));
    }
}
