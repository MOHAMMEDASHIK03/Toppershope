@extends('layouts.ads')
@section('title', 'New Campaign')
@section('page_title', 'Create New Campaign')

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('ads.campaigns.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-500 transition-colors mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M224,128a8,8,0,0,1-8,8H59.31l58.35,58.34a8,8,0,0,1-11.32,11.32l-72-72a8,8,0,0,1,0-11.32l72-72a8,8,0,0,1,11.32,11.32L59.31,120H216A8,8,0,0,1,224,128Z"/></svg>
        Back to campaigns
    </a>

    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-slate-800">New campaign</h2>
        <p class="text-sm text-slate-500 mt-1">Configure landing page content, visuals, and conversion settings.</p>
    </div>

    @php $editing = false; $campaign = null; @endphp
    @include('ads.campaigns._form')
</div>
@endsection
