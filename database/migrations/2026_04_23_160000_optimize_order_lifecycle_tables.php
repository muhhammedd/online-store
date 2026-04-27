<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'carts_user_status_index');
            $table->index(['session_id', 'status'], 'carts_session_status_index');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_variant_id', 'cart_items_product_variant_id_foreign')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();

            $table->index(
                ['cart_id', 'product_id', 'product_variant_id'],
                'cart_items_cart_product_variant_index'
            );
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug', 'products_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_slug_unique');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('cart_items_product_variant_id_foreign');
            $table->dropIndex('cart_items_cart_product_variant_index');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_user_status_index');
            $table->dropIndex('carts_session_status_index');
        });
    }
};
