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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'pages_count')) {
                $table->integer('pages_count')->nullable()->after('age_suited');
            }
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('pages_count');
            }
        });

        Schema::table('bundles', function (Blueprint $table) {
            if (!Schema::hasColumn('bundles', 'age_suited')) {
                $table->string('age_suited')->nullable()->after('level');
            }
            if (!Schema::hasColumn('bundles', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('age_suited');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pages_count', 'dimensions']);
        });

        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn(['age_suited', 'dimensions']);
        });
    }
};
