<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\Admission\AdmissionContact;
use App\Models\Admission\TrialAccess;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrialController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $trials = $this->paginateListing(
            TrialAccess::with(['batch.course', 'issuedBy', 'contact'])
                ->orderByRaw('is_expired ASC, expires_at ASC'),
            $request
        );

        return view('admission.trials.index', compact('trials'));
    }

    public function issue(Request $request, AdmissionContact $contact)
    {
        $data = $request->validate([
            'batch_id'  => ['required', 'exists:batches,id'],
            'days'      => ['nullable', 'integer', 'min:1', 'max:30'],
            'trial_email' => ['required', 'email', 'unique:trial_accesses,trial_email'],
        ]);

        $name  = $contact->display_name;
        $email = $contact->display_email;
        $phone = $contact->display_phone;

        // Generate a random 8-char temp password
        $rawPassword = Str::upper(Str::random(4)) . rand(100, 999) . Str::lower(Str::random(2));

        $trial = TrialAccess::create([
            'contact_id'      => $contact->id,
            'name'            => $name,
            'email'           => $email,
            'phone'           => $phone,
            'trial_email'     => $data['trial_email'],
            'trial_password'  => bcrypt($rawPassword),
            'plain_password'  => $rawPassword,
            'batch_id'        => $data['batch_id'],
            'issued_by'       => Auth::guard('admission')->id(),
            'expires_at'      => now()->addDays((int) ($data['days'] ?? 5)),
        ]);

        $contact->update([
            'trial_issued_at' => now(),
            'needs_followup'  => false,
        ]);

        // Send Email using Hostinger SMTP
        if (!empty($trial->email) && filter_var($trial->email, FILTER_VALIDATE_EMAIL)) {
            try {
                \Illuminate\Support\Facades\Mail::to($trial->email)->send(new \App\Mail\TrialAccessIssued($trial, $rawPassword));
            } catch (\Exception $e) {
                // Log and ignore email errors so it doesn't break the UI flow
                \Illuminate\Support\Facades\Log::error('Failed to send trial email: ' . $e->getMessage());
            }
        }

        // Show credentials in flash so the team can share them
        return back()->with('trial_created', [
            'email'    => $data['trial_email'],
            'password' => $rawPassword,
            'expires'  => $trial->expires_at->format('d M Y h:i A'),
            'batch'    => Batch::find($data['batch_id'])->name ?? '',
        ]);
    }

    public function expire(TrialAccess $trial)
    {
        $trial->update(['is_expired' => true]);
        if ($trial->contact_id) {
            $trial->contact()->update(['needs_followup' => true]);
        }
        return back()->with('success', 'Trial access revoked.');
    }

    /**
     * Called by scheduler to auto-expire and flag contacts.
     */
    public function checkExpired()
    {
        TrialAccess::where('is_expired', false)
            ->where('expires_at', '<', now())
            ->with('contact')
            ->chunk(50, function ($trials) {
                foreach ($trials as $trial) {
                    $trial->update(['is_expired' => true]);
                    if ($trial->contact_id) {
                        $trial->contact()->update(['needs_followup' => true]);
                    }
                }
            });
    }
}
