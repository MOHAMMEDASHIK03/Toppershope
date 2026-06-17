@extends('layouts.faculty')

@section('title', 'Edit Batch')
@section('page_title', 'Edit batch')

@section('content')
<x-create-form-layout
    :back-href="route('faculty.head.batches.index')"
    back-label="Back to batches"
    title="Edit batch"
    :subtitle="'Update settings for ' . $batch->name"
    :action="route('faculty.head.batches.globalUpdate', $batch)"
    method="PUT"
    submit-label="Save changes"
    layout-view="layouts.faculty"
>
    @include('faculty.admin.batches._form', ['batch' => $batch])
</x-create-form-layout>
@endsection
