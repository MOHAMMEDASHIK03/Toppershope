<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\Ads\AdCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $campaigns = $this->paginateListing(AdCampaign::withCount('leads')->latest(), $request);
        return view('ads.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('ads.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateCampaign($request);
        $data = $this->handleUploads($request, $data);
        $data = $this->parseJsonFields($request, $data);

        $data['is_active']  = $request->boolean('is_active', true);
        $data['created_by'] = Auth::guard('ads')->id();
        $data['slug']       = Str::slug($data['title']) . '-' . Str::random(6);
        $data['uuid']       = (string) Str::uuid();

        AdCampaign::create($data);

        return redirect()->route('ads.campaigns.index')->with('success', 'Campaign created successfully!');
    }

    public function edit(AdCampaign $campaign)
    {
        return view('ads.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, AdCampaign $campaign)
    {
        $data = $this->validateCampaign($request);
        $data = $this->handleUploads($request, $data, $campaign);
        $data = $this->parseJsonFields($request, $data);

        $data['is_active'] = $request->boolean('is_active', true);

        $campaign->update($data);

        return redirect()->route('ads.campaigns.index')->with('success', 'Campaign updated successfully!');
    }

    public function destroy(AdCampaign $campaign)
    {
        foreach (['hero_image', 'popup_image', 'brochure_pdf', 'faculty_photo'] as $field) {
            if ($campaign->$field) Storage::disk('public')->delete($campaign->$field);
        }
        $campaign->delete();
        return redirect()->route('ads.campaigns.index')->with('success', 'Campaign deleted.');
    }

    public function toggleActive(AdCampaign $campaign)
    {
        $campaign->update(['is_active' => !$campaign->is_active]);
        return back()->with('success', 'Campaign status updated.');
    }

    // ─── Private Helpers ───────────────────────────────────────────────────────

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'title'                => ['required', 'string', 'max:255'],
            'subtitle'             => ['nullable', 'string', 'max:255'],
            'description'          => ['nullable', 'string'],
            'course_name'          => ['nullable', 'string', 'max:255'],
            'badge_text'           => ['nullable', 'string', 'max:100'],
            'hero_image'           => ['nullable', 'image', 'max:4096'],
            'features'             => ['nullable', 'string'],
            'fee'                  => ['nullable', 'numeric'],
            'original_fee'         => ['nullable', 'numeric'],
            'brochure_pdf'         => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'cta_button_text'      => ['nullable', 'string', 'max:80'],
            'interest_button_text' => ['nullable', 'string', 'max:80'],
            'primary_color'        => ['nullable', 'string', 'max:7'],
            'secondary_color'      => ['nullable', 'string', 'max:7'],
            'accent_color'         => ['nullable', 'string', 'max:7'],
            'bg_color'             => ['nullable', 'string', 'max:7'],
            'text_color'           => ['nullable', 'string', 'max:7'],
            'popup_type'           => ['required', 'in:none,global,custom'],
            'popup_image'          => ['nullable', 'image', 'max:4096'],
            'popup_delay_seconds'  => ['nullable', 'integer', 'min:0', 'max:30'],
            'is_active'            => ['nullable', 'boolean'],
            // Faculty
            'faculty_name'         => ['nullable', 'string', 'max:150'],
            'faculty_title'        => ['nullable', 'string', 'max:200'],
            'faculty_bio'          => ['nullable', 'string'],
            'faculty_photo'        => ['nullable', 'image', 'max:2048'],
            'faculty_experience'   => ['nullable', 'string', 'max:100'],
            // Dynamic JSON fields (sent as arrays from form)
            'stats'                => ['nullable', 'array'],
            'stats.*.label'        => ['nullable', 'string', 'max:80'],
            'stats.*.value'        => ['nullable', 'string', 'max:80'],
            'testimonials'         => ['nullable', 'array'],
            'testimonials.*.name'  => ['nullable', 'string', 'max:100'],
            'testimonials.*.course'=> ['nullable', 'string', 'max:150'],
            'testimonials.*.rank'  => ['nullable', 'string', 'max:80'],
            'testimonials.*.quote' => ['nullable', 'string'],
            'faqs'                 => ['nullable', 'array'],
            'faqs.*.question'      => ['nullable', 'string', 'max:255'],
            'faqs.*.answer'        => ['nullable', 'string'],
        ]);
    }

    private function handleUploads(Request $request, array $data, ?AdCampaign $existing = null): array
    {
        $uploads = [
            'hero_image'    => 'ads/heroes',
            'popup_image'   => 'ads/popups',
            'brochure_pdf'  => 'ads/brochures',
            'faculty_photo' => 'ads/faculty',
        ];

        foreach ($uploads as $field => $path) {
            if ($request->hasFile($field)) {
                if ($existing && $existing->$field) {
                    Storage::disk('public')->delete($existing->$field);
                }
                $data[$field] = $request->file($field)->store($path, 'public');
            } elseif ($existing) {
                unset($data[$field]); // don't overwrite with null on edit
            }
        }

        return $data;
    }

    private function parseJsonFields(Request $request, array $data): array
    {
        // Features: plain textarea one-per-line → array
        if (!empty($data['features']) && is_string($data['features'])) {
            $data['features'] = array_values(array_filter(array_map('trim', explode("\n", $data['features']))));
        }

        // Filter out blank stats/testimonials/faqs rows
        foreach (['stats', 'testimonials', 'faqs'] as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = array_values(array_filter($data[$field], function ($row) {
                    return !empty(array_filter(array_values($row), fn($v) => trim($v) !== ''));
                }));
                if (empty($data[$field])) $data[$field] = null;
            }
        }

        return $data;
    }
}
