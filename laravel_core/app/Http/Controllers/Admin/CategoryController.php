<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use LogsAdminActivity;

    protected string $routeNamePrefix = 'admin.categories';

    protected string $layoutView = 'admin.layouts.app';

    public function index()
    {
        $categories = Category::query()
            ->withCount(['subcategories', 'courses', 'batches'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', [
            'categories' => $categories,
            'routePrefix' => $this->routeNamePrefix,
            'layoutView' => $this->layoutView,
        ]);
    }

    public function create()
    {
        return view('admin.categories.create', [
            'routePrefix' => $this->routeNamePrefix,
            'layoutView' => $this->layoutView,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedCategoryPayload($request);
        $data['slug'] = $this->uniqueCategorySlug(
            $data['slug'] ?? Str::slug($data['name']),
        );

        $category = Category::create($data);
        $this->syncSubcategories($category, $request->input('subcategories', []));

        $this->logAudit('created_category', "Created category: {$category->name}");

        return redirect()->route($this->routeNamePrefix . '.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $category->load('subcategories');

        return view('admin.categories.edit', [
            'category' => $category,
            'routePrefix' => $this->routeNamePrefix,
            'layoutView' => $this->layoutView,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validatedCategoryPayload($request, $category);
        $data['slug'] = $this->uniqueCategorySlug(
            $data['slug'] ?? Str::slug($data['name']),
            $category->id,
        );

        $category->update($data);
        $this->syncSubcategories($category, $request->input('subcategories', []));

        $this->logAudit('updated_category', "Updated category: {$category->name}");

        return redirect()->route($this->routeNamePrefix . '.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->courses()->exists() || $category->batches()->exists()) {
            return back()->with('error', 'Cannot delete category while courses or batches are linked.');
        }

        $name = $category->name;
        $category->delete();

        $this->logAudit('deleted_category', "Deleted category: {$name}");

        return redirect()->route($this->routeNamePrefix . '.index')->with('success', 'Category deleted.');
    }

    public function storeSubcategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = $this->uniqueSubcategorySlug($category, $data['slug'] ?? Str::slug($data['name']));

        $category->subcategories()->create([
            'name' => $data['name'],
            'slug' => $slug,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? ($category->subcategories()->max('sort_order') + 1),
        ]);

        return back()->with('success', 'Subcategory added.');
    }

    public function updateSubcategory(Request $request, Category $category, Subcategory $subcategory)
    {
        if ((int) $subcategory->category_id !== (int) $category->id) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $subcategory->update([
            'name' => $data['name'],
            'slug' => $this->uniqueSubcategorySlug(
                $category,
                $data['slug'] ?? Str::slug($data['name']),
                $subcategory->id
            ),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? $subcategory->sort_order,
        ]);

        return back()->with('success', 'Subcategory updated.');
    }

    public function destroySubcategory(Category $category, Subcategory $subcategory)
    {
        if ((int) $subcategory->category_id !== (int) $category->id) {
            abort(404);
        }

        if ($subcategory->courses()->exists() || $subcategory->batches()->exists()) {
            return back()->with('error', 'Cannot delete subcategory while courses or batches are linked.');
        }

        $subcategory->delete();

        return back()->with('success', 'Subcategory deleted.');
    }

    private function validatedCategoryPayload(Request $request, ?Category $category = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'landing_meta' => 'nullable|array',
            'landing_meta.subtitle' => 'nullable|string',
            'landing_meta.about' => 'nullable|string',
            'landing_meta.color' => 'nullable|string|max:50',
            'landing_meta.icon_url' => 'nullable|string|max:500',
            'landing_meta.hero_bg' => 'nullable|string|max:100',
            'landing_meta.icon_bg' => 'nullable|string|max:100',
            'landing_meta.icon_svg' => 'nullable|string',
            'landing_meta.legacy_course_category' => 'nullable|string|max:100',
            'landing_meta.tags' => 'nullable|array',
            'landing_meta.subjects' => 'nullable|array',
            'landing_meta.features' => 'nullable|array',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['landing_meta'] = array_merge(
            $category->landing_meta ?? [],
            $validated['landing_meta'] ?? []
        );

        return $validated;
    }

    private function syncSubcategories(Category $category, array $rows): void
    {
        foreach ($rows as $row) {
            if (empty($row['name'])) {
                continue;
            }
            if (! empty($row['id'])) {
                $sub = Subcategory::where('category_id', $category->id)->find($row['id']);
                if ($sub) {
                    $sub->update([
                        'name' => $row['name'],
                        'slug' => $this->uniqueSubcategorySlug(
                            $category,
                            $row['slug'] ?? Str::slug($row['name']),
                            $sub->id
                        ),
                        'is_active' => ! empty($row['is_active']),
                        'sort_order' => (int) ($row['sort_order'] ?? $sub->sort_order),
                    ]);
                }

                continue;
            }
            $category->subcategories()->create([
                'name' => $row['name'],
                'slug' => $this->uniqueSubcategorySlug($category, $row['slug'] ?? Str::slug($row['name'])),
                'is_active' => ! empty($row['is_active']),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ]);
        }
    }

    private function uniqueCategorySlug(string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug) ?: 'category';
        $candidate = $base;
        $i = 1;
        while (
            Category::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }

    private function uniqueSubcategorySlug(Category $category, string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug) ?: 'subcategory';
        $candidate = $base;
        $i = 1;
        while (
            Subcategory::where('category_id', $category->id)
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
}
