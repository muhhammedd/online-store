<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $productId = (int) $this->input('product_id');
                $variantId = $this->input('product_variant_id');

                if (! $productId) {
                    return;
                }

                $product = Product::query()->withCount('variants')->find($productId);

                if (! $product) {
                    return;
                }

                if ($product->variants_count > 0 && ! $variantId) {
                    $validator->errors()->add('product_variant_id', 'Please choose a product variant.');
                }
            }
        ];
    }
}
