@extends('layouts.ads')
@section('title', 'Global Popup')
@section('page_title', 'Homepage Popup Ad')

@section('content')
<div class="py-4 max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                <i class="ph-fill ph-browser text-orange-500 text-xl"></i>
            </div>
            <div>
                <h2 class="font-black text-slate-900">Global Homepage Popup</h2>
                <p class="text-xs text-slate-400">This popup appears on the homepage for ALL visitors. Per-campaign settings can override it.</p>
            </div>
        </div>

        @if ($popup->image)
        <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-4">
            <img src="{{ Storage::url($popup->image) }}" alt="Popup Preview" class="w-32 h-20 object-cover rounded-lg border border-slate-200">
            <div>
                <p class="font-bold text-slate-900 text-sm">Current Popup Image</p>
                <p class="text-xs text-slate-400 mt-1">Delay: {{ $popup->delay_seconds }}s &nbsp;|&nbsp; Status:
                    <span class="{{ $popup->is_active ? 'text-green-600' : 'text-slate-400' }} font-bold">
                        {{ $popup->is_active ? 'ACTIVE' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('ads.popups.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Popup Image <span class="font-normal text-slate-400">(JPG / PNG, max 4MB)</span></label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold hover:file:bg-orange-100 transition">
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Delay (seconds)</label>
                    <input type="number" name="delay_seconds" value="{{ $popup->delay_seconds }}" min="0" max="30"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-400 bg-slate-50 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Button Text</label>
                    <input type="text" name="link_text" value="{{ $popup->link_text }}"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-400 bg-slate-50 transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Link URL <span class="font-normal text-slate-400">(optional)</span></label>
                <input type="url" name="link_url" value="{{ $popup->link_url }}" placeholder="https://..."
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-orange-400/40 focus:border-orange-400 bg-slate-50 transition">
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $popup->is_active ? 'checked' : '' }}
                           class="w-5 h-5 rounded accent-orange-500">
                    <span class="font-bold text-slate-800">Enable global popup on homepage</span>
                </label>

                <button type="submit" class="px-7 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-black rounded-xl transition shadow-md shadow-orange-500/25 text-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
