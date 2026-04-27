<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use App\Services\CartService;
use Illuminate\Http\Request;
use RuntimeException;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index()
    {
        $cart = $this->cartService->getActiveCart(request())->load([
            'items.product.images',
            'items.productVariant.attributeOptions.attribute',
        ]);

        $totals = $this->cartService->totals($cart);

        return Inertia::render('Cart/Index', compact('cart', 'totals'));
    }

    public function store(AddCartItemRequest $request, int $id): RedirectResponse
    {
        $product = Product::query()->with('variants')->findOrFail($id);

        try {
            $this->cartService->addItem(
                $request,
                $product,
                $product->variants->firstWhere('id', $request->integer('product_variant_id')),
                $request->integer('quantity')
            );
        } catch (RuntimeException $exception) {
            return back()->withErrors(['cart' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(UpdateCartItemRequest $request, int $id): RedirectResponse
    {
        $item = CartItem::query()->with('productVariant')->findOrFail($id);

        try {
            $this->cartService->updateItemQuantity($request, $item, $request->integer('quantity'));
        } catch (RuntimeException $exception) {
            return back()->withErrors(['cart' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('cart.index')->with('success', 'Item updated.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $item = CartItem::query()->findOrFail($id);
        $this->cartService->removeItem($request, $item);

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }
}
