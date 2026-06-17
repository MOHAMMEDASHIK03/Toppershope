@extends('layouts.panel.shell', [
    'panelKey' => 'student',
    'consoleTitle' => "Topper's Hope",
    'consoleSubtitle' => 'Student Learning',
    'guard' => null,
    'userRole' => ucfirst(auth()->user()->target_exam ?? 'Student'),
    'logoutRoute' => 'logout',
])

@section('sidebar-nav')
    @include('components.panel.nav.student')
@endsection
