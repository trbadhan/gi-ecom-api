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
use Illuminate\Support\Facades\DB;

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
                ->where('parent_id', 0)
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
        try {
            $parentId = (int) $request->input('parent_id', 0);

            $category = DB::transaction(function () use ($request, $parentId) {
                $maxOrder = Category::query()
                    ->where('parent_id', $parentId)
                    ->lockForUpdate()
                    ->max('order');

                return Category::create([
                    'name'      => (string) $request->input('name'),
                    'parent_id' => $parentId,
                    'is_active' => true,
                    'order'     => (int)($maxOrder ?? 0) + 1,
                ]);
            });

            return $this->successResponse($category, 'Category created successfully', ApiStatus::HTTP_200);
        } catch (\Throwable $e) {
            return $this->errorResponse('Failed to create category', ['exception' => $e->getMessage()], ApiStatus::HTTP_500);
        }
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
