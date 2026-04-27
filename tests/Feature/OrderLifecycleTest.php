<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_variant_product_to_cart(): void
    {
        [$product, $variant] = $this->createCatalogProduct();

        $response = $this->post(route('cart.store', $product->id), [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('carts', [
            'status' => 'active',
            'user_id' => null,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'total_price' => 160.00,
        ]);
    }

    public function test_guest_can_checkout_and_create_order(): void
    {
        [$product, $variant] = $this->createCatalogProduct();

        $this->post(route('cart.store', $product->id), [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Guest Buyer',
            'phone' => '01000000000',
            'email' => 'guest@example.com',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'address' => '123 Nile Street',
            'address2' => 'Apt 5',
            'payment_method' => 'cod',
            'notes' => 'Leave at the front desk',
        ]);

        $response->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Guest Buyer',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
            'total' => 160.00,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => $product->name,
            'quantity' => 2,
            'unit_price' => 80.00,
            'unit_discount' => 20.00,
            'total_price' => 160.00,
        ]);

        $this->assertDatabaseHas('carts', [
            'status' => 'converted',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock' => 3,
        ]);
    }

    public function test_authenticated_user_gets_user_bound_cart(): void
    {
        $user = User::factory()->create();
        [$product, $variant] = $this->createCatalogProduct();

        $response = $this->actingAs($user)->post(route('cart.store', $product->id), [
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }

    protected function createCatalogProduct(): array
    {
        $category = Category::query()->create([
            'name' => 'Shoes',
            'slug' => 'shoes',
            'description' => 'Shoes category',
            'is_active' => true,
        ]);

        $brand = Brand::query()->create([
            'name' => 'Stride Lab',
            'slug' => 'stride-lab',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Test Runner',
            'slug' => 'test-runner',
            'short_description' => 'Testing product',
            'description' => 'Testing description',
            'base_price' => 100,
            'sale_price' => 80,
            'sku' => 'TEST-RUNNER',
            'is_featured' => false,
            'is_active' => true,
        ]);

        $sizeAttribute = Attribute::query()->create([
            'name' => 'Size',
            'slug' => 'size',
        ]);

        $sizeOption = AttributeOption::query()->create([
            'attribute_id' => $sizeAttribute->id,
            'value' => '42',
            'sort_order' => 1,
        ]);

        $variant = ProductVariant::query()->create([
            'product_id' => $product->id,
            'sku' => 'TEST-RUNNER-42',
            'price' => 80,
            'stock' => 5,
            'is_active' => true,
        ]);

        $variant->attributeOptions()->attach($sizeOption->id);

        return [$product, $variant];
    }
}
