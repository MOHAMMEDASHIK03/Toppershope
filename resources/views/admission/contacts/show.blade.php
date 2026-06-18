@extends('layouts.admission')
@section('title', $contact->display_name)
@section('page_title', $contact->display_name)

@section('content')
<div class="pt-2 space-y-6">

<a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-primary-500 transition">
    ← Back to contacts
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Profile card + Status + Issue trial --}}
    <div class="space-y-5">

        {{-- Profile card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl text-white mx-auto mb-4"
                 style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                {{ strtoupper(substr($contact->display_name, 0, 1)) }}
            </div>
            <h2 class="font-black text-slate-900 text-lg">{{ $contact->display_name }}</h2>

            @if($contact->needs_followup)
            <div class="mt-2 inline-block px-3 py-1 text-xs font-black bg-amber-100 text-amber-700 rounded-full">
                ⏰ Trial Expired — Call Now!
            </div>
            @endif

            <div class="mt-4 space-y-2 text-left">
                @php $info = [
                    ['📧', $contact->display_email],
                    ['📞', $contact->display_phone],
                    ['📌', match($contact->source_type) {
                        'ad_lead' => 'Ad Lead',
                        'registered' => 'Registered User',
                        default => 'Non-Purchaser'
                    }],
                ]; @endphp
                @foreach($info as [$icon, $val])
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-base">{{ $icon }}</span>
                    <span class="text-slate-600 font-medium truncate">{{ $val }}</span>
                </div>
                @endforeach
                @if($contact->adLead?->campaign)
                <div class="flex items-start gap-3 text-sm">
                    <span class="text-base">📢</span>
                    <span class="text-slate-600 font-medium">{{ $contact->adLead->campaign->title }}</span>
                </div>
                @endif
                @if($contact->last_called_at)
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-base">🕐</span>
                    <span class="text-slate-500 font-medium">Last called {{ $contact->last_called_at->diffForHumans() }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Status toggles --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-black text-slate-900 text-sm mb-4">Update Status</h3>
            <form method="POST" action="{{ route('admission.contacts.status', $contact) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Call Status</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['not_called' => ['Not Called','slate'], 'answered' => ['Answered','green'], 'no_response' => ['No Response','yellow']] as $val => [$lbl, $col])
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="call_status" value="{{ $val }}" class="sr-only" {{ $contact->call_status === $val ? 'checked' : '' }}>
                            <span class="block text-xs font-bold py-2.5 px-1 rounded-xl border-2 transition
                                {{ $contact->call_status === $val
                                    ? 'border-'.$col.'-500 bg-'.$col.'-50 text-'.$col.'-700'
                                    : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                {{ $lbl }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Outcome</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['pending' => ['Pending','orange'], 'will_join' => ['Will Join','blue'], 'rejected' => ['Rejected','red']] as $val => [$lbl, $col])
                        <label class="cursor-pointer text-center">
                            <input type="radio" name="outcome" value="{{ $val }}" class="sr-only" {{ $contact->outcome === $val ? 'checked' : '' }}>
                            <span class="block text-xs font-bold py-2.5 px-1 rounded-xl border-2 transition
                                {{ $contact->outcome === $val
                                    ? 'border-'.$col.'-500 bg-'.$col.'-50 text-'.$col.'-700'
                                    : 'border-slate-200 text-slate-500 hover:border-slate-300' }}">
                                {{ $lbl }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-2.5 text-white font-black text-sm rounded-xl transition hover:opacity-90"
                        style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                    Save Status
                </button>
            </form>
        </div>

        {{-- Trial: issue or current status --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-black text-slate-900 text-sm mb-4">🧪 Trial Access</h3>

            @if($contact->trial && !$contact->trial->is_expired && $contact->trial->expires_at->isFuture())
            {{-- Active trial --}}
            <div class="p-4 bg-primary-50 border border-emerald-200 rounded-xl space-y-2">
                <p class="text-xs font-black text-emerald-700 uppercase">Active Trial</p>
                <p class="text-sm font-bold text-slate-800">{{ $contact->trial->name }}</p>
                <div class="bg-white/60 p-2 my-2 rounded-lg border border-emerald-100 flex flex-col gap-1">
                    <p class="text-xs text-slate-500 font-mono"><span class="font-bold text-emerald-800">U:</span> {{ $contact->trial->trial_email }}</p>
                    <p class="text-xs text-slate-500 font-mono"><span class="font-bold text-emerald-800">P:</span> {{ $contact->trial->plain_password ?? 'N/A' }}</p>
                </div>
                <p class="text-xs text-slate-600 mt-2">Batch: <strong>{{ $contact->trial->batch?->name }}</strong></p>
                <p class="text-xs font-bold {{ $contact->trial->daysLeft() <= 1 ? 'text-red-600' : 'text-emerald-700' }}">
                    Expires: {{ $contact->trial->expires_at->format('d M Y, h:i A') }}
                    ({{ $contact->trial->daysLeft() }}d left)
                </p>
            </div>
            <form method="POST" action="{{ route('admission.trials.expire', $contact->trial) }}" class="mt-3">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Revoke this trial?')"
                        class="w-full py-2 text-red-600 font-bold text-xs rounded-xl border border-red-200 hover:bg-red-50 transition">Revoke Trial</button>
            </form>

            @elseif($contact->trial && ($contact->trial->is_expired || $contact->trial->expires_at->isPast()))
            {{-- Expired trial --}}
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-3">
                <p class="text-xs font-black text-red-600">Trial has expired — Issue a new one below</p>
            </div>
            @include('admission.contacts._trial_form', compact('batches', 'contact'))

            @else
            {{-- No trial yet --}}
            @include('admission.contacts._trial_form', compact('batches', 'contact'))
            @endif
        </div>

    </div>

    {{-- RIGHT: Remarks timeline + add remark --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Add remark --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-black text-slate-900 text-sm mb-4">📝 Add Call Note</h3>
            <form method="POST" action="{{ route('admission.contacts.remark', $contact) }}" class="flex flex-col gap-3">
                @csrf
                <textarea name="note" rows="3" required
                          placeholder="What happened on the call? What did the student say? Any follow-up needed?"
                          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 resize-none bg-slate-50 transition"></textarea>
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-5 py-2.5 text-white font-black text-sm rounded-xl hover:opacity-90 transition"
                            style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                        Save Note
                    </button>
                </div>
            </form>
        </div>

        {{-- Call history timeline --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-black text-slate-900 text-sm">Call History & Remarks</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $contact->remarks->count() }} note(s)</p>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($contact->remarks as $remark)
                <div class="px-6 py-5 flex gap-4">
                    <div class="w-9 h-9 rounded-xl bg-primary-100 flex items-center justify-center font-black text-primary-700 text-sm shrink-0">
                        {{ strtoupper(substr($remark->staff?->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <p class="font-black text-slate-800 text-sm">{{ $remark->staff?->name ?? 'Staff' }}</p>
                            <p class="text-xs text-slate-400 font-medium">{{ $remark->called_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $remark->note }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-slate-400 text-sm">
                    No call notes yet. Add the first note above after calling this student.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</div>
@endsection
