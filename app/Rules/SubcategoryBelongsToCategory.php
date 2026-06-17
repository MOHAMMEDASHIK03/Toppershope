<?php

namespace App\Rules;

use App\Models\Subcategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SubcategoryBelongsToCategory implements ValidationRule
{
    public function __construct(protected ?int $categoryId)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->categoryId || !$value) {
            return;
        }

        $exists = Subcategory::query()
            ->where('id', $value)
            ->where('category_id', $this->categoryId)
            ->exists();

        if (!$exists) {
            $fail('The selected subcategory does not belong to the chosen category.');
        }
    }
}
