<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\Ads\AdCampaign;
use App\Models\Ads\AdLead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $campaigns = AdCampaign::orderBy('title')->get(['id', 'title', 'slug']);
        $leads     = $this->paginateListing(
            AdLead::with('campaign')
                ->when($request->filled('campaign_id'), fn ($q) => $q->where('campaign_id', $request->integer('campaign_id')))
                ->when($request->filled('type'), fn ($q) => $q->where('enquiry_type', $request->input('type')))
                ->latest(),
            $request
        );

        return view('ads.leads.index', compact('leads', 'campaigns'));
    }
}
