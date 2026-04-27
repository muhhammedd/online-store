<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with(['category', 'images', 'variants.attributeOptions.attribute'])
            ->active()
            ->latest()
            ->paginate(16);
        $featuredProducts = Product::query()
            ->with(['category', 'images'])
            ->active()
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with(['category', 'images'])
            ->active()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()
            ->withCount(['products' => fn($query) => $query->active()])
            ->active()
            ->latest()
            ->take(5)
            ->get();


        return Inertia::render('Product/Index', [
            'products' => $products,
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories
        ]);
    }

    public function show($slug)
    {
        $product = Product::query()
            ->with(['category', 'brand', 'images', 'variants.attributeOptions.attribute'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return Inertia::render('Product/Show', [
            'product' => $product
        ]);
    }
}
