@extends('layouts.ads')
@section('title', 'Edit Campaign')
@section('page_title', 'Edit Campaign: ' . $campaign->title)

@section('content')
@php $editing = true; @endphp
@include('ads.campaigns._form')
@endsection
