@extends('layouts.faculty')

@section('title', 'Assign Faculty')
@section('page_title', 'Assign faculty to course')

@section('content')
<x-create-form-layout
    :back-href="route('faculty.head.faculties.index')"
    back-label="Back to faculty management"
    title="Assign faculty to course"
    subtitle="Link an instructor to a master course so they can manage content in their LMS panel."
    :action="route('faculty.head.faculties.assign')"
    submit-label="Assign now"
    layout-view="layouts.faculty"
>
    @php
        $inputClass = 'w-full rounded-xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm py-2.5 px-3 outline-none';
        $selectedUser = old('user_id', request('user_id'));
    @endphp

    <div class="space-y-5">
        <div>
            <label for="user_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Faculty member <span class="text-red-500">*</span></label>
            <select name="user_id" id="user_id" required class="{{ $inputClass }}">
                <option value="">— Select faculty —</option>
                @foreach($facultyUsers as $fac)
                    <option value="{{ $fac->id }}" @selected($selectedUser == $fac->id)>{{ $fac->name }} ({{ $fac->email }})</option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="course_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Course to assign <span class="text-red-500">*</span></label>
            <select name="course_id" id="course_id" required class="{{ $inputClass }}">
                <option value="">— Select course —</option>
                @foreach($allCourses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
                        {{ $course->name }}@if($course->category) ({{ $course->category->name }})@endif
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1.5">The faculty member will get access to manage this course&rsquo;s content, batches, and teaching tools.</p>
            @error('course_id')
                <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
            @enderror
        </div>
    </div>
</x-create-form-layout>
@endsection
