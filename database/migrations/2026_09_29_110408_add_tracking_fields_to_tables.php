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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_driver')->default(false)->after('is_admin');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('delivery_status')->default('pending')->after('status');
            $table->decimal('driver_lat', 10, 8)->nullable()->after('delivery_status');
            $table->decimal('driver_lng', 11, 8)->nullable()->after('driver_lat');
            $table->timestamp('driver_last_updated_at')->nullable()->after('driver_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'driver_id',
                'delivery_status',
                'driver_lat',
                'driver_lng',
                'driver_last_updated_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_driver');
        });
    }
};
