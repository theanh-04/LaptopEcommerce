<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laptop extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'brand',
        'processor', 'ram', 'storage', 'display', 'graphics',
        'price', 'stock', 'image', 'sku', 'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
