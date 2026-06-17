<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Rules\SubcategoryBelongsToCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Traits\LogsAdminActivity;

class CourseController extends Controller
{
    use LogsAdminActivity;

    public function create()
    {
        $categories = Category::with('activeSubcategories')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.academic.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
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

        $this->logAudit('created_course', "Created new master course: {$course->name}");

        return redirect()
            ->route('admin.courses.edit', $course->id)
            ->with('success', 'Master course created. Continue configuring details below.');
    }

    public function edit(Course $course)
    {
        $course->load(['batches', 'category', 'subcategory']);
        $categories = Category::with('activeSubcategories')->orderBy('sort_order')->get();
        return view('admin.academic.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'syllabus_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'hero_image' => 'nullable|image|max:5120',
        ]);

        $course->name = $request->title; 
        $course->description = $request->description;
        $course->about = $request->about;
        $course->language = $request->language;
        $course->duration = $request->duration;
        $course->is_published = (bool)($request->input('is_published_toggle', 0));
        
        if ($request->has('what_you_learn')) {
            $course->what_you_learn = is_array($request->what_you_learn) ? $request->what_you_learn : json_decode($request->what_you_learn, true);
        }
        if ($request->has('includes')) {
            $course->includes = is_array($request->includes) ? $request->includes : json_decode($request->includes, true);
        }
        if ($request->has('faculty_json')) {
            $course->faculty = is_array($request->faculty_json) ? $request->faculty_json : json_decode($request->faculty_json, true);
        }

        if ($request->hasFile('syllabus_pdf')) {
            $path = $request->file('syllabus_pdf')->store('syllabuses', 'public');
            $course->syllabus_pdf_path = $path;
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('course_heroes', 'public');
            $course->hero_image = $path;
        }

        $course->save();

        $this->logAudit('updated_course', "Updated master course: {$course->name} (ID: {$course->id})");

        return redirect()->route('admin.courses.edit', $course->id)->with('success', 'Master Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $name = $course->name;
        $course->delete();
        
        $this->logAudit('deleted_course', "Permanently deleted master course: {$name}");

        return redirect()->route('admin.academic.index')->with('success', 'Master Course deleted.');
    }
}
