<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index()
    {
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

        return Inertia::render('Home', [
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories
        ]);
    }
}
