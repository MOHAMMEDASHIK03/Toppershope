<?php

namespace App\Http\Controllers\Ads;

use App\Http\Controllers\Controller;
use App\Models\Ads\AdPopupGlobal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PopupController extends Controller
{
    public function index()
    {
        $popup = AdPopupGlobal::getInstance();
        return view('ads.popups.index', compact('popup'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image'          => ['nullable', 'image', 'max:4096'],
            'is_active'      => ['nullable', 'boolean'],
            'delay_seconds'  => ['nullable', 'integer', 'min:0', 'max:30'],
            'link_url'       => ['nullable', 'url'],
            'link_text'      => ['nullable', 'string', 'max:80'],
        ]);

        $popup = AdPopupGlobal::getInstance();

        $data = [
            'is_active'     => $request->boolean('is_active', false),
            'delay_seconds' => $request->input('delay_seconds', 3),
            'link_url'      => $request->input('link_url'),
            'link_text'     => $request->input('link_text', 'Learn More'),
            'updated_by'    => Auth::guard('ads')->id(),
        ];

        if ($request->hasFile('image')) {
            if ($popup->image) Storage::disk('public')->delete($popup->image);
            $data['image'] = $request->file('image')->store('ads/popups', 'public');
        }

        $popup->update($data);

        return back()->with('success', 'Global popup settings saved!');
    }
}
