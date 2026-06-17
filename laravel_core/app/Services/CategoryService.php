<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Course;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function activeCategoriesWithSubcategories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->with(['activeSubcategories'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function allCategoriesForSelect(): Collection
    {
        return Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'is_active']);
    }

    public function subcategoriesForCategory(int $categoryId, bool $activeOnly = true): Collection
    {
        $query = Subcategory::query()
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get(['id', 'category_id', 'name', 'slug', 'is_active']);
    }

    public function resolveCategoryBySlug(string $slug): ?Category
    {
        return Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function resolveSubcategory(Category $category, string $subcategorySlug): ?Subcategory
    {
        return Subcategory::query()
            ->where('category_id', $category->id)
            ->where('slug', $subcategorySlug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @throws ValidationException
     */
    public function validateBatchCategoryPair(Course $course, int $categoryId, int $subcategoryId): void
    {
        if ((int) $course->category_id !== $categoryId) {
            throw ValidationException::withMessages([
                'category_id' => 'Batch category must match the master course category.',
            ]);
        }

        $subcategory = Subcategory::query()->find($subcategoryId);
        if (!$subcategory || (int) $subcategory->category_id !== $categoryId) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'Selected subcategory does not belong to the chosen category.',
            ]);
        }
    }

    /**
     * Map legacy course.category strings (and optional slug hints) to category IDs.
     */
    public function categoryIdFromLegacy(string $legacy, ?string $slugHint = null): ?int
    {
        if ($slugHint) {
            $bySlug = Category::where('slug', $slugHint)->value('id');
            if ($bySlug) {
                return (int) $bySlug;
            }
        }

        $byMeta = Category::query()
            ->where('landing_meta->legacy_course_category', $legacy)
            ->value('id');

        if ($byMeta) {
            return (int) $byMeta;
        }

        $slugMap = [
            'School Boards' => 'school-boards',
            'UPSC' => 'upsc',
            'Govt Jobs' => 'govt-job-exams',
            'Computer Skills' => 'computer-skills',
            'Language' => 'language-courses',
            'NDA' => 'nda',
        ];

        $slug = $slugMap[$legacy] ?? Str::slug($legacy);

        $id = Category::where('slug', $slug)->value('id');

        return $id ? (int) $id : null;
    }

    /**
     * Pick a subcategory for batch seeding from batch name heuristics.
     */
    public function inferSubcategoryId(Category $category, string $batchName, ?string $hint = null): ?int
    {
        $category->loadMissing('subcategories');

        if ($hint) {
            $sub = $category->subcategories->first(
                fn ($s) => Str::slug($s->name) === Str::slug($hint) || strcasecmp($s->name, $hint) === 0
            );
            if ($sub) {
                return $sub->id;
            }
        }

        $name = strtolower($batchName);
        $rules = [
            'dropper' => ['dropper', '12+'],
            'class-11' => ['class 11', '11th', 'ignite'],
            'class-12' => ['class 12', '12th'],
            'class-10' => ['class 10', '10th'],
            'class-9' => ['class 9', '9th'],
            'class-8' => ['class 8', '8th'],
            'cbse' => ['cbse'],
            'icse' => ['icse'],
            'banking' => ['bank', 'ssc'],
            'nda' => ['nda'],
            'airforce' => ['airforce', 'x/y'],
            'python' => ['python'],
            'english-speaking' => ['english', 'speaking'],
        ];

        foreach ($rules as $slug => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($name, $needle)) {
                    $sub = $category->subcategories->firstWhere('slug', $slug)
                        ?? $category->subcategories->first(fn ($s) => str_contains(strtolower($s->name), $needle));
                    if ($sub) {
                        return $sub->id;
                    }
                }
            }
        }

        return $category->subcategories->sortBy('sort_order')->first()?->id;
    }

    public function apiPayload(): array
    {
        return $this->activeCategoriesWithSubcategories()
            ->map(fn (Category $cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'subcategories' => $cat->activeSubcategories->map(fn (Subcategory $sub) => [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                ])->values()->all(),
            ])
            ->values()
            ->all();
    }
}
