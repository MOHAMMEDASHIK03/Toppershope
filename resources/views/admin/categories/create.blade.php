@extends($layoutView ?? 'admin.layouts.app')
@section('title', 'Create Category')
@section('page_header', 'Create Category')
@section('page_title', 'Create Category')

@section('content')
<x-create-form-layout
    :back-href="route($routePrefix . '.index')"
    back-label="Back to categories"
    title="New exam category"
    subtitle="Add a category for organizing master courses and batches. Subcategories can be added after saving."
    :action="route($routePrefix . '.store')"
    submit-label="Create category"
    :layout-view="$layoutView ?? null"
>
    @include('admin.categories._form')
</x-create-form-layout>
@endsection
