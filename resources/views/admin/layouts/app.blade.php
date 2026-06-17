@extends('layouts.panel.shell', [
    'panelKey' => 'admin',
    'consoleTitle' => "Topper's Hope",
    'consoleSubtitle' => 'Admin Console',
    'guard' => 'admin',
    'userRole' => 'Super Admin',
    'logoutRoute' => 'admin.logout',
])

@section('sidebar-nav')
    @include('components.panel.nav.admin')
@endsection
