<?php

namespace App\Http\Controllers\api\category;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {
            $category = Category::create($request->validated());

            return $this->successResponse(
                $category,
                'Category created successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create category',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
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
        try {
            $category->update($request->validated());

            return $this->successResponse(
                $category,
                'Category updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update category',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    // Delete category (cascades to children)
    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $category = Category::find($id);

            if (!$category) {
                return $this->errorResponse(
                    'Category not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $category->delete();

            return $this->successResponse(
                null,
                'Category deleted successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete category',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function sortData(Request $request)
    {
        try {
            $categories = json_decode($request->category_ids);
            if (empty($categories) || !is_array($categories)) {
                return $this->errorResponse(
                    'Invalid input. services_ids must be a non-empty array.',
                    [],
                    ApiStatus::HTTP_422
                );
            }

            foreach ($categories as $index => $id) {
                $category = Category::find($id);
                if ($category) {
                    $category->update(['order' => ($index + 1)]);
                }
            }

            return $this->successResponse(
                null,
                'Category sort order updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update category sort order',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return $this->errorResponse(
                    'Category not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $category->update([
                'is_active' => $request->is_active,
            ]);

            return $this->successResponse(
                $category,
                'Category status updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                'Validation failed',
                $e->errors(),
                ApiStatus::HTTP_422
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to update category status',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }
}
