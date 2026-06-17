@extends('layouts.faculty')
@section('title', 'Master Course Configurator: ' . $course->name)
@section('page_title', 'Master Course Configurator')

@section('content')
    <x-academic.mega-course-configurator
        :course="$course"
        :categories="$categories"
        :back-href="route('faculty.head.courses.index')"
        back-label="Back to courses"
        :form-action="route('faculty.head.courses.update', $course->id)"
        :batch-store-url="route('faculty.head.batches.store', $course->id)"
        :batch-update-url-prefix="url('/faculty/head/courses/' . $course->id . '/batches')"
        :extended-batches="true"
    />
@endsection
