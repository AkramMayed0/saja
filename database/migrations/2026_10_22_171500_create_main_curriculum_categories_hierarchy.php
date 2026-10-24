<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Main Category: مناهج اللغة الإنجليزية
        $english = Category::firstOrCreate(
            ['slug' => 'manahij-al-lughah-al-injiliziyah'],
            ['name' => 'مناهج اللغة الإنجليزية', 'parent_id' => null]
        );
        if ($english->name !== 'مناهج اللغة الإنجليزية') {
            $english->update(['name' => 'مناهج اللغة الإنجليزية', 'parent_id' => null]);
        }

        // 2. Create Main Category: مناهج الحوسبة والتقنية
        $tech = Category::firstOrCreate(
            ['slug' => 'manahij-al-hawsabah-wa-al-tiqniyah'],
            ['name' => 'مناهج الحوسبة والتقنية', 'parent_id' => null]
        );
        if ($tech->name !== 'مناهج الحوسبة والتقنية') {
            $tech->update(['name' => 'مناهج الحوسبة والتقنية', 'parent_id' => null]);
        }

        // 3. Create Main Category: مناهج رياض الأطفال
        $kindergarten = Category::firstOrCreate(
            ['slug' => 'manahij-riyad-al-atfal'],
            ['name' => 'مناهج رياض الأطفال', 'parent_id' => null]
        );
        if ($kindergarten->name !== 'مناهج رياض الأطفال') {
            $kindergarten->update(['name' => 'مناهج رياض الأطفال', 'parent_id' => null]);
        }

        // Assign English subcategories
        Category::where('id', '!=', $english->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%English Gate%')
                  ->orWhere('name', 'like', '%English Friends%');
            })
            ->update(['parent_id' => $english->id]);

        // Assign Computer & Tech subcategories
        Category::where('id', '!=', $tech->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%الحوسبة%')
                  ->orWhere('name', 'like', '%حوسبة%');
            })
            ->update(['parent_id' => $tech->id]);

        // Assign Kindergarten / Ashbal subcategories
        Category::where('id', '!=', $kindergarten->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%اشبال%')
                  ->orWhere('name', 'like', '%أشبال%')
                  ->orWhere('name', 'like', '%تمهيدي%');
            })
            ->update(['parent_id' => $kindergarten->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $english = Category::where('slug', 'manahij-al-lughah-al-injiliziyah')->first();
        $tech = Category::where('slug', 'manahij-al-hawsabah-wa-al-tiqniyah')->first();
        $kindergarten = Category::where('slug', 'manahij-riyad-al-atfal')->first();

        $parentIds = array_filter([$english?->id, $tech?->id, $kindergarten?->id]);

        if (!empty($parentIds)) {
            Category::whereIn('parent_id', $parentIds)->update(['parent_id' => null]);
            Category::whereIn('id', $parentIds)->delete();
        }
    }
};
