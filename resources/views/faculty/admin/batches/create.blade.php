@extends('layouts.faculty')

@section('title', 'Create Batch')
@section('page_title', 'Create batch')

@section('content')
<x-create-form-layout
    :back-href="route('faculty.head.batches.index')"
    back-label="Back to batches"
    title="New batch"
    subtitle="Configure pricing, seats, schedule, and category for this cohort."
    :action="route('faculty.head.batches.globalStore')"
    submit-label="Create batch"
    layout-view="layouts.faculty"
>
    @include('faculty.admin.batches._form')
</x-create-form-layout>
@endsection
