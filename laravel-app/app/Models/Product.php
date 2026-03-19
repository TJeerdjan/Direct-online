<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'client_id', 'title', 'slug', 'description', 'price', 'currency',
        'category', 'brand', 'sku', 'stock_quantity', 'weight', 'variants',
        'image_id', 'is_available', 'is_visible', 'sort_order'
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_available' => 'boolean',
        'is_visible' => 'boolean',
        'variants' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
