<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ValidatesBatchCategories;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    use ValidatesBatchCategories;

    public function store(Request $request, Course $course)
    {
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
            ->route('admin.courses.edit', $course->id)
            ->with('success', 'Batch "' . $request->name . '" created successfully!');
    }

    public function update(Request $request, Course $course, Batch $batch)
    {
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
            ->route('admin.courses.edit', $course->id)
            ->with('success', 'Batch "' . $batch->name . '" updated successfully!');
    }
}
