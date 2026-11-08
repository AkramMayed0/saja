<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class RuwadComputingProductSeeder extends Seeder
{
    public function run(): void
    {
        $categorySlug = 'slsl-road-alhosb-oalthkaaa-alastnaaay-aarby-mbst';
        $referenceCategorySlug = 'slsl-alhosb-oalthkaaa-alastnaaay-alamn-alsybrany-aarby';
        $levels = range(1, 10);
        $assets = [];

        foreach ($levels as $level) {
            $assetPath = database_path('seeders/assets/ruwad-computing/level-' . str_pad((string) $level, 2, '0', STR_PAD_LEFT) . '.webp');
            $contents = is_file($assetPath) ? file_get_contents($assetPath) : false;

            if ($contents === false) {
                throw new RuntimeException("Missing Ruwad Computing cover asset for level {$level}: {$assetPath}");
            }

            $assets[$level] = $contents;
        }

        $parentCategory = Category::firstOrCreate(
            ['slug' => 'manahij-al-hawsabah-wa-al-tiqniyah'],
            ['name' => 'مناهج الحوسبة والتقنية', 'parent_id' => null]
        );
        $category = Category::firstOrCreate(
            ['slug' => $categorySlug],
            ['name' => 'سلسلة رواد الحوسبة والذكاء الاصطناعي (عربي-مبسط)', 'parent_id' => $parentCategory->id]
        );

        if ($category->parent_id !== $parentCategory->id) {
            $category->update(['parent_id' => $parentCategory->id]);
        }

        $referenceCategory = Category::where('slug', $referenceCategorySlug)->first();
        if (!$referenceCategory) {
            throw new RuntimeException('The Arabic computing series used for price references was not found.');
        }

        $referenceProducts = Product::where('category_id', $referenceCategory->id)
            ->where('book_type', 'combined')
            ->whereIn('level', array_map('strval', $levels))
            ->get()
            ->keyBy('level');

        foreach ($levels as $level) {
            if (!$referenceProducts->has((string) $level)) {
                throw new RuntimeException("No matching computing-series price reference exists for level {$level}.");
            }
        }

        foreach ($assets as $level => $contents) {
            $imagePath = 'products/ruwad-computing-level-' . str_pad((string) $level, 2, '0', STR_PAD_LEFT) . '.webp';
            if (!Storage::disk('public')->put($imagePath, $contents)) {
                throw new RuntimeException("Unable to store Ruwad Computing cover for level {$level}.");
            }
        }

        DB::transaction(function () use ($category, $levels, $referenceProducts): void {
            foreach ($levels as $level) {
                $levelString = (string) $level;
                $reference = $referenceProducts->get($levelString);
                $title = $category->getRawOriginal('name') . ' ' . $levelString;

                Product::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'level' => $levelString,
                        'book_type' => 'combined',
                    ],
                    [
                        'title' => $title,
                        'slug' => Str::slug('ruwad-computing-ai-simplified-level-' . $levelString),
                        'description' => "كتاب المستوى {$levelString} من سلسلة رواد الحوسبة والذكاء الاصطناعي (عربي-مبسط).",
                        'price' => $reference->price,
                        'price_sar' => $reference->price_sar,
                        'price_usd' => $reference->price_usd,
                        'original_price_sar' => $reference->original_price_sar,
                        'original_price_usd' => $reference->original_price_usd,
                        'stock' => 1000000,
                        'author' => $reference->author,
                        'publisher' => $reference->publisher,
                        'language' => 'Arabic',
                        'format' => 'ورقي',
                        'image' => 'products/ruwad-computing-level-' . str_pad($levelString, 2, '0', STR_PAD_LEFT) . '.webp',
                        'sku' => 'RWAD-COMP-' . str_pad($levelString, 2, '0', STR_PAD_LEFT),
                        'rating' => 0,
                        'reviews_count' => 0,
                    ]
                );
            }
        });

        $this->command?->info('Added Ruwad Computing and AI products for levels 1-10.');
    }
}
