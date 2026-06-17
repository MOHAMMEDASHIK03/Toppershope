@extends('admin.layouts.app')
@section('title', 'Create Master Course')
@section('page_title', 'Create Master Course')

@section('content')
<x-create-form-layout
    :back-href="route('admin.academic.index')"
    back-label="Back to courses & batches"
    title="New master course"
    subtitle="Add a master course to the academic registry. You can configure landing page content, faculty, and batches after saving."
    :action="route('admin.courses.store')"
    submit-label="Create course"
>
    <x-forms.master-course-fields :categories="$categories" />
</x-create-form-layout>
@endsection
