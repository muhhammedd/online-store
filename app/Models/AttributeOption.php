<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeOption extends Model
{
    protected $table = 'attribute_options';
    protected $fillable = [
        'attribute_id',
        'value',
        'sort_order',
    ];
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
    public function productVariants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class)
            ->using(ProductVariantAttributeOption::class)
            ->withTimestamps();
    }
}
