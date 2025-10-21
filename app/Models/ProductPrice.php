<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'default_price',
        'variant_prices',
    ];

    protected $casts = [
        'variant_prices' => 'array', // automatically casts JSON <-> array
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
