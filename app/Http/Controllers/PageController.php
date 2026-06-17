<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function category(Category $category, ?Subcategory $subcategory = null)
    {
        if (!$category->is_active) {
            abort(404);
        }

        if ($subcategory && ((int) $subcategory->category_id !== (int) $category->id || !$subcategory->is_active)) {
            abort(404);
        }

        $category->load(['activeSubcategories']);

        $batchQuery = Batch::with(['course', 'subcategory'])
            ->where('category_id', $category->id);

        if ($subcategory) {
            $batchQuery->where('subcategory_id', $subcategory->id);
        }

        $batches = (clone $batchQuery)
            ->where('is_upcoming', false)
            ->take(9)
            ->get();

        $upcomingBatches = (clone $batchQuery)
            ->where('is_upcoming', true)
            ->take(6)
            ->get();

        if ($batches->isEmpty() && $upcomingBatches->isEmpty()) {
            $batches = Batch::with(['course', 'subcategory'])
                ->where('category_id', $category->id)
                ->where('is_upcoming', false)
                ->take(6)
                ->get();
        }

        return view('pages.category', compact('category', 'subcategory', 'batches', 'upcomingBatches'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function courseDetail($slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['batches' => function ($q) {
                $q->with(['category', 'subcategory'])->orderBy('is_upcoming')->orderBy('price');
            }, 'category', 'subcategory'])
            ->firstOrFail();

        $related = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->with('batches')
            ->take(3)
            ->get();

        return view('pages.course-detail', compact('course', 'related'));
    }
}
