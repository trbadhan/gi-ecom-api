<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'detailed_description',
        'delivery_time',
        'warranty',
        'category_id',
        'sku',
        'brand',
        'origin',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Optional: get only the main image
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', 'main');
    }
}
