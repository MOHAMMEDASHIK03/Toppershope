@extends('layouts.panel.shell', [
    'panelKey' => 'trial',
    'consoleTitle' => 'Trial Access',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => null,
    'userRole' => 'Trial Student',
    'logoutRoute' => 'trial.logout',
])

@push('before-content')
    @isset($trial)
        <div class="mb-4 bg-emerald-600 text-white px-4 py-2.5 rounded-lg text-center text-sm font-semibold shadow-sm">
            You are on a 5-day trial. Access expires in {{ min(5, $trial->daysLeft()) }} day(s). Enrol now to unlock the full course.
        </div>
    @endisset
@endpush

@section('sidebar-nav')
    @include('components.panel.nav.trial')
@endsection
