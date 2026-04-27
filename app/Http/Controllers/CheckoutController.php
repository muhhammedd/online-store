<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use RuntimeException;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService
    ) {
    }

    public function index()
    {
        $cart = $this->cartService->getActiveCart(request())->load([
            'items.product.images',
            'items.productVariant.attributeOptions.attribute',
        ]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $totals = $this->cartService->totals($cart);

        return Inertia::render('Cart/Checkout', compact('cart', 'totals'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cartService->getActiveCart($request)->load([
            'items.product',
            'items.productVariant.attributeOptions.attribute',
        ]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $order = $this->checkoutService->createOrder($cart, $request->validated());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['checkout' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('cart.index')->with('success', "Order {$order->order_number} placed successfully.");
    }
}
