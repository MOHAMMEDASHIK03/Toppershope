<?php

namespace App\Http\Controllers\Faculty\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Http\Controllers\Concerns\ValidatesBatchCategories;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AdminBatchController extends Controller
{
    use ValidatesBatchCategories, PaginatesListings;
    /**
     * Global Batches Index page — shows all batches across all courses.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $query = Batch::with(['course', 'category', 'subcategory'])->orderBy('batches.created_at', 'desc');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('status')) {
            $query->where('batches.status', $request->status);
        }

        $batches = $this->paginateListing($query, $request);

        $courses         = Course::with('category')->orderBy('name')->get(['id', 'name', 'category_id']);
        $categories      = Category::with('activeSubcategories')->orderBy('sort_order')->get();
        $activeBatchCount = Batch::where('status', 'active')->count();
        $upcomingCount   = Batch::where('is_upcoming', true)->count();
        $totalEnrolled   = Enrollment::where('enrollments.status', 'active')->count();

        return view('faculty.admin.batches.index', compact(
            'batches', 'courses', 'categories', 'activeBatchCount', 'upcomingCount', 'totalEnrolled'
        ));
    }

    public function create()
    {
        if (!auth()->user()->isFacultyHead()) {
            abort(403);
        }

        $courses = Course::with('category')->orderBy('name')->get(['id', 'name', 'category_id']);
        $categories = Category::with('activeSubcategories')->orderBy('sort_order')->get();

        return view('faculty.admin.batches.create', compact('courses', 'categories'));
    }

    public function edit(Batch $batch)
    {
        if (!auth()->user()->isFacultyHead()) {
            abort(403);
        }

        $batch->load(['course', 'category', 'subcategory']);
        $courses = Course::with('category')->orderBy('name')->get(['id', 'name', 'category_id']);
        $categories = Category::with('activeSubcategories')->orderBy('sort_order')->get();

        return view('faculty.admin.batches.edit', compact('batch', 'courses', 'categories'));
    }

    /**
     * Store a new batch from the global Batches page (course_id picked via dropdown).
     */
    public function globalStore(Request $request)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $course = Course::findOrFail($request->course_id);

        $request->validate(array_merge([
            'course_id'      => 'required|exists:courses,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'total_seats'    => 'nullable|integer|min:1',
            'start_date'     => 'nullable|date',
            'mode'           => 'nullable|string|max:50',
            'status'         => 'nullable|string|max:50',
        ], $this->batchCategoryRules($request, $course)));

        Batch::create(array_merge([
            'course_id'      => $request->course_id,
            'name'           => $request->name,
            'price'          => $request->price,
            'original_price' => $request->original_price ?? null,
            'total_seats'    => $request->total_seats ?? 100,
            'filled_seats'   => 0,
            'start_date'     => $request->start_date ?? null,
            'mode'           => $request->mode ?? 'Online Live',
            'status'         => $request->status ?? 'active',
            'is_upcoming'    => $request->boolean('is_upcoming'),
        ], $this->batchCategoryPayload($request, $course)));

        return redirect()
            ->route('faculty.head.batches.index')
            ->with('success', 'Batch "' . $request->name . '" created successfully!');
    }

    /**
     * Global update from the Batches index page.
     */
    public function globalUpdate(Request $request, Batch $batch)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $course = Course::findOrFail($request->course_id);

        $request->validate(array_merge([
            'course_id'      => 'required|exists:courses,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'total_seats'    => 'nullable|integer|min:1',
            'start_date'     => 'nullable|date',
            'mode'           => 'nullable|string|max:50',
            'status'         => 'nullable|string|max:50',
        ], $this->batchCategoryRules($request, $course)));

        $batch->update(array_merge([
            'course_id'      => $request->course_id,
            'name'           => $request->name,
            'price'          => $request->price,
            'original_price' => $request->original_price ?? null,
            'total_seats'    => $request->total_seats ?? 100,
            'start_date'     => $request->start_date ?? null,
            'mode'           => $request->mode ?? 'Online Live',
            'status'         => $request->status ?? 'active',
            'is_upcoming'    => $request->boolean('is_upcoming'),
        ], $this->batchCategoryPayload($request, $course)));

        return redirect()
            ->route('faculty.head.batches.index')
            ->with('success', 'Batch "' . $batch->name . '" updated successfully!');
    }

    /**
     * Delete batch.
     */
    public function destroy(Batch $batch)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);
        $name = $batch->name;
        $batch->delete();

        return redirect()
            ->route('faculty.head.batches.index')
            ->with('success', '"' . $name . '" deleted successfully.');
    }

    // ---- Per-course methods (used from the Course edit page) ----

    public function store(Request $request, Course $course)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);

        $request->validate(array_merge([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'total_seats'    => 'nullable|integer|min:1',
            'start_date'     => 'nullable|date',
            'mode'           => 'nullable|string|max:50',
            'status'         => 'nullable|string|max:50',
        ], $this->batchCategoryRules($request, $course)));

        $course->batches()->create(array_merge([
            'name'           => $request->name,
            'price'          => $request->price,
            'original_price' => $request->original_price ?? null,
            'total_seats'    => $request->total_seats ?? 100,
            'filled_seats'   => 0,
            'start_date'     => $request->start_date ?? null,
            'mode'           => $request->mode ?? 'Online Live',
            'status'         => $request->status ?? 'active',
            'is_upcoming'    => $request->boolean('is_upcoming'),
        ], $this->batchCategoryPayload($request, $course)));

        return redirect()
            ->route('faculty.head.courses.edit', $course->id)
            ->with('success', 'Batch "' . $request->name . '" created successfully!');
    }

    public function update(Request $request, Course $course, Batch $batch)
    {
        if (!auth()->user()->isFacultyHead()) abort(403);
        if ($batch->course_id !== $course->id) abort(403);

        $request->validate(array_merge([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'total_seats'    => 'nullable|integer|min:1',
            'start_date'     => 'nullable|date',
            'mode'           => 'nullable|string|max:50',
            'status'         => 'nullable|string|max:50',
        ], $this->batchCategoryRules($request, $course)));

        $batch->update(array_merge([
            'name'           => $request->name,
            'price'          => $request->price,
            'original_price' => $request->original_price ?? null,
            'total_seats'    => $request->total_seats ?? 100,
            'start_date'     => $request->start_date ?? null,
            'mode'           => $request->mode ?? 'Online Live',
            'status'         => $request->status ?? 'active',
            'is_upcoming'    => $request->boolean('is_upcoming'),
        ], $this->batchCategoryPayload($request, $course)));

        return redirect()
            ->route('faculty.head.courses.edit', $course->id)
            ->with('success', 'Batch "' . $batch->name . '" updated successfully!');
    }
}
