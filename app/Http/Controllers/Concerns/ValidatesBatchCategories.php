<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Course;
use App\Rules\BatchCategoryMatchesCourse;
use App\Rules\SubcategoryBelongsToCategory;
use Illuminate\Http\Request;

trait ValidatesBatchCategories
{
    protected function batchCategoryRules(Request $request, Course $course): array
    {
        $categoryId = $request->integer('category_id') ?: (int) $course->category_id;

        return [
            'category_id' => [
                'required',
                'exists:categories,id',
                new BatchCategoryMatchesCourse($course->id, $categoryId),
            ],
            'subcategory_id' => [
                'required',
                'exists:subcategories,id',
                new SubcategoryBelongsToCategory($categoryId),
            ],
        ];
    }

    protected function batchCategoryPayload(Request $request, Course $course): array
    {
        return [
            'category_id' => $request->integer('category_id') ?: (int) $course->category_id,
            'subcategory_id' => $request->integer('subcategory_id') ?: (int) $course->subcategory_id,
        ];
    }
}
