<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'price', 'stock', 'sku', 'is_default', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helpful: variant -> category (through product)
    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            Product::class,
            'id',          // Foreign key on products...
            'id',          // Foreign key on categories...
            'product_id',  // Local key on variants...
            'category_id'  // Local key on products...
        );
    }
}