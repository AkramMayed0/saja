<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AshbalKg1ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categorySlug = 'slsl-ashbal-altaalymy-tmhydy-1';
        $referenceCategorySlug = 'slsl-ashbal-altaalymy-tmhydy-2';
        $coverCount = 6;
        $covers = [];

        foreach (range(1, $coverCount) as $number) {
            $assetPath = database_path('seeders/assets/ashbal-kg1/cover-' . str_pad((string) $number, 2, '0', STR_PAD_LEFT) . '.webp');
            $contents = is_file($assetPath) ? file_get_contents($assetPath) : false;

            if ($contents === false) {
                throw new RuntimeException("Missing Ashbal KG1 cover {$number}: {$assetPath}");
            }

            $covers[$number] = $contents;
        }

        $parentCategory = Category::firstOrCreate(
            ['slug' => 'manahij-riyad-al-atfal'],
            ['name' => 'مناهج رياض الأطفال', 'parent_id' => null]
        );
        $category = Category::firstOrCreate(
            ['slug' => $categorySlug],
            ['name' => 'سلسلة اشبال التعليمية (تمهيدي 1)', 'parent_id' => $parentCategory->id]
        );

        if ($category->parent_id !== $parentCategory->id) {
            $category->update(['parent_id' => $parentCategory->id]);
        }

        $referenceCategory = Category::where('slug', $referenceCategorySlug)->first();
        if (!$referenceCategory) {
            throw new RuntimeException('The Ashbal KG2 category used for price references was not found.');
        }

        $referenceProducts = Product::where('category_id', $referenceCategory->id)
            ->where('level', 'KG2')
            ->orderBy('id')
            ->limit($coverCount)
            ->get();

        if ($referenceProducts->count() !== $coverCount) {
            throw new RuntimeException("Expected {$coverCount} Ashbal KG2 products to copy prices from.");
        }

        foreach ($covers as $number => $contents) {
            $imagePath = 'products/ashbal-kg1-cover-' . str_pad((string) $number, 2, '0', STR_PAD_LEFT) . '.webp';
            if (!Storage::disk('public')->put($imagePath, $contents)) {
                throw new RuntimeException("Unable to store Ashbal KG1 cover {$number}.");
            }
        }

        DB::transaction(function () use ($category, $referenceProducts, $coverCount): void {
            foreach (range(1, $coverCount) as $number) {
                $reference = $referenceProducts[$number - 1];
                $numberLabel = (string) $number;
                $slug = 'ashbal-kg1-book-' . $numberLabel;
                $sku = 'ASHBAL-KG1-' . str_pad($numberLabel, 2, '0', STR_PAD_LEFT);
                $title = 'أشبال صغار - تمهيدي 1 - كتاب ' . $numberLabel;

                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'title' => $title,
                        'level' => 'KG1',
                        'book_type' => $reference->book_type,
                        'description' => "كتاب رقم {$numberLabel} من سلسلة أشبال صغار لمرحلة التمهيدي الأول (KG1).",
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
                        'image' => 'products/ashbal-kg1-cover-' . str_pad($numberLabel, 2, '0', STR_PAD_LEFT) . '.webp',
                        'sku' => $sku,
                        'rating' => 0,
                        'reviews_count' => 0,
                    ]
                );
            }
        });

        $this->command?->info('Added six Ashbal KG1 products with KG2 prices.');
    }
}
