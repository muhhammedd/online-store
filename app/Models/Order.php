<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        "user_id",
        "customer_name",
        "email",
        "phone",
        "address",
        "city",
        "country",
        "state",
        "address2",
        "order_number",
        "shipping_cost",
        "discount",
        "total",
        "payment_method",
        "payment_status",
        "status",
        "notes"
    ];

    protected function casts(): array
    {
        return [
            "shipping_cost" => "decimal:2",
            "discount" => "decimal:2",
            "total" => "decimal:2",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
