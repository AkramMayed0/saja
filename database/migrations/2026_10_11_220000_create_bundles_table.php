<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('bundles')) {
            Schema::create('bundles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('level')->nullable();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->decimal('price_sar', 10, 2)->nullable();
                $table->decimal('price_usd', 10, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'bundle_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('bundle_id')->nullable()->after('category_id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'bundle_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('bundle_id');
            });
        }

        Schema::dropIfExists('bundles');
    }
};
