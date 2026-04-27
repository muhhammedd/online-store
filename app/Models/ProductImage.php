<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = "product_images";
    protected $fillable = [
        "product_id",
        "image_url",
        "alt_text",
        "sort_order",
        "is_primary"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getImageUrlAttribute()
    {
        if (! $this->attributes['image_url']) {
            return null;
        }

        if (filter_var($this->attributes['image_url'], FILTER_VALIDATE_URL)) {
            return $this->attributes['image_url'];
        }

        return asset('storage/' . ltrim($this->attributes['image_url'], '/'));
    }
}
