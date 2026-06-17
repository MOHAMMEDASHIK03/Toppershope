@extends('admin.layouts.app')
@section('title', 'Master Course Configurator: ' . $course->name)
@section('page_title', 'Master Course Configurator')

@section('content')
    <x-academic.mega-course-configurator
        :course="$course"
        :categories="$categories"
        :back-href="route('admin.academic.index')"
        back-label="Back to courses & batches"
        :form-action="route('admin.courses.update', $course->id)"
        :batch-store-url="route('admin.courses.batches.store', $course->id)"
        :batch-update-url-prefix="url('/admin/courses/' . $course->id . '/batches')"
    />
@endsection
