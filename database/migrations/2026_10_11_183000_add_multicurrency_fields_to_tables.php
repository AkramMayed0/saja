<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add multicurrency prices to products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_sar', 10, 2)->nullable()->after('price');
            $table->decimal('price_usd', 10, 2)->nullable()->after('price_sar');
        });

        // Seed existing products: set price_sar = price, and calculate a baseline price_usd
        DB::statement('UPDATE products SET price_sar = price WHERE price_sar IS NULL');
        DB::statement('UPDATE products SET price_usd = ROUND(price / 3.75, 2) WHERE price_usd IS NULL');

        // 2. Add currency to carts
        Schema::table('carts', function (Blueprint $table) {
            $table->string('currency', 10)->default('SAR')->after('session_id');
        });

        // 3. Add currency to cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('currency', 10)->default('SAR')->after('price');
        });

        // 4. Add currency to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 10)->default('SAR')->after('total_amount');
            $table->string('currency_symbol', 10)->default('ر.س')->after('currency');
        });

        // 5. Add currency to order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('currency', 10)->default('SAR')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_sar', 'price_usd']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['currency']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['currency']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['currency', 'currency_symbol']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['currency']);
        });
    }
};
