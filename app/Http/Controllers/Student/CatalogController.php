<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subcategory;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(Request $request, CategoryService $categoryService)
    {
        $categorySlug = $request->query('category');
        $subcategorySlug = $request->query('subcategory');
        $search = $request->query('search');

        $query = Course::where('is_published', true)
            ->with(['category', 'subcategory'])
            ->withCount('batches');

        $activeCategory = null;
        $activeSubcategory = null;

        if ($categorySlug && $categorySlug !== 'all') {
            $activeCategory = Category::where('slug', $categorySlug)->where('is_active', true)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($subcategorySlug && $activeCategory) {
            $activeSubcategory = Subcategory::where('category_id', $activeCategory->id)
                ->where('slug', $subcategorySlug)
                ->where('is_active', true)
                ->first();
            if ($activeSubcategory) {
                $query->where('subcategory_id', $activeSubcategory->id);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->orderByDesc('created_at')->get();

        $categories = $categoryService->activeCategoriesWithSubcategories();

        return view('student.catalog.index', compact(
            'courses',
            'categories',
            'categorySlug',
            'subcategorySlug',
            'activeCategory',
            'activeSubcategory',
            'search'
        ));
    }

    public function show($slug)
    {
        $course = Course::with([
            'category',
            'subcategory',
            'batches' => function ($q) {
                $q->with(['category', 'subcategory'])
                    ->where('status', '!=', 'closed')
                    ->orderBy('price');
            },
            'subjects.chapters.units',
        ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $enrolledBatchIds = [];
        if (Auth::check()) {
            $enrolledBatchIds = Auth::user()->enrollments()
                ->pluck('batch_id')
                ->toArray();
        }

        return view('student.catalog.show', compact('course', 'enrolledBatchIds'));
    }
}
