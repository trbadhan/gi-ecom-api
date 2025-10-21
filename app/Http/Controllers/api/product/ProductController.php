<?php

namespace App\Http\Controllers\api\product;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Illuminate\Database\QueryException;
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
        try {
            $slug = $request->slug ?: Str::slug($request->name);
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'short_description' => $request->short_description,
                'detailed_description' => $request->detailed_description,
                'delivery_time' => $request->delivery_time,
                'warranty' => $request->warranty,
                'category_id' => $request->category_id,
                'sku' => $request->sku,
                'brand' => $request->brand,
                'origin' => $request->origin,
                'is_active' => $request->is_active,
            ]);

            return $this->successResponse(
                $product,
                'Product created successfully',
                ApiStatus::HTTP_201
            );
        } catch (QueryException $e) {
            return $this->errorResponse(
                'Database error while creating product',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create product',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function show(Product $product)
    {
        return $product->load('category');
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->errorResponse(
                    'Product not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $slug = $request->slug ?: Str::slug($request->name);

            $product->update([
                'name' => $request->name,
                'slug' => $slug,
                'short_description' => $request->short_description,
                'detailed_description' => $request->detailed_description,
                'delivery_time' => $request->delivery_time,
                'warranty' => $request->warranty,
                'category_id' => $request->category_id,
                'sku' => $request->sku,
                'brand' => $request->brand,
                'origin' => $request->origin,
                'is_active' => $request->is_active,
            ]);

            return $this->successResponse(
                $product->fresh(),
                'Product updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (QueryException $e) {
            return $this->errorResponse(
                'Database error while updating product',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update product',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
