<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $cartCount = 0;

        $cartQuery = Cart::query()->where('status', 'active');

        if ($request->user()) {
            $cartQuery->where('user_id', $request->user()->getAuthIdentifier());
        } else {
            $cartQuery->whereNull('user_id')->where('session_id', $request->session()->getId());
        }

        $cart = $cartQuery->withSum('items', 'quantity')->first();
        $cartCount = (int) ($cart?->items_sum_quantity ?? 0);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'cart' => [
                'itemsCount' => $cartCount,
            ],
        ];
    }
}
