<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use App\Models\Brand;
use Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Generate slug from name
        if ($this->has('name') && !$this->has('slug')) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }

        // Get category_id by category_name safely
        if ($this->has('category_name')) {
            $category = Category::where('name', $this->category_name)->first();
            if ($category) {
                $this->merge(['category_id' => $category->id]);
            }
        }

        // Get brand_id by brand_name safely
        if ($this->has('brand_name')) {
            $brand = Brand::where('name', $this->brand_name)->first();
            if ($brand) {
                $this->merge(['brand_id' => $brand->id]);
            }
        }

        // generate product sku if not provided
        if (!$this->has('sku')) {
            $this->merge(['sku' => $this->generateProductSku()]);
        }

        // Auto-generate missing variant SKUs based on product SKU
        $variants = $this->input('variants');
        if (is_array($variants)) {
            $productSku = $this->input('sku');
            $updated = false;
            foreach ($variants as $index => $variant) {
                if (empty($variant['sku'])) {
                    $variants[$index]['sku'] = $productSku . '-V' . ($index + 1);
                    $updated = true;
                }
            }
            if ($updated) {
                $this->merge(['variants' => $variants]);
            }
        }
    }

    private function generateProductSku(): string
    {
        return 'MOAH-' . strtoupper(Str::random(8));
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],

            // Variants array validation
            'variants' => ['nullable', 'array'],
            'variants.*.sku' => ['required', 'string', 'max:100', 'distinct', 'unique:product_variants,sku'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.attributes' => ['nullable', 'array'],
            'variants.*.attributes.*' => ['integer', 'exists:attribute_options,id'],

            // Image uploads validation
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Max 2MB per image
        ];
    }
}
