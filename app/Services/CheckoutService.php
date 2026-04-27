<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutService
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function createOrder(Cart $cart, array $validated): Order
    {
        $cart->loadMissing('items.product', 'items.productVariant.attributeOptions');

        if ($cart->items->isEmpty()) {
            throw new RuntimeException('Your cart is empty.');
        }

        return DB::transaction(function () use ($cart, $validated) {
            $totals = $this->cartService->totals($cart);

            foreach ($cart->items as $item) {
                /** @var ProductVariant|null $variant */
                $variant = $item->productVariant ? $item->productVariant()->lockForUpdate()->first() : null;

                if ($variant && $item->quantity > $variant->stock) {
                    throw new RuntimeException("Not enough stock for {$item->product->name}.");
                }
            }

            $order = Order::query()->create([
                'user_id' => $cart->user_id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'shipping_cost' => $totals['shipping'],
                'discount' => $totals['discount'],
                'total' => $totals['total'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'country' => $validated['country'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'address2' => $validated['address2'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $variantSnapshot = $item->productVariant
                    ? $item->productVariant->attributeOptions->map(
                        fn ($option) => $option->attribute->name . ': ' . $option->value
                    )->implode(', ')
                    : null;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name_snapshot' => $item->product->name,
                    'variant_snapshot' => $variantSnapshot,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'unit_discount' => $item->unit_discount,
                    'total_price' => $item->total_price,
                ]);

                if ($item->productVariant) {
                    $item->productVariant->decrement('stock', $item->quantity);
                }
            }

            $cart->update(['status' => 'converted']);

            return $order->fresh('items');
        });
    }

    protected function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
    }
}
