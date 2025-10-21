<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_main',
    ];

    /**
     * Relationship: Image belongs to a product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor: Get full URL for image
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
