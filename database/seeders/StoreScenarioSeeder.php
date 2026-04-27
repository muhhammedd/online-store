<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('cart_items')->delete();
        DB::table('carts')->delete();
        DB::table('contact_messages')->delete();
        DB::table('users')->delete();

        $customer = User::query()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => $now,
        ]);

        $admin = User::query()->create([
            'name' => 'Store Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => $now,
        ]);

        $products = DB::table('products')
            ->orderBy('id')
            ->get()
            ->keyBy('slug');

        $variants = DB::table('product_variants')
            ->orderBy('id')
            ->get()
            ->keyBy('sku');

        $this->seedActiveCustomerCart($customer->id, $variants, $now);
        $this->seedGuestCart($variants, $now);
        $this->seedOrders($customer->id, $products, $variants, $now);
        $this->seedContactMessages($now);
    }

    protected function seedActiveCustomerCart(int $userId, $variants, Carbon $now): void
    {
        $cartId = DB::table('carts')->insertGetId([
            'user_id' => $userId,
            'session_id' => null,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $items = [
            ['sku' => 'SHOE-001-WHT-40', 'quantity' => 2, 'unit_discount' => 20.00],
            ['sku' => 'BAG-001-TAN-OS', 'quantity' => 1, 'unit_discount' => 16.00],
        ];

        foreach ($items as $item) {
            $variant = $variants[$item['sku']];

            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'quantity' => $item['quantity'],
                'unit_price' => $variant->price,
                'unit_discount' => $item['unit_discount'],
                'total_price' => $variant->price * $item['quantity'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    protected function seedGuestCart($variants, Carbon $now): void
    {
        $cartId = DB::table('carts')->insertGetId([
            'user_id' => null,
            'session_id' => 'demo-guest-session',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $variant = $variants['SAND-001-WHT-37'];

        DB::table('cart_items')->insert([
            'cart_id' => $cartId,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'unit_price' => $variant->price,
            'unit_discount' => 10.00,
            'total_price' => $variant->price,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    protected function seedOrders(int $userId, $products, $variants, Carbon $now): void
    {
        $firstOrderId = DB::table('orders')->insertGetId([
            'user_id' => $userId,
            'order_number' => 'ORD-' . $now->format('Ymd') . '-100001',
            'status' => 'processing',
            'shipping_cost' => 0,
            'discount' => 56.00,
            'total' => 374.00,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'customer_name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '01000000000',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'address' => '12 Nile Street',
            'address2' => 'Floor 4',
            'notes' => 'Call before delivery',
            'created_at' => $now->copy()->subDays(1),
            'updated_at' => $now->copy()->subDays(1),
        ]);

        $this->insertOrderItem($firstOrderId, $products['city-runner-sneakers']->name, $variants['SHOE-001-BLK-41'], 2, 20.00, $now);
        $this->insertOrderItem($firstOrderId, $products['metro-leather-tote']->name, $variants['BAG-001-TAN-OS'], 1, 16.00, $now);

        $secondOrderId = DB::table('orders')->insertGetId([
            'user_id' => null,
            'order_number' => 'ORD-' . $now->format('Ymd') . '-100002',
            'status' => 'completed',
            'shipping_cost' => 0,
            'discount' => 10.00,
            'total' => 69.00,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'customer_name' => 'Guest Buyer',
            'email' => 'guest@example.com',
            'phone' => '01111111111',
            'country' => 'Egypt',
            'city' => 'Alexandria',
            'address' => '34 Corniche Road',
            'address2' => null,
            'notes' => 'Leave with reception',
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDays(2),
        ]);

        $this->insertOrderItem($secondOrderId, $products['coastline-flat-sandals']->name, $variants['SAND-001-TAN-38'], 1, 10.00, $now);

        DB::table('carts')->insert([
            'user_id' => $userId,
            'session_id' => null,
            'status' => 'converted',
            'created_at' => $now->copy()->subDays(1),
            'updated_at' => $now->copy()->subDays(1),
        ]);
    }

    protected function insertOrderItem(int $orderId, string $productName, object $variant, int $quantity, float $unitDiscount, Carbon $now): void
    {
        $variantSnapshot = DB::table('product_variant_attribute_options as pvao')
            ->join('attribute_options as ao', 'ao.id', '=', 'pvao.attribute_option_id')
            ->join('attributes as a', 'a.id', '=', 'ao.attribute_id')
            ->where('pvao.product_variant_id', $variant->id)
            ->orderBy('a.name')
            ->get(['a.name as attribute_name', 'ao.value'])
            ->map(fn ($option) => "{$option->attribute_name}: {$option->value}")
            ->implode(', ');

        DB::table('order_items')->insert([
            'order_id' => $orderId,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => $productName,
            'variant_snapshot' => $variantSnapshot,
            'quantity' => $quantity,
            'unit_price' => $variant->price,
            'unit_discount' => $unitDiscount,
            'total_price' => $variant->price * $quantity,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    protected function seedContactMessages(Carbon $now): void
    {
        DB::table('contact_messages')->insert([
            [
                'name' => 'Nour Hassan',
                'email' => 'nour@example.com',
                'message' => 'Do you restock sold out variants every week?',
                'is_read' => false,
                'created_at' => $now->copy()->subHours(6),
                'updated_at' => $now->copy()->subHours(6),
            ],
            [
                'name' => 'Omar Adel',
                'email' => 'omar@example.com',
                'message' => 'I want to confirm bag dimensions before ordering.',
                'is_read' => true,
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now->copy()->subHours(20),
            ],
        ]);
    }
}
