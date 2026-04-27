<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        View::composer('partials.store.navbar', function ($view) {
            $cartItemsCount = 0;

            if (Schema::hasTable('carts') && Schema::hasTable('cart_items')) {
                $request = request();
                $cart = null;

                if ($request->user()) {
                    $cart = Cart::query()
                        ->where('user_id', $request->user()->id)
                        ->where('status', 'active')
                        ->with('items')
                        ->first();
                }

                if (! $cart && $request->session()->has('cart_id')) {
                    $cart = Cart::query()
                        ->where('id', $request->session()->get('cart_id'))
                        ->where('status', 'active')
                        ->with('items')
                        ->first();
                }

                if (! $cart) {
                    $cart = Cart::query()
                        ->where('session_id', $request->session()->getId())
                        ->where('status', 'active')
                        ->with('items')
                        ->first();
                }

                $cartItemsCount = $cart ? (int) $cart->items->sum('quantity') : 0;
            }

            $view->with('cartItemsCount', $cartItemsCount);
        });
    }
}
