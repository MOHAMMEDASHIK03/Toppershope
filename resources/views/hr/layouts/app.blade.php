@extends('layouts.panel.shell', [
    'panelKey' => 'hr',
    'consoleTitle' => 'HR Panel',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => 'hr',
    'userRole' => 'HR Department',
    'logoutRoute' => 'hr.logout',
])

@section('sidebar-nav')
    @include('components.panel.nav.hr')
@endsection
