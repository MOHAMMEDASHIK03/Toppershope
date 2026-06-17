@extends('layouts.admission')
@section('title', 'Contacts')
@section('page_title', 'Contacts')

@section('content')
<div class="pt-2 space-y-4">

{{-- Tab bar --}}
<div class="flex flex-wrap border-b border-slate-200 gap-0 -mb-px">
    @php
        $tabs = [
            'enrol_leads'    => ['Enrol Leads',       '📋'],
            'interest_leads' => ['Interested Leads',  '💡'],
            'new_users'      => ['New Registrations', '🆕'],
            'non_purchasers' => ['Non-Purchasers',    '🛒'],
        ];
    @endphp
    @foreach($tabs as $key => [$label, $icon])
    <a href="{{ route('admission.contacts.index', array_merge(request()->except('tab','page'), ['tab' => $key])) }}"
       class="flex items-center gap-2 px-5 py-3.5 text-sm font-bold whitespace-nowrap border-b-2 transition-colors
              {{ $tab === $key
                    ? 'border-indigo-500 text-orange-600'
                    : 'border-transparent text-slate-500 hover:text-slate-800' }}">
        <span>{{ $icon }}</span> {{ $label }}
    </a>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admission.contacts.index') }}" class="flex flex-wrap items-center gap-3">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <select name="course_id" onchange="this.form.submit()"
            class="px-3 py-2 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none">
        <option value="">All Courses</option>
        @foreach($courses as $c)
        <option value="{{ $c->id }}" {{ $courseId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
    </select>
    @if($courseId && $batches->count())
    <select name="batch_id" onchange="this.form.submit()"
            class="px-3 py-2 text-sm border border-slate-200 rounded-xl bg-white text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none">
        <option value="">All Batches</option>
        @foreach($batches as $b)
        <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
    </select>
    @endif
    @if($courseId || $batchId)
    <a href="{{ route('admission.contacts.index', ['tab' => $tab]) }}"
       class="text-xs font-bold text-slate-400 hover:text-red-500 transition">✕ Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
        <p class="text-sm text-slate-500 font-semibold">{{ $contacts->total() }} records</p>
    </div>

    @if($contacts->isEmpty())
    <div class="px-6 py-16 text-center">
        <p class="text-3xl mb-3">📭</p>
        <p class="text-slate-500 text-sm font-semibold">No contacts in this tab yet.</p>
        <p class="text-slate-400 text-xs mt-1">Use the Sync buttons on the Dashboard to import leads.</p>
    </div>
    @else
    <div class="panel-table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Name</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide hidden sm:table-cell">Phone</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide hidden md:table-cell">Source</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Call Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Outcome</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide hidden lg:table-cell">Trial</th>
                    <th class="px-5 py-3.5 text-left text-xs font-black text-slate-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($contacts as $contact)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs text-white shrink-0"
                                 style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                {{ strtoupper(substr($contact->display_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 leading-tight">{{ $contact->display_name }}</p>
                                <p class="text-xs text-slate-400">{{ $contact->display_email }}</p>
                            </div>
                        </div>
                        @if($contact->needs_followup)
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-black bg-amber-100 text-amber-700 rounded-full">⏰ Follow-up</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden sm:table-cell">
                        <a href="tel:{{ $contact->display_phone }}"
                           class="font-mono text-sm font-semibold text-slate-700 hover:text-orange-600 transition">{{ $contact->display_phone }}</a>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full
                            {{ $contact->source_type === 'ad_lead' ? 'bg-orange-100 text-orange-700'
                               : ($contact->source_type === 'registered' ? 'bg-blue-100 text-blue-700'
                               : 'bg-purple-100 text-purple-700') }}">
                            {{ str_replace('_', ' ', ucfirst($contact->source_type)) }}
                        </span>
                        @if($contact->adLead?->campaign)
                        <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($contact->adLead->campaign->title, 20) }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admission.contacts.status', $contact) }}" class="inline">
                            @csrf @method('PATCH')
                            <select name="call_status" onchange="this.form.submit()"
                                    class="text-xs font-bold border-0 rounded-lg p-1.5 focus:ring-1 focus:ring-indigo-300 outline-none cursor-pointer
                                           {{ $contact->call_status === 'answered' ? 'bg-green-100 text-green-700'
                                              : ($contact->call_status === 'no_response' ? 'bg-yellow-100 text-yellow-700'
                                              : 'bg-slate-100 text-slate-500') }}">
                                <option value="not_called"  {{ $contact->call_status === 'not_called'  ? 'selected' : '' }}>Not Called</option>
                                <option value="answered"    {{ $contact->call_status === 'answered'    ? 'selected' : '' }}>Answered</option>
                                <option value="no_response" {{ $contact->call_status === 'no_response' ? 'selected' : '' }}>No Response</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('admission.contacts.status', $contact) }}" class="inline">
                            @csrf @method('PATCH')
                            <select name="outcome" onchange="this.form.submit()"
                                    class="text-xs font-bold border-0 rounded-lg p-1.5 focus:ring-1 focus:ring-indigo-300 outline-none cursor-pointer
                                           {{ $contact->outcome === 'will_join' ? 'bg-blue-100 text-blue-700'
                                              : ($contact->outcome === 'rejected' ? 'bg-red-100 text-red-600'
                                              : 'bg-orange-100 text-orange-600') }}">
                                <option value="pending"   {{ $contact->outcome === 'pending'   ? 'selected' : '' }}>Pending</option>
                                <option value="will_join" {{ $contact->outcome === 'will_join' ? 'selected' : '' }}>Will Join</option>
                                <option value="rejected"  {{ $contact->outcome === 'rejected'  ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        @if($contact->trial)
                            @if($contact->trial->is_expired || $contact->trial->expires_at->isPast())
                            <span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-600 rounded-full">Expired</span>
                            @else
                            <span class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-full">
                                {{ $contact->trial->daysLeft() }}d left
                            </span>
                            @endif
                        @else
                        <span class="text-xs text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admission.contacts.show', $contact) }}"
                           class="inline-flex items-center gap-1 text-xs font-bold text-indigo-500 hover:text-indigo-700 transition">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 256 256"><path d="M224,104a8,8,0,0,1-16,0V59.32l-82.34,82.34a8,8,0,0,1-11.32-11.32L196.68,48H152a8,8,0,0,1,0-16h64a8,8,0,0,1,8,8Zm-40,24a8,8,0,0,0-8,8v72H48V80h72a8,8,0,0,0,0-16H48A16,16,0,0,0,32,80V208a16,16,0,0,0,16,16H176a16,16,0,0,0,16-16V136A8,8,0,0,0,184,128Z"/></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-panel.pagination :paginator="$contacts" />
</div>
@endif
</div>
@endsection
