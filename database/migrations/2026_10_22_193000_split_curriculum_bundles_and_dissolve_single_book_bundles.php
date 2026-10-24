<?php

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
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
        // 1. Add bundle_type column if missing
        if (!Schema::hasColumn('bundles', 'bundle_type')) {
            Schema::table('bundles', function (Blueprint $table) {
                $table->string('bundle_type')->default('student')->after('level');
            });
        }

        // 2. Locate categories
        $englishGateCat = Category::where('name', 'like', '%English Gate%')->first();
        $englishFriendsCat = Category::where('name', 'like', '%English Friends%')->first();

        // 3. Restructure English Gate (Levels 1 to 6)
        if ($englishGateCat) {
            for ($level = 1; $level <= 6; $level++) {
                $pupilBook = Product::where('category_id', $englishGateCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'student')
                    ->first();

                $activityBook = Product::where('category_id', $englishGateCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'workbook')
                    ->first();

                $teacherBook = Product::where('category_id', $englishGateCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'teacher')
                    ->first();

                $cards = Product::where('category_id', $englishGateCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'flashcard')
                    ->first();

                $audio = Product::where('category_id', $englishGateCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'audio')
                    ->first();

                // Create Student Bundle (Pupil's Book + Activity Book)
                $studentBundle = Bundle::updateOrCreate(
                    ['slug' => "bundle-student-english-gate-{$level}"],
                    [
                        'category_id' => $englishGateCat->id,
                        'name' => "باقة الطالب - English Gate {$level}",
                        'level' => (string)$level,
                        'bundle_type' => 'student',
                        'price_sar' => 18.00,
                        'price_usd' => 4.80,
                        'image' => $pupilBook?->image,
                        'description' => "باقة الطالب المتكاملة لمنهج English Gate المستوى {$level} - تشمل كتاب الحصة (الطالب) وكتاب التمارين والواجبات المنزلية بخصم خاص.",
                        'is_active' => true,
                    ]
                );

                if ($pupilBook) {
                    $pupilBook->update(['bundle_id' => $studentBundle->id]);
                }
                if ($activityBook) {
                    $activityBook->update(['bundle_id' => $studentBundle->id]);
                }

                // Create Teacher & Media Bundle (Teacher's Book + Cards + Audio)
                $teacherBundle = Bundle::updateOrCreate(
                    ['slug' => "bundle-teacher-english-gate-{$level}"],
                    [
                        'category_id' => $englishGateCat->id,
                        'name' => "باقة المعلم والوسائط - English Gate {$level}",
                        'level' => (string)$level,
                        'bundle_type' => 'teacher',
                        'price_sar' => 25.00,
                        'price_usd' => 6.67,
                        'image' => $teacherBook?->image,
                        'description' => "حقيبة المعلم والوسائط التعليمية لمنهج English Gate المستوى {$level} - تشمل دليل المعلم الشامل، بطاقات الفلاش كارد، والتسجيلات الصوتية الرقمية.",
                        'is_active' => true,
                    ]
                );

                if ($teacherBook) {
                    $teacherBook->update(['bundle_id' => $teacherBundle->id]);
                }
                if ($cards) {
                    $cards->update(['bundle_id' => $teacherBundle->id]);
                }
                if ($audio) {
                    $audio->update(['bundle_id' => $teacherBundle->id]);
                }
            }
        }

        // 4. Restructure English Friends (Levels 1 to 6)
        if ($englishFriendsCat) {
            for ($level = 1; $level <= 6; $level++) {
                // Combined student book remains standalone individual product
                $combinedBook = Product::where('category_id', $englishFriendsCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'combined')
                    ->first();

                if ($combinedBook) {
                    $combinedBook->update(['bundle_id' => null]);
                }

                $teacherBook = Product::where('category_id', $englishFriendsCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'teacher')
                    ->first();

                $cards = Product::where('category_id', $englishFriendsCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'flashcard')
                    ->first();

                $audio = Product::where('category_id', $englishFriendsCat->id)
                    ->where('level', (string)$level)
                    ->where('book_type', 'audio')
                    ->first();

                // Create Teacher & Media Bundle
                $teacherBundle = Bundle::updateOrCreate(
                    ['slug' => "bundle-teacher-english-friends-{$level}"],
                    [
                        'category_id' => $englishFriendsCat->id,
                        'name' => "باقة المعلم والوسائط - English Friends {$level}",
                        'level' => (string)$level,
                        'bundle_type' => 'teacher',
                        'price_sar' => 25.00,
                        'price_usd' => 6.67,
                        'image' => $teacherBook?->image,
                        'description' => "حقيبة المعلم والوسائط التعليمية لمنهج English Friends المستوى {$level} - تشمل دليل المعلم، الفلاش كارد، والتسجيلات الصوتية الرقمية.",
                        'is_active' => true,
                    ]
                );

                if ($teacherBook) {
                    $teacherBook->update(['bundle_id' => $teacherBundle->id]);
                }
                if ($cards) {
                    $cards->update(['bundle_id' => $teacherBundle->id]);
                }
                if ($audio) {
                    $audio->update(['bundle_id' => $teacherBundle->id]);
                }
            }
        }

        // 5. Computer & Tech products: unlink from artificial 1-item bundles
        $techCatIds = Category::where('slug', 'like', '%hawsabah%')
            ->orWhere('name', 'like', '%الحوسبة%')
            ->pluck('id');
        $techSubIds = Category::whereIn('parent_id', $techCatIds)->pluck('id');
        $allTechIds = $techCatIds->merge($techSubIds)->unique();

        Product::whereIn('category_id', $allTechIds)->update(['bundle_id' => null]);
        Bundle::whereIn('category_id', $allTechIds)->delete();

        // 6. Kindergarten products: unlink from artificial 1-item bundles
        $kgCatIds = Category::where('slug', 'like', '%riyad%')
            ->orWhere('name', 'like', '%رياض%')
            ->orWhere('name', 'like', '%اشبال%')
            ->pluck('id');
        $kgSubIds = Category::whereIn('parent_id', $kgCatIds)->pluck('id');
        $allKgIds = $kgCatIds->merge($kgSubIds)->unique();

        Product::whereIn('category_id', $allKgIds)->update(['bundle_id' => null]);
        Bundle::whereIn('category_id', $allKgIds)->delete();

        // 7. Clean up any remaining obsolete bundles that have no products
        Bundle::doesntHave('products')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bundles', 'bundle_type')) {
            Schema::table('bundles', function (Blueprint $table) {
                $table->dropColumn('bundle_type');
            });
        }
    }
};
