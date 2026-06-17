<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Models\Admission\AdmissionContact;
use App\Models\Admission\TrialAccess;
use App\Models\Ads\AdLead;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_contacts'    => AdmissionContact::count(),
            'pending_calls'     => AdmissionContact::where('call_status', 'not_called')->count(),
            'will_join'         => AdmissionContact::where('outcome', 'will_join')->count(),
            'active_trials'     => TrialAccess::where('is_expired', false)->where('expires_at', '>', now())->count(),
            'trials_expiring'   => TrialAccess::where('is_expired', false)
                                    ->whereBetween('expires_at', [now(), now()->addDays(1)])->count(),
            'needs_followup'    => AdmissionContact::where('needs_followup', true)->count(),
        ];

        $recentContacts = AdmissionContact::with(['adLead', 'registeredUser'])
            ->latest()->take(5)->get();

        $expiringTrials = TrialAccess::with(['batch.course', 'contact'])
            ->where('is_expired', false)
            ->where('expires_at', '<=', now()->addDays(1))
            ->orderBy('expires_at')
            ->take(5)
            ->get();

        return view('admission.dashboard', compact('stats', 'recentContacts', 'expiringTrials'));
    }
}
