<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AshbalKg1TeacherGuideSeeder extends Seeder
{
    public function run(): void
    {
        $categorySlug = 'slsl-ashbal-altaalymy-tmhydy-1';
        $englishCategorySlugs = ['slsl-english-gate', 'slsl-english-friends'];
        $guideCount = 6;
        $covers = [];

        foreach (range(1, $guideCount) as $number) {
            $assetPath = database_path('seeders/assets/ashbal-kg1-teacher/guide-' . str_pad((string) $number, 2, '0', STR_PAD_LEFT) . '.webp');
            $contents = is_file($assetPath) ? file_get_contents($assetPath) : false;

            if ($contents === false) {
                throw new RuntimeException("Missing Ashbal KG1 teacher-guide cover {$number}: {$assetPath}");
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

        $referenceTeacherBook = Product::where('book_type', 'teacher')
            ->whereHas('category', fn($query) => $query->whereIn('slug', $englishCategorySlugs))
            ->first();

        if (!$referenceTeacherBook) {
            throw new RuntimeException('An English Gate or English Friends teacher book is required for KG1 teacher-guide pricing.');
        }

        foreach ($covers as $number => $contents) {
            $imagePath = 'products/ashbal-kg1-teacher-guide-' . str_pad((string) $number, 2, '0', STR_PAD_LEFT) . '.webp';
            if (!Storage::disk('public')->put($imagePath, $contents)) {
                throw new RuntimeException("Unable to store Ashbal KG1 teacher-guide cover {$number}.");
            }
        }

        DB::transaction(function () use ($category, $referenceTeacherBook, $guideCount): void {
            foreach (range(1, $guideCount) as $number) {
                $numberLabel = (string) $number;
                $paddedNumber = str_pad($numberLabel, 2, '0', STR_PAD_LEFT);
                $title = 'دليل المعلمة للأنشطة - تمهيدي 1 - ' . $numberLabel;

                Product::updateOrCreate(
                    ['slug' => 'ashbal-kg1-teacher-guide-' . $numberLabel],
                    [
                        'category_id' => $category->id,
                        'title' => $title,
                        'level' => 'KG1',
                        'book_type' => 'teacher',
                        'description' => "دليل المعلمة للأنشطة رقم {$numberLabel} من سلسلة أشبال التعليمية لمرحلة التمهيدي الأول.",
                        'price' => $referenceTeacherBook->price,
                        'price_sar' => $referenceTeacherBook->price_sar,
                        'price_usd' => $referenceTeacherBook->price_usd,
                        'original_price_sar' => $referenceTeacherBook->original_price_sar,
                        'original_price_usd' => $referenceTeacherBook->original_price_usd,
                        'stock' => 1000000,
                        'author' => $referenceTeacherBook->author,
                        'publisher' => $referenceTeacherBook->publisher,
                        'language' => 'Arabic',
                        'format' => 'ورقي',
                        'image' => 'products/ashbal-kg1-teacher-guide-' . $paddedNumber . '.webp',
                        'sku' => 'ASHBAL-KG1-TEACHER-' . $paddedNumber,
                        'rating' => 0,
                        'reviews_count' => 0,
                    ]
                );
            }
        });

        $this->command?->info('Added six Ashbal KG1 teacher-guide products.');
    }
}
