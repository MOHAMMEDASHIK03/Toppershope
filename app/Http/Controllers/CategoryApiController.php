<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->categoryService->apiPayload(),
        ]);
    }

    public function subcategories(Category $category): JsonResponse
    {
        $items = $this->categoryService
            ->subcategoriesForCategory($category->id, activeOnly: true)
            ->map(fn ($sub) => [
                'id' => $sub->id,
                'name' => $sub->name,
                'slug' => $sub->slug,
            ]);

        return response()->json(['data' => $items]);
    }
}
