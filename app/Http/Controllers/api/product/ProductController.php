<?php

namespace App\Http\Controllers\api\product;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse, Paginatable;

    public function index(Request $request)
    {
        try {
            $per_page = $request->input('per_page', 10);
            $current_page = $request->input('page', 1);

            $query = Product::with('category')->orderBy('id');

            // 🔍 Apply name filter
            if ($request->filled('name')) {
                $name = $request->name;
                $query->where('name', 'like', "%{$name}%");
            }

            // 📄 Pagination
            $categories = $query->paginate($per_page, ['*'], 'page', $current_page);

            return $this->successResponse(
                $categories->items(),
                'Product retrieved successfully',
                ApiStatus::HTTP_200,
                $this->paginationMeta($categories)
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to fetch Product',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
