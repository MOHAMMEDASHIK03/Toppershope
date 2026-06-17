<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Support\ListingPagination;
use App\Models\Admission\AdmissionContact;
use App\Models\Admission\AdmissionRemark;
use App\Models\Ads\AdLead;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $tab      = $request->get('tab', 'enrol_leads');
        $courseId = $request->get('course_id');
        $batchId  = $request->get('batch_id');

        $courses = Course::orderBy('name')->get();
        $batches = $batchId ? Batch::where('course_id', $courseId)->get()
                            : ($courseId ? Batch::where('course_id', $courseId)->get() : collect());

        $contacts = match($tab) {
            'interest_leads' => $this->adLeadContacts($request, 'interest', $courseId, $batchId),
            'new_users'      => $this->newUserContacts($request, $courseId, $batchId),
            'non_purchasers' => $this->nonPurchaserContacts($request, $courseId, $batchId),
            default          => $this->adLeadContacts($request, 'enrol', $courseId, $batchId),
        };

        return view('admission.contacts.index', compact('contacts', 'tab', 'courses', 'batches', 'courseId', 'batchId'));
    }

    public function show(AdmissionContact $contact)
    {
        $contact->load(['adLead.campaign', 'registeredUser', 'remarks.staff', 'trial.batch.course', 'assignedTo']);
        $batches = Batch::with('course')->get();
        return view('admission.contacts.show', compact('contact', 'batches'));
    }

    public function updateStatus(Request $request, AdmissionContact $contact)
    {
        $data = $request->validate([
            'call_status' => ['nullable', 'in:not_called,answered,no_response'],
            'outcome'     => ['nullable', 'in:pending,will_join,rejected'],
        ]);

        if (isset($data['call_status'])) {
            $contact->call_status = $data['call_status'];
            if ($data['call_status'] !== 'not_called') {
                $contact->last_called_at = now();
            }
        }
        if (isset($data['outcome'])) {
            $contact->outcome = $data['outcome'];
        }
        $contact->save();

        return back()->with('success', 'Status updated.');
    }

    public function addRemark(Request $request, AdmissionContact $contact)
    {
        $data = $request->validate(['note' => ['required', 'string', 'max:1000']]);

        AdmissionRemark::create([
            'contact_id'       => $contact->id,
            'admission_user_id'=> Auth::guard('admission')->id(),
            'note'             => $data['note'],
            'called_at'        => now(),
        ]);

        // Auto-update last_called_at
        $contact->update(['last_called_at' => now()]);

        return back()->with('success', 'Remark saved.');
    }

    // ─── Private query builders ────────────────────────────────────────────────

    private function adLeadContacts(Request $request, string $type, ?int $courseId, ?int $batchId)
    {
        $query = AdmissionContact::with(['adLead.campaign', 'remarks', 'trial'])
            ->where('source_type', 'ad_lead')
            ->whereHas('adLead', fn($q) => $q->where('enquiry_type', $type === 'enrol' ? 'enrol' : 'interest'));

        if ($courseId) {
            $query->whereHas('adLead.campaign', function ($q) use ($courseId) {
                // Match campaign course_name by joining — approximate via campaign filter
            });
        }

        return ListingPagination::paginate($query->latest(), $request);
    }

    private function newUserContacts(Request $request, ?int $courseId, ?int $batchId)
    {
        $query = AdmissionContact::with(['registeredUser', 'remarks', 'trial'])
            ->where('source_type', 'registered');

        $query->whereHas('registeredUser', fn ($q) =>
            $q->where('created_at', '>=', now()->subDays(7))
        );

        return ListingPagination::paginate($query->latest(), $request);
    }

    private function nonPurchaserContacts(Request $request, ?int $courseId, ?int $batchId)
    {
        $query = AdmissionContact::with(['registeredUser.enrollments', 'remarks', 'trial'])
            ->where('source_type', 'non_purchaser');

        if ($batchId) {
            // Filter those whose linked user has never enrolled in this batch
        }

        return ListingPagination::paginate($query->latest(), $request);
    }

    // ─── Sync helpers (called from routes or admin) ───────────────────────────

    /**
     * Import all ad leads that don't yet have a contact record.
     */
    public function syncAdLeads()
    {
        $imported = 0;
        AdLead::whereDoesntHave('admissionContact')->chunk(100, function ($leads) use (&$imported) {
            foreach ($leads as $lead) {
                AdmissionContact::create([
                    'source_type' => 'ad_lead',
                    'ad_lead_id'  => $lead->id,
                ]);
                $imported++;
            }
        });
        return back()->with('success', "Imported {$imported} new ad leads.");
    }

    /**
     * Sync recent registrations and non-purchasers.
     */
    public function syncUsers()
    {
        $imported = 0;

        // New users (last 7 days) not yet in contacts
        User::where('created_at', '>=', now()->subDays(7))
            ->whereDoesntHave('admissionContact')
            ->chunk(100, function ($users) use (&$imported) {
                foreach ($users as $user) {
                    AdmissionContact::create([
                        'source_type' => 'registered',
                        'user_id'     => $user->id,
                    ]);
                    $imported++;
                }
            });

        // Non-purchasers: registered but 0 enrollments
        User::whereDoesntHave('enrollments')
            ->whereDoesntHave('admissionContact')
            ->chunk(100, function ($users) use (&$imported) {
                foreach ($users as $user) {
                    AdmissionContact::create([
                        'source_type' => 'non_purchaser',
                        'user_id'     => $user->id,
                    ]);
                    $imported++;
                }
            });

        return back()->with('success', "Synced {$imported} user contacts.");
    }
}
