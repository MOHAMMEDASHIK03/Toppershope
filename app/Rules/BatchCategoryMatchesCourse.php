<?php

namespace App\Rules;

use App\Models\Course;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BatchCategoryMatchesCourse implements ValidationRule
{
    public function __construct(protected ?int $courseId, protected ?int $categoryId)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->courseId || !$this->categoryId) {
            return;
        }

        $course = Course::query()->find($this->courseId);
        if (!$course || (int) $course->category_id !== (int) $this->categoryId) {
            $fail('Batch category must match the master course category.');
        }
    }
}
