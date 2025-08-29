<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'images',
        'options',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',   // auto-cast JSON to array
        'options' => 'array',  // auto-cast JSON to array
        'is_active' => 'boolean',
    ];

    // relation with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
