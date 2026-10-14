<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurriculumBundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Add Audio products for English Gate (Category 6, Levels 1-6)
        $gateCategory = Category::find(6) ?? Category::where('name', 'like', '%English Gate%')->first();
        if ($gateCategory) {
            for ($lvl = 1; $lvl <= 6; $lvl++) {
                $levelStr = (string) $lvl;
                $existingAudio = Product::where('category_id', $gateCategory->id)
                    ->where('level', $levelStr)
                    ->where('book_type', 'audio')
                    ->first();

                if (!$existingAudio) {
                    $studentBook = Product::where('category_id', $gateCategory->id)
                        ->where('level', $levelStr)
                        ->where('book_type', 'student')
                        ->first();

                    $imagePath = $studentBook?->image;

                    Product::create([
                        'title' => "English Gate Audio {$lvl} - Listening Tracks",
                        'slug' => 'english-gate-audio-' . $lvl . '-' . uniqid(),
                        'category_id' => $gateCategory->id,
                        'level' => $levelStr,
                        'book_type' => 'audio',
                        'price' => 10.00,
                        'price_sar' => 10.00,
                        'price_usd' => 2.67,
                        'stock' => 1000000,
                        'author' => 'Saja Publishing & Audio Lab',
                        'publisher' => 'مؤسسة سجى المتخصصة',
                        'language' => 'English',
                        'format' => 'Digital Audio (MP3)',
                        'description' => "المسارات والتسجيلات الصوتية الكاملة الخاصة بمنهج English Gate المستوى {$lvl}. تتضمن كافة تمارين الاستماع (Listening)، المحادثة، ونطق الأصوات والكلمات للمنهج بصوت متحدثين أصليين لتطوير مهارات النطق والاستيعاب.",
                        'rating' => 0.0,
                        'reviews_count' => 0,
                        'image' => $imagePath,
                        'sku' => 'AUD-EG' . $lvl . '-' . strtoupper(Str::random(3)),
                    ]);
                }
            }
        }

        // 2. Add Audio products for English Friends (Category 7, Levels 1-6)
        $friendsCategory = Category::find(7) ?? Category::where('name', 'like', '%English Friends%')->first();
        if ($friendsCategory) {
            for ($lvl = 1; $lvl <= 6; $lvl++) {
                $levelStr = (string) $lvl;
                $existingAudio = Product::where('category_id', $friendsCategory->id)
                    ->where('level', $levelStr)
                    ->where('book_type', 'audio')
                    ->first();

                if (!$existingAudio) {
                    $combinedBook = Product::where('category_id', $friendsCategory->id)
                        ->where('level', $levelStr)
                        ->whereIn('book_type', ['student', 'combined'])
                        ->first();

                    Product::create([
                        'title' => "English Friends Audio {$lvl} - Listening Tracks",
                        'slug' => 'english-friends-audio-' . $lvl . '-' . uniqid(),
                        'category_id' => $friendsCategory->id,
                        'level' => $levelStr,
                        'book_type' => 'audio',
                        'price' => 10.00,
                        'price_sar' => 10.00,
                        'price_usd' => 2.67,
                        'stock' => 1000000,
                        'author' => 'Saja Publishing & Audio Lab',
                        'publisher' => 'مؤسسة سجى المتخصصة',
                        'language' => 'English',
                        'format' => 'Digital Audio (MP3)',
                        'description' => "المسارات والتسجيلات الصوتية عالية الجودة لمنهج English Friends المستوى {$lvl}، داعمة لمهارات التحدث والاستماع لطلاب المرحلة الابتدائية.",
                        'rating' => 0.0,
                        'reviews_count' => 0,
                        'image' => $combinedBook?->image,
                        'sku' => 'AUD-EF' . $lvl . '-' . strtoupper(Str::random(3)),
                    ]);
                }
            }
        }

        // 3. Create or Update Bundles for all (category_id, level) combinations
        $groups = Product::select('category_id', 'level')
            ->whereNotNull('category_id')
            ->whereNotNull('level')
            ->where('level', '!=', '')
            ->groupBy('category_id', 'level')
            ->get();

        foreach ($groups as $group) {
            $category = Category::find($group->category_id);
            if (!$category) {
                continue;
            }

            $products = Product::where('category_id', $group->category_id)
                ->where('level', $group->level)
                ->get();

            if ($products->isEmpty()) {
                continue;
            }

            // Representative image
            $cover = $products->firstWhere('book_type', 'student')?->image
                ?? $products->firstWhere('book_type', 'combined')?->image
                ?? $products->first(fn($p) => !empty($p->image))?->image;

            // Generate clean bundle name
            $cleanCatName = preg_replace('/^سلسلة\s+/u', '', $category->name);
            $bundleName = "باقة منهج {$cleanCatName} {$group->level}";
            $bundleSlug = Str::slug("bundle-{$category->slug}-level-{$group->level}");

            // Check if bundle already exists
            $bundle = Bundle::firstOrNew([
                'category_id' => $category->id,
                'level' => (string) $group->level,
            ]);

            $bundle->name = $bundleName;
            $bundle->slug = $bundle->slug ?: $bundleSlug;
            $bundle->image = $cover;
            $bundle->is_active = true;

            // Rich description for the bundle
            $componentsCount = $products->count();
            $bundle->description = "الباقة التعليمية الشاملة لمنهج {$cleanCatName} المستوى {$group->level}. "
                . "تتضمن هذه الحزمة المتكاملة {$componentsCount} ملحقات ومكونات مخصصة لتقديم تجربة تعليمية متوازنة وشاملة "
                . "تغطي الكتب الدراسية والأنشطة التطبيقية ودليل المعلم والمصادر الصوتية الداعمة.";

            // Calculate bundle pricing from products
            $sumSar = (float) $products->sum(fn($p) => $p->price_sar ?: $p->price ?: 0);
            $sumUsd = (float) $products->sum(fn($p) => $p->price_usd ?: round(($p->price_sar ?: $p->price ?: 0) / 3.75, 2));
            $bundle->price_sar = $sumSar;
            $bundle->price_usd = $sumUsd;

            // Save bundle
            $bundle->save();

            // Link all products to this bundle
            foreach ($products as $product) {
                $product->bundle_id = $bundle->id;
                $product->save();
            }
        }
    }
}
