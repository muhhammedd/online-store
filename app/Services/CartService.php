<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    public function getActiveCart(Request $request): Cart
    {
        $user = $request->user();

        if ($user) {
            $cart = $this->getOrCreateUserCart($request, $user);
            $request->session()->put('cart_id', $cart->id);

            return $cart;
        }

        $cart = $this->getOrCreateGuestCart($request);
        $request->session()->put('cart_id', $cart->id);

        return $cart;
    }

    public function addItem(Request $request, Product $product, ?ProductVariant $variant, int $quantity): Cart
    {
        $cart = $this->getActiveCart($request);

        if ($variant && $variant->product_id !== $product->id) {
            throw new RuntimeException('The selected variant does not belong to this product.');
        }

        if ($product->variants()->exists() && ! $variant) {
            throw new RuntimeException('A product variant is required for this product.');
        }

        $unitPrice = $variant ? (float) $variant->price : (float) $product->effective_price;
        $unitDiscount = max(0, (float) $product->base_price - $unitPrice);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $quantity;

        $this->assertStock($variant, $newQuantity);

        $payload = [
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity' => $newQuantity,
            'unit_price' => $unitPrice,
            'unit_discount' => $unitDiscount,
            'total_price' => $unitPrice * $newQuantity,
        ];

        if ($item) {
            $item->update($payload);
        } else {
            $cart->items()->create($payload);
        }

        return $cart->fresh('items.product', 'items.productVariant.attributeOptions');
    }

    public function updateItemQuantity(Request $request, CartItem $item, int $quantity): Cart
    {
        $cart = $this->getActiveCart($request);
        $this->assertCartOwnership($cart, $item);

        $variant = $item->productVariant;
        $this->assertStock($variant, $quantity);

        $item->update([
            'quantity' => $quantity,
            'total_price' => (float) $item->unit_price * $quantity,
        ]);

        return $cart->fresh('items.product', 'items.productVariant.attributeOptions');
    }

    public function removeItem(Request $request, CartItem $item): void
    {
        $cart = $this->getActiveCart($request);
        $this->assertCartOwnership($cart, $item);
        $item->delete();
    }

    public function totals(Cart $cart): array
    {
        $cart->loadMissing('items');

        $subtotal = (float) $cart->items->sum(
            fn (CartItem $item) => ((float) $item->unit_price + (float) $item->unit_discount) * $item->quantity
        );
        $discount = (float) $cart->items->sum(fn (CartItem $item) => (float) $item->unit_discount * $item->quantity);
        $shipping = 0.0;
        $total = (float) $cart->items->sum('total_price') + $shipping;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
            'items_count' => (int) $cart->items->sum('quantity'),
        ];
    }

    protected function getOrCreateUserCart(Request $request, Authenticatable $user): Cart
    {
        $sessionId = $request->session()->getId();

        return DB::transaction(function () use ($user, $sessionId) {
            $cart = Cart::query()
                ->firstOrCreate(
                    ['user_id' => $user->getAuthIdentifier(), 'status' => 'active'],
                    ['session_id' => $sessionId]
                );

            $guestCart = Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $sessionId)
                ->where('status', 'active')
                ->where('id', '!=', $cart->id)
                ->with('items')
                ->first();

            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $existing = $cart->items()
                        ->where('product_id', $item->product_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();

                    if ($existing) {
                        $quantity = $existing->quantity + $item->quantity;
                        $existing->update([
                            'quantity' => $quantity,
                            'total_price' => ((float) $existing->unit_price - (float) $existing->unit_discount) * $quantity,
                        ]);
                        $item->delete();
                    } else {
                        $item->update(['cart_id' => $cart->id]);
                    }
                }

                $guestCart->update(['status' => 'abandoned']);
            }

            if ($cart->session_id !== $sessionId) {
                $cart->update(['session_id' => $sessionId]);
            }

            return $cart->fresh('items.product', 'items.productVariant.attributeOptions');
        });
    }

    protected function getOrCreateGuestCart(Request $request): Cart
    {
        $sessionId = $request->session()->getId();
        $sessionCartId = $request->session()->get('cart_id');

        if ($sessionCartId) {
            $cart = Cart::query()
                ->where('id', $sessionCartId)
                ->where('status', 'active')
                ->first();

            if ($cart) {
                if ($cart->session_id !== $sessionId) {
                    $cart->update(['session_id' => $sessionId]);
                }

                return $cart;
            }
        }

        return Cart::query()->firstOrCreate(
            ['session_id' => $sessionId, 'status' => 'active'],
            ['user_id' => null]
        );
    }

    protected function assertCartOwnership(Cart $cart, CartItem $item): void
    {
        if ($item->cart_id !== $cart->id) {
            throw new RuntimeException('This cart item does not belong to the active cart.');
        }
    }

    protected function assertStock(?ProductVariant $variant, int $quantity): void
    {
        if ($variant && $quantity > $variant->stock) {
            throw new RuntimeException('Requested quantity exceeds available stock.');
        }
    }
}
