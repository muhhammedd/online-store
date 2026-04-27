<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $table = "product_variants";

    protected $fillable = [
        "product_id",
        "sku",
        "price",
        "stock",
        "is_active",
    ];

    protected function casts(): array
    {
        return [
            "price" => "decimal:2",
            "stock" => "integer",
            "is_active" => "boolean",
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeOption::class,
            'product_variant_attribute_options',
            'product_variant_id',
            'attribute_option_id'
        )->using(ProductVariantAttributeOption::class)->withTimestamps();
    }
}
