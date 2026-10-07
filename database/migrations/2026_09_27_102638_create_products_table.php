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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 8, 2)->default(0.00);
            $table->integer('stock')->default(0);
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('language')->default('Arabic');
            $table->string('age_suited')->nullable();
            $table->string('season_type')->nullable();
            $table->string('format')->nullable();
            $table->string('isbn')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
