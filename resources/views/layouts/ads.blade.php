@php
    $adsUser = Auth::guard('ads')->user();
    $adsRole = $adsUser && $adsUser->role === 'ads_head' ? 'Ads Head' : 'Ads Manager';
@endphp
@extends('layouts.panel.shell', [
    'panelKey' => 'ads',
    'consoleTitle' => 'Ads Manager',
    'consoleSubtitle' => "Topper's Hope",
    'guard' => 'ads',
    'userRole' => $adsRole,
    'logoutRoute' => 'ads.logout',
])

@section('sidebar-nav')
    @include('components.panel.nav.ads')
@endsection
