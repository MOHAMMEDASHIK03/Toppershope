<?php

namespace App\Http\Controllers\Faculty\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Rules\SubcategoryBelongsToCategory;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Faculty Head check happens via middleware, but double safety
        if (!auth()->user()->isFacultyHead()) {
            abort(403);
        }

        $courses = Course::with(['category', 'subcategory'])->withCount(['batches', 'subjects'])->latest()->get();
        $categories = Category::with('activeSubcategories')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('faculty.admin.courses.index', compact('courses', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'exists:subcategories,id', new SubcategoryBelongsToCategory($request->integer('category_id'))],
        ]);

        $course = new Course();
        $course->name = $request->title; 
        $course->slug = Str::slug($request->title) . '-' . uniqid();
        $course->description = $request->description;
        $course->category_id = $request->category_id;
        $course->subcategory_id = $request->subcategory_id;
        $course->is_published = false;
        $course->save();

        return redirect()->route('faculty.head.courses.index')->with('success', 'Master Course created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);
        // Eager load batches for the batches tab
        $course->load(['batches', 'category', 'subcategory']);
        $categories = Category::with('activeSubcategories')->orderBy('sort_order')->get();
        return view('faculty.admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'syllabus_pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'hero_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        $course->name = $request->title; 
        $course->description = $request->description;
        $course->about = $request->about;
        $course->language = $request->language;
        $course->duration = $request->duration;
        // The header toggle sends a hidden field 'is_published_toggle' with value '1' or '0'
        $course->is_published = (bool)($request->input('is_published_toggle', 0));
        
        // Handle JSON arrays (What you learn, Includes, Faculty)
        // These will come in as JSON strings from Alpine or as arrays.
        if ($request->has('what_you_learn')) {
            $course->what_you_learn = is_array($request->what_you_learn) ? $request->what_you_learn : json_decode($request->what_you_learn, true);
        }
        if ($request->has('includes')) {
            $course->includes = is_array($request->includes) ? $request->includes : json_decode($request->includes, true);
        }
        if ($request->has('faculty_json')) {
            $course->faculty = is_array($request->faculty_json) ? $request->faculty_json : json_decode($request->faculty_json, true);
        }

        // Handle File Uploads
        if ($request->hasFile('syllabus_pdf')) {
            $path = $request->file('syllabus_pdf')->store('syllabuses', 'public');
            $course->syllabus_pdf_path = $path;
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('course_heroes', 'public');
            $course->hero_image = $path;
        }

        $course->save();

        return redirect()->route('faculty.head.courses.edit', $course->id)->with('success', 'Master Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);
        $course->delete(); // Soft delete via model
        return redirect()->route('faculty.head.courses.index')->with('success', 'Master Course deleted.');
    }
}
