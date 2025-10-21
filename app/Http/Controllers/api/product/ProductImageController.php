<?php

namespace App\Http\Controllers\api\product;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\ApiResponse;
use App\Traits\Paginatable;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    use ApiResponse, Paginatable;
    public function uploadImages(StoreProductImageRequest $request)
    {
        try {
            $product = Product::find($request->product_id);

            if (!$product) {
                return $this->errorResponse(
                    'Product not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $uploadedImages = [];

            // 🔹 Handle main image
            if ($request->hasFile('main_image')) {
                $oldMain = $product->images()->where('is_main', 'main')->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->image_path);
                    $oldMain->delete();
                }

                $mainFile = $request->file('main_image');
                if ($mainFile->isValid()) {
                    $path = $mainFile->store('products', 'public');
                    $mainImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => 'main',
                    ]);
                    $mainImage->url = asset('storage/' . $mainImage->image_path);
                    $uploadedImages[] = $mainImage;
                }
            }

            // 🔹 Handle other images
            $otherFiles = $request->file('other_images');
            if ($otherFiles) {
                if (!is_array($otherFiles)) {
                    $otherFiles = [$otherFiles]; // single file case
                }

                foreach ($otherFiles as $file) {
                    if (!$file || !$file->isValid()) continue;

                    $path = $file->store('products', 'public');
                    $otherImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => 'other',
                    ]);
                    $otherImage->url = asset('storage/' . $otherImage->image_path);
                    $uploadedImages[] = $otherImage;
                }
            }

            if (empty($uploadedImages)) {
                return $this->errorResponse(
                    'No images uploaded',
                    [],
                    ApiStatus::HTTP_422
                );
            }

            return $this->successResponse(
                $uploadedImages,
                'Images uploaded successfully',
                ApiStatus::HTTP_201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to upload images',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function editImages(UpdateProductImageRequest $request)
    {
        try {
            $product = Product::find($request->product_id);
            if (!$product) {
                return $this->errorResponse(
                    'Product not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            $updatedImages = [];

            //Replace main image
            if ($request->hasFile('main_image')) {
                $oldMain = $product->images()->where('is_main', 'main')->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->image_path);
                    $oldMain->delete();
                }

                $file = $request->file('main_image');
                if ($file->isValid()) {
                    $path = $file->store('products', 'public');
                    $mainImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => 'main',
                    ]);
                    $mainImage->url = asset('storage/' . $mainImage->image_path);
                    $updatedImages[] = $mainImage;
                }
            }

            //Delete specific other images
            if ($request->filled('delete_other_images')) {
                foreach ($request->delete_other_images as $imageId) {
                    $other = ProductImage::find($imageId);
                    if ($other && $other->is_main == 'other') {
                        Storage::disk('public')->delete($other->image_path);
                        $other->delete();
                    }
                }
            }

            //Add new other images
            $otherFiles = $request->file('other_images');
            if ($otherFiles) {
                if (!is_array($otherFiles)) $otherFiles = [$otherFiles];

                foreach ($otherFiles as $file) {
                    if (!$file || !$file->isValid()) continue;

                    $path = $file->store('products', 'public');
                    $otherImage = ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => 'other',
                    ]);
                    $otherImage->url = asset('storage/' . $otherImage->image_path);
                    $updatedImages[] = $otherImage;
                }
            }

            //Return all images for product
            $allImages = $product->images()->get()->map(function ($img) {
                $img->url = asset('storage/' . $img->image_path);
                return $img;
            });

            return $this->successResponse(
                $allImages,
                'Product images updated successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update images',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }

    public function deleteImage(ProductImage $image)
    {
        try {
            // Delete image file from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete database record
            $image->delete();

            return $this->successResponse(
                null,
                'Image deleted successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete image',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }
    }
}
