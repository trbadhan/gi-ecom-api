<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    // List all categories with subcategories
    public function index()
    {
        $categories = Category::with(['children' => function ($query) {
            $query->orderBy('order')->orderBy('name');
        }])
        ->whereNull('parent_id')
        ->orderBy('order')
        ->orderBy('name')
        ->get();
    
        return response()->json($categories);
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
