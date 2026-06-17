@extends('layouts.panel.shell', [
    'panelKey' => 'faculty',
    'consoleTitle' => Auth::user()->isFacultyHead() ? 'Faculty Head Panel' : 'Faculty Portal',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => null,
    'userRole' => Auth::user()->isFacultyHead() ? 'Faculty Head' : 'Faculty',
    'logoutRoute' => 'faculty.logout',
])

@push('panel-extra-styles')
    @include('components.panel.faculty-compat-styles')
@endpush

@section('sidebar-nav')
    @include('components.panel.nav.faculty')
@endsection
