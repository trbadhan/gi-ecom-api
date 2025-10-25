<?php

namespace App\Http\Controllers\api\product;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Requests\UpdateProductPriceRequest;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    use ApiResponse, Paginatable;
    public function store(StoreProductPriceRequest $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);

            $price = ProductPrice::create([
                'product_id' => $product->id,
                'default_price' => $request->default_price,
                'variant_prices' => json_encode($request->variant_prices),
            ]);

            return $this->successResponse(
                $price,
                'Product price added successfully',
                ApiStatus::HTTP_201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to add product price',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function update(UpdateProductPriceRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $price = ProductPrice::find($id);

            if (!$price) {
                return $this->errorResponse(
                    'Product price not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $price->update([
                'default_price' => $request->default_price,
                'variant_prices' => json_encode($request->variant_prices ?? []),
            ]);

            DB::commit();

            return $this->successResponse(
                $price,
                'Product price updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse(
                'Failed to update product price',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
			
        }
    }
}
