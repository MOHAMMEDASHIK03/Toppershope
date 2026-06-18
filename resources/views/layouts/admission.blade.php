@php
    $admissionUser = Auth::guard('admission')->user();
    $admissionRole = $admissionUser && $admissionUser->role === 'head' ? 'Admissions Head' : 'Admissions Staff';
@endphp
@extends('layouts.panel.shell', [
    'panelKey' => 'admission',
    'consoleTitle' => 'Admissions',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => 'admission',
    'userRole' => $admissionRole,
    'logoutRoute' => 'admission.logout',
])

@push('before-content')
    @if(session('trial_created'))
        @php $tc = session('trial_created'); @endphp
        <div class="mb-4 p-5 bg-indigo-50 border border-indigo-200 rounded-xl">
            <p class="text-indigo-900 font-semibold text-sm mb-3">Trial access issued — share these credentials with the student:</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                    <p class="text-indigo-600 text-xs font-semibold mb-0.5">Login URL</p>
                    <p class="text-sm font-mono font-semibold text-slate-800">{{ url('/trial/login') }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                    <p class="text-indigo-600 text-xs font-semibold mb-0.5">Email</p>
                    <p class="text-sm font-mono font-semibold text-slate-800">{{ $tc['email'] ?? '' }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                    <p class="text-indigo-600 text-xs font-semibold mb-0.5">Password</p>
                    <p class="text-sm font-mono font-semibold text-slate-800">{{ $tc['password'] ?? '' }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                    <p class="text-indigo-600 text-xs font-semibold mb-0.5">Expires</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $tc['expires_at'] ?? '' }}</p>
                </div>
            </div>
        </div>
    @endif
@endpush

@section('sidebar-nav')
    @include('components.panel.nav.admission')
@endsection
