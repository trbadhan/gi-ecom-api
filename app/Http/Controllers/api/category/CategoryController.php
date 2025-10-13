<?php

namespace App\Http\Controllers\api\category;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse, Paginatable;

    // List all categories with subcategories
    public function index(Request $request)
    {
        try {
            $per_page = $request->input('per_page', 10);
            $current_page = $request->input('page', 1);

            $query = Category::with(['children' => function ($query) {
                $query->orderBy('order')->orderBy('name');
            }])
                ->whereNull('parent_id')
                ->orderBy('order')
                ->orderBy('name');

            // 🔍 Apply name filter
            if ($request->filled('name')) {
                $name = $request->name;
                $query->where('name', 'like', "%{$name}%");
            }

            // 📄 Pagination
            $categories = $query->paginate($per_page, ['*'], 'page', $current_page);

            return $this->successResponse(
                $categories->items(),
                'Categories retrieved successfully',
                ApiStatus::HTTP_200,
                $this->paginationMeta($categories)
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to fetch categories',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }


    // Store new category or subcategory
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201);
    }

    // Show single category with children
    public function show(Category $category)
    {
        return $category->load('children');
    }

    // Update category
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json($category);
    }

    // Delete category (cascades to children)
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
