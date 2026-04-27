<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StoreCatalogSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('product_variant_attribute_options')->delete();
        DB::table('product_images')->delete();
        DB::table('product_variants')->delete();
        DB::table('products')->delete();
        DB::table('attribute_options')->delete();
        DB::table('attributes')->delete();
        DB::table('brands')->delete();
        DB::table('categories')->delete();

        Schema::enableForeignKeyConstraints();

        $now = Carbon::now();

        $categoryIds = $this->seedCategories($now);
        $brandIds = $this->seedBrands($now);
        $attributeOptionIds = $this->seedAttributes($now);

        foreach ($this->products() as $index => $product) {
            $productId = DB::table('products')->insertGetId([
                'brand_id' => $brandIds[$product['brand']],
                'category_id' => $categoryIds[$product['category']],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'short_description' => $product['short_description'],
                'description' => $product['description'],
                'base_price' => $product['base_price'],
                'sale_price' => $product['sale_price'],
                'sku' => $product['sku'],
                'is_featured' => $index < 6,
                'is_active' => true,
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('product_images')->insert([
                'product_id' => $productId,
                'image_url' => $this->commonsFileUrl($product['image_file']),
                'alt_text' => $product['name'],
                'sort_order' => 1,
                'is_primary' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($product['variants'] as $variant) {
                $variantId = DB::table('product_variants')->insertGetId([
                    'product_id' => $productId,
                    'stock' => $variant['stock'],
                    'price' => $variant['price'],
                    'sku' => $variant['sku'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($variant['options'] as $attributeName => $value) {
                    DB::table('product_variant_attribute_options')->insert([
                        'product_variant_id' => $variantId,
                        'attribute_option_id' => $attributeOptionIds[$attributeName][$value],
                    ]);
                }
            }
        }
    }

    protected function seedCategories(Carbon $now): array
    {
        $categories = [
            'heels' => 'Elegant heels and occasion-ready silhouettes.',
            'shoes' => 'Daily shoes built for comfort, work, and city movement.',
            'bags' => 'Functional bags for work, travel, and everyday carry.',
            'accessories' => 'Small finishing pieces that complete every outfit.',
            'sandals' => 'Open footwear for warm days and relaxed styling.',
        ];

        $ids = [];

        foreach ($categories as $slug => $description) {
            $ids[$slug] = DB::table('categories')->insertGetId([
                'name' => Str::title($slug),
                'slug' => $slug,
                'description' => $description,
                'is_active' => true,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $ids;
    }

    protected function seedBrands(Carbon $now): array
    {
        $brands = [
            'aurelle',
            'stride-lab',
            'urban-carry',
            'solenne',
            'vanta',
            'northloop',
            'terra-vogue',
            'luma-craft',
        ];

        $ids = [];

        foreach ($brands as $slug) {
            $ids[$slug] = DB::table('brands')->insertGetId([
                'name' => Str::headline($slug),
                'slug' => $slug,
                'logo' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $ids;
    }

    protected function seedAttributes(Carbon $now): array
    {
        $attributes = [
            'Size' => ['One Size', '36', '37', '38', '39', '40', '41', '42'],
            'Color' => ['Black', 'White', 'Beige', 'Brown', 'Tan', 'Gold', 'Silver', 'Red', 'Blue', 'Green'],
            'Material' => ['Leather', 'Suede', 'Canvas', 'Faux Leather', 'Metal', 'Rubber'],
        ];

        $ids = [];

        foreach ($attributes as $name => $values) {
            $attributeId = DB::table('attributes')->insertGetId([
                'name' => $name,
                'slug' => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($values as $sortOrder => $value) {
                $ids[$name][$value] = DB::table('attribute_options')->insertGetId([
                    'attribute_id' => $attributeId,
                    'value' => $value,
                    'sort_order' => $sortOrder + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        return $ids;
    }

    protected function commonsFileUrl(string $fileName): string
    {
        return 'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode($fileName);
    }

    protected function products(): array
    {
        return [
            [
                'category' => 'heels',
                'brand' => 'aurelle',
                'name' => 'Velvet Rise Heels',
                'slug' => 'velvet-rise-heels',
                'short_description' => 'Pointed evening heels with a satin-inspired finish.',
                'description' => 'A refined pair of dress heels designed for events, dinners, and formal looks. Balanced height and a clean silhouette make them easy to style.',
                'base_price' => 149,
                'sale_price' => 129,
                'sku' => 'HEEL-001',
                'image_file' => 'High heels (1).jpg',
                'variants' => [
                    ['sku' => 'HEEL-001-BLK-38', 'price' => 129.00, 'stock' => 12, 'options' => ['Size' => '38', 'Color' => 'Black', 'Material' => 'Leather']],
                    ['sku' => 'HEEL-001-RED-39', 'price' => 129.00, 'stock' => 9, 'options' => ['Size' => '39', 'Color' => 'Red', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'heels',
                'brand' => 'solenne',
                'name' => 'Aurora Slingback Heels',
                'slug' => 'aurora-slingback-heels',
                'short_description' => 'Soft slingback heels for polished day-to-night outfits.',
                'description' => 'This pair blends a sleek profile with a comfortable back strap, making it a reliable option for office wear and semi-formal occasions.',
                'base_price' => 139,
                'sale_price' => null,
                'sku' => 'HEEL-002',
                'image_file' => 'High Heels.JPG',
                'variants' => [
                    ['sku' => 'HEEL-002-BEI-37', 'price' => 139.00, 'stock' => 11, 'options' => ['Size' => '37', 'Color' => 'Beige', 'Material' => 'Suede']],
                    ['sku' => 'HEEL-002-BLK-39', 'price' => 139.00, 'stock' => 10, 'options' => ['Size' => '39', 'Color' => 'Black', 'Material' => 'Suede']],
                ],
            ],
            [
                'category' => 'heels',
                'brand' => 'terra-vogue',
                'name' => 'Studio Point Heels',
                'slug' => 'studio-point-heels',
                'short_description' => 'Sharp pointed pumps with a clean, editorial look.',
                'description' => 'A minimalist high heel built for elevated everyday dressing. The shape is modern, feminine, and easy to pair with dresses or tailoring.',
                'base_price' => 159,
                'sale_price' => 144,
                'sku' => 'HEEL-003',
                'image_file' => 'High heels shoes.jpg',
                'variants' => [
                    ['sku' => 'HEEL-003-GLD-38', 'price' => 144.00, 'stock' => 7, 'options' => ['Size' => '38', 'Color' => 'Gold', 'Material' => 'Leather']],
                    ['sku' => 'HEEL-003-SLV-40', 'price' => 144.00, 'stock' => 6, 'options' => ['Size' => '40', 'Color' => 'Silver', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'heels',
                'brand' => 'vanta',
                'name' => 'Luna Crystal Heels',
                'slug' => 'luna-crystal-heels',
                'short_description' => 'Statement heels for event-ready styling.',
                'description' => 'A bold heel with a dressy attitude and a clean upper. Ideal for parties, weddings, and premium evening edits.',
                'base_price' => 169,
                'sale_price' => null,
                'sku' => 'HEEL-004',
                'image_file' => 'Highheels.JPG',
                'variants' => [
                    ['sku' => 'HEEL-004-BLK-37', 'price' => 169.00, 'stock' => 8, 'options' => ['Size' => '37', 'Color' => 'Black', 'Material' => 'Faux Leather']],
                    ['sku' => 'HEEL-004-BLU-39', 'price' => 169.00, 'stock' => 5, 'options' => ['Size' => '39', 'Color' => 'Blue', 'Material' => 'Faux Leather']],
                ],
            ],
            [
                'category' => 'shoes',
                'brand' => 'stride-lab',
                'name' => 'City Runner Sneakers',
                'slug' => 'city-runner-sneakers',
                'short_description' => 'Breathable sneakers built for long walks and daily wear.',
                'description' => 'A lightweight sneaker with a streetwear-friendly shape and enough cushioning for everyday movement.',
                'base_price' => 119,
                'sale_price' => 99,
                'sku' => 'SHOE-001',
                'image_file' => 'Sneaker.png',
                'variants' => [
                    ['sku' => 'SHOE-001-WHT-40', 'price' => 99.00, 'stock' => 20, 'options' => ['Size' => '40', 'Color' => 'White', 'Material' => 'Canvas']],
                    ['sku' => 'SHOE-001-BLK-41', 'price' => 99.00, 'stock' => 16, 'options' => ['Size' => '41', 'Color' => 'Black', 'Material' => 'Canvas']],
                ],
            ],
            [
                'category' => 'shoes',
                'brand' => 'northloop',
                'name' => 'Heritage Court Shoes',
                'slug' => 'heritage-court-shoes',
                'short_description' => 'Classic low-profile shoes with a retro sport influence.',
                'description' => 'This pair blends a vintage court aesthetic with modern comfort, making it an easy everyday option.',
                'base_price' => 124,
                'sale_price' => null,
                'sku' => 'SHOE-002',
                'image_file' => 'Sneaker.jpg',
                'variants' => [
                    ['sku' => 'SHOE-002-WHT-39', 'price' => 124.00, 'stock' => 13, 'options' => ['Size' => '39', 'Color' => 'White', 'Material' => 'Leather']],
                    ['sku' => 'SHOE-002-GRN-42', 'price' => 124.00, 'stock' => 8, 'options' => ['Size' => '42', 'Color' => 'Green', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'shoes',
                'brand' => 'luma-craft',
                'name' => 'Harbor Tassel Loafers',
                'slug' => 'harbor-tassel-loafers',
                'short_description' => 'Smart loafers suited for workwear and tailored outfits.',
                'description' => 'Refined loafers with a soft structure and timeless shape. Great for dress-casual wardrobes and polished office combinations.',
                'base_price' => 134,
                'sale_price' => 119,
                'sku' => 'SHOE-003',
                'image_file' => 'Tassel loafers.jpg',
                'variants' => [
                    ['sku' => 'SHOE-003-BRN-40', 'price' => 119.00, 'stock' => 10, 'options' => ['Size' => '40', 'Color' => 'Brown', 'Material' => 'Leather']],
                    ['sku' => 'SHOE-003-TAN-41', 'price' => 119.00, 'stock' => 7, 'options' => ['Size' => '41', 'Color' => 'Tan', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'shoes',
                'brand' => 'urban-carry',
                'name' => 'Presidio Slip-On Shoes',
                'slug' => 'presidio-slip-on-shoes',
                'short_description' => 'Versatile loafers with an easy slip-on design.',
                'description' => 'A comfortable dress-casual shoe designed for quick styling, lightweight wear, and clean silhouettes.',
                'base_price' => 129,
                'sale_price' => null,
                'sku' => 'SHOE-004',
                'image_file' => 'Presidio Loafer.jpg',
                'variants' => [
                    ['sku' => 'SHOE-004-BRN-39', 'price' => 129.00, 'stock' => 11, 'options' => ['Size' => '39', 'Color' => 'Brown', 'Material' => 'Suede']],
                    ['sku' => 'SHOE-004-BLK-40', 'price' => 129.00, 'stock' => 9, 'options' => ['Size' => '40', 'Color' => 'Black', 'Material' => 'Suede']],
                ],
            ],
            [
                'category' => 'bags',
                'brand' => 'urban-carry',
                'name' => 'Metro Leather Tote',
                'slug' => 'metro-leather-tote',
                'short_description' => 'Structured tote bag for workdays and commuting.',
                'description' => 'A roomy tote with clean lines and a minimalist profile, ideal for documents, a tablet, and daily essentials.',
                'base_price' => 145,
                'sale_price' => 129,
                'sku' => 'BAG-001',
                'image_file' => 'A handbag.jpg',
                'variants' => [
                    ['sku' => 'BAG-001-TAN-OS', 'price' => 129.00, 'stock' => 14, 'options' => ['Size' => 'One Size', 'Color' => 'Tan', 'Material' => 'Leather']],
                    ['sku' => 'BAG-001-BRN-OS', 'price' => 129.00, 'stock' => 8, 'options' => ['Size' => 'One Size', 'Color' => 'Brown', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'bags',
                'brand' => 'solenne',
                'name' => 'Sunset Mini Bag',
                'slug' => 'sunset-mini-bag',
                'short_description' => 'Compact shoulder bag for lighter day looks.',
                'description' => 'A small carry bag with a bright finish and enough room for cards, keys, and the essentials.',
                'base_price' => 95,
                'sale_price' => null,
                'sku' => 'BAG-002',
                'image_file' => 'Orange hand bag.jpg',
                'variants' => [
                    ['sku' => 'BAG-002-RED-OS', 'price' => 95.00, 'stock' => 12, 'options' => ['Size' => 'One Size', 'Color' => 'Red', 'Material' => 'Faux Leather']],
                    ['sku' => 'BAG-002-BEI-OS', 'price' => 95.00, 'stock' => 7, 'options' => ['Size' => 'One Size', 'Color' => 'Beige', 'Material' => 'Faux Leather']],
                ],
            ],
            [
                'category' => 'bags',
                'brand' => 'vanta',
                'name' => 'Noir Structured Bag',
                'slug' => 'noir-structured-bag',
                'short_description' => 'Clean black handbag with a formal silhouette.',
                'description' => 'A polished handbag suited to smart outfits and evening use. Compact proportions with a refined, structured feel.',
                'base_price' => 138,
                'sale_price' => 122,
                'sku' => 'BAG-003',
                'image_file' => 'Black handbag.jpg',
                'variants' => [
                    ['sku' => 'BAG-003-BLK-OS', 'price' => 122.00, 'stock' => 10, 'options' => ['Size' => 'One Size', 'Color' => 'Black', 'Material' => 'Leather']],
                    ['sku' => 'BAG-003-BRN-OS', 'price' => 122.00, 'stock' => 6, 'options' => ['Size' => 'One Size', 'Color' => 'Brown', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'bags',
                'brand' => 'terra-vogue',
                'name' => 'Nomad Woven Backpack',
                'slug' => 'nomad-woven-backpack',
                'short_description' => 'Casual backpack for travel days and campus carry.',
                'description' => 'A practical backpack with a simple shape and enough room for day trips, study, or hands-free city use.',
                'base_price' => 110,
                'sale_price' => null,
                'sku' => 'BAG-004',
                'image_file' => 'Backpack (26645453056).jpg',
                'variants' => [
                    ['sku' => 'BAG-004-BLU-OS', 'price' => 110.00, 'stock' => 9, 'options' => ['Size' => 'One Size', 'Color' => 'Blue', 'Material' => 'Canvas']],
                    ['sku' => 'BAG-004-GRN-OS', 'price' => 110.00, 'stock' => 9, 'options' => ['Size' => 'One Size', 'Color' => 'Green', 'Material' => 'Canvas']],
                ],
            ],
            [
                'category' => 'accessories',
                'brand' => 'luma-craft',
                'name' => 'Solar Edge Sunglasses',
                'slug' => 'solar-edge-sunglasses',
                'short_description' => 'Lightweight sunglasses for sunny daily wear.',
                'description' => 'A sharp everyday accessory with a timeless shape. Works well with casual looks, vacation outfits, and warm-weather styling.',
                'base_price' => 58,
                'sale_price' => 49,
                'sku' => 'ACC-001',
                'image_file' => 'Sunglasses.jpg',
                'variants' => [
                    ['sku' => 'ACC-001-BLK-OS', 'price' => 49.00, 'stock' => 22, 'options' => ['Size' => 'One Size', 'Color' => 'Black', 'Material' => 'Metal']],
                    ['sku' => 'ACC-001-SLV-OS', 'price' => 49.00, 'stock' => 17, 'options' => ['Size' => 'One Size', 'Color' => 'Silver', 'Material' => 'Metal']],
                ],
            ],
            [
                'category' => 'accessories',
                'brand' => 'northloop',
                'name' => 'Meridian Wrist Watch',
                'slug' => 'meridian-wrist-watch',
                'short_description' => 'Clean analog watch for smart everyday use.',
                'description' => 'A versatile wristwatch with a classic face and simple strap, designed to complement both casual and office wardrobes.',
                'base_price' => 135,
                'sale_price' => null,
                'sku' => 'ACC-002',
                'image_file' => 'Wrist watch 1.jpg',
                'variants' => [
                    ['sku' => 'ACC-002-BRN-OS', 'price' => 135.00, 'stock' => 12, 'options' => ['Size' => 'One Size', 'Color' => 'Brown', 'Material' => 'Leather']],
                    ['sku' => 'ACC-002-SLV-OS', 'price' => 135.00, 'stock' => 8, 'options' => ['Size' => 'One Size', 'Color' => 'Silver', 'Material' => 'Metal']],
                ],
            ],
            [
                'category' => 'accessories',
                'brand' => 'aurelle',
                'name' => 'Pearl Drop Necklace',
                'slug' => 'pearl-drop-necklace',
                'short_description' => 'Decorative necklace for elevated evening styling.',
                'description' => 'A statement necklace with a dressy mood, created to add texture and shine to clean outfits and occasion looks.',
                'base_price' => 72,
                'sale_price' => 64,
                'sku' => 'ACC-003',
                'image_file' => 'Fashion Jewelry Necklace.jpg',
                'variants' => [
                    ['sku' => 'ACC-003-GLD-OS', 'price' => 64.00, 'stock' => 13, 'options' => ['Size' => 'One Size', 'Color' => 'Gold', 'Material' => 'Metal']],
                    ['sku' => 'ACC-003-SLV-OS', 'price' => 64.00, 'stock' => 10, 'options' => ['Size' => 'One Size', 'Color' => 'Silver', 'Material' => 'Metal']],
                ],
            ],
            [
                'category' => 'accessories',
                'brand' => 'terra-vogue',
                'name' => 'Atlas Shell Bracelet',
                'slug' => 'atlas-shell-bracelet',
                'short_description' => 'Textured bracelet with artisan-inspired detailing.',
                'description' => 'A lightweight bracelet designed as a finishing touch for relaxed looks, resort styling, and layered accessory sets.',
                'base_price' => 39,
                'sale_price' => null,
                'sku' => 'ACC-004',
                'image_file' => 'Bracelet image.jpg',
                'variants' => [
                    ['sku' => 'ACC-004-WHT-OS', 'price' => 39.00, 'stock' => 18, 'options' => ['Size' => 'One Size', 'Color' => 'White', 'Material' => 'Metal']],
                    ['sku' => 'ACC-004-BLU-OS', 'price' => 39.00, 'stock' => 11, 'options' => ['Size' => 'One Size', 'Color' => 'Blue', 'Material' => 'Metal']],
                ],
            ],
            [
                'category' => 'sandals',
                'brand' => 'solenne',
                'name' => 'Coastline Flat Sandals',
                'slug' => 'coastline-flat-sandals',
                'short_description' => 'Minimal sandals for warm days and vacation styling.',
                'description' => 'A comfortable flat sandal designed for summer outfits, beach walks, and relaxed city use.',
                'base_price' => 79,
                'sale_price' => 69,
                'sku' => 'SAND-001',
                'image_file' => 'Sandals (1).jpg',
                'variants' => [
                    ['sku' => 'SAND-001-WHT-37', 'price' => 69.00, 'stock' => 15, 'options' => ['Size' => '37', 'Color' => 'White', 'Material' => 'Leather']],
                    ['sku' => 'SAND-001-TAN-38', 'price' => 69.00, 'stock' => 14, 'options' => ['Size' => '38', 'Color' => 'Tan', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'sandals',
                'brand' => 'stride-lab',
                'name' => 'Cocoa Strap Sandals',
                'slug' => 'cocoa-strap-sandals',
                'short_description' => 'Supportive sandals with a secure strap layout.',
                'description' => 'A practical pair of sandals that blends everyday comfort with a slightly dressier shape for summer outings.',
                'base_price' => 84,
                'sale_price' => null,
                'sku' => 'SAND-002',
                'image_file' => 'Sandals (4).jpg',
                'variants' => [
                    ['sku' => 'SAND-002-BRN-38', 'price' => 84.00, 'stock' => 12, 'options' => ['Size' => '38', 'Color' => 'Brown', 'Material' => 'Leather']],
                    ['sku' => 'SAND-002-BEI-39', 'price' => 84.00, 'stock' => 10, 'options' => ['Size' => '39', 'Color' => 'Beige', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'sandals',
                'brand' => 'aurelle',
                'name' => 'Golden Bloom Sandals',
                'slug' => 'golden-bloom-sandals',
                'short_description' => 'Dressy sandals with a brighter finish.',
                'description' => 'A feminine sandal meant for summer evenings, holiday dressing, and lighter formal occasions.',
                'base_price' => 92,
                'sale_price' => 82,
                'sku' => 'SAND-003',
                'image_file' => 'Fancy sandals.png',
                'variants' => [
                    ['sku' => 'SAND-003-GLD-37', 'price' => 82.00, 'stock' => 9, 'options' => ['Size' => '37', 'Color' => 'Gold', 'Material' => 'Leather']],
                    ['sku' => 'SAND-003-BLK-39', 'price' => 82.00, 'stock' => 7, 'options' => ['Size' => '39', 'Color' => 'Black', 'Material' => 'Leather']],
                ],
            ],
            [
                'category' => 'sandals',
                'brand' => 'vanta',
                'name' => 'Palm Walk Slides',
                'slug' => 'palm-walk-slides',
                'short_description' => 'Simple slides for travel, poolside, and quick wear.',
                'description' => 'An easy sandal built for convenience and comfort, designed for daily errands, vacations, and warm-weather routines.',
                'base_price' => 56,
                'sale_price' => null,
                'sku' => 'SAND-004',
                'image_file' => 'Traditional Sandals.jpg',
                'variants' => [
                    ['sku' => 'SAND-004-BLK-40', 'price' => 56.00, 'stock' => 18, 'options' => ['Size' => '40', 'Color' => 'Black', 'Material' => 'Rubber']],
                    ['sku' => 'SAND-004-BRN-41', 'price' => 56.00, 'stock' => 13, 'options' => ['Size' => '41', 'Color' => 'Brown', 'Material' => 'Rubber']],
                ],
            ],
        ];
    }
}
