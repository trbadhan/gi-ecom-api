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
        $categories = Category::with(['children' => function ($query) {
            $query->orderBy('order');
        }])
        ->whereNull('parent_id')
        ->orderBy('order')
        ->get();
    
        return response()->json($categories);
    }

    // Store new category or subcategory
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);
    
        // find max order under same parent
        $maxOrder = Category::where('parent_id', $validated['parent_id'] ?? null)->max('order');
        $validated['order'] = $maxOrder ? $maxOrder + 1 : 1;
    
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

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:categories,id',
            'items.*.order' => 'required|integer',
            'items.*.parent_id' => 'nullable|exists:categories,id',
        ]);

        foreach ($validated['items'] as $item) {
            $category = Category::find($item['id']);
            if ($category) {
                // Apply new parent + order
                $category->update([
                    'parent_id' => $item['parent_id'] ?? null,
                    'order' => $item['order'],
                ]);
            }
        }

        return response()->json(['message' => 'Categories reordered successfully']);
    }

    // Delete category (cascades to children)
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
