<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List all categories with subcategories
    public function index()
    {
        return Category::with(['children' => function ($q) {
            $q->orderBy('name', 'asc');
        }])
        ->whereNull('parent_id')
        ->orderBy('name', 'asc')
        ->get();
    }

    // Store new category or subcategory
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    // Show single category with children
    public function show(Category $category)
    {
        return $category->load('children');
    }

    // Update category
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    // Delete category (cascades to children)
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
