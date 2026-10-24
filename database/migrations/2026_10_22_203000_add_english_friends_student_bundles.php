<?php

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $englishFriendsCat = Category::where('name', 'like', '%English Friends%')->first();
        if (!$englishFriendsCat) {
            return;
        }

        for ($level = 1; $level <= 6; $level++) {
            $combinedBook = Product::where('category_id', $englishFriendsCat->id)
                ->where('level', (string)$level)
                ->where('book_type', 'combined')
                ->first();

            $studentBundle = Bundle::updateOrCreate(
                ['slug' => "bundle-student-english-friends-{$level}"],
                [
                    'category_id' => $englishFriendsCat->id,
                    'name' => "باقة الطالب والتمارين - English Friends {$level}",
                    'level' => (string)$level,
                    'bundle_type' => 'student',
                    'price_sar' => 18.00,
                    'price_usd' => 4.80,
                    'image' => $combinedBook?->image,
                    'description' => "باقة الطالب المتكاملة لمنهج English Friends المستوى {$level} - تشمل كتاب الحصة والأنشطة والتمارين المنزلية (حصة وتمارين معاً) بخصم خاص.",
                    'is_active' => true,
                ]
            );

            if ($combinedBook) {
                $combinedBook->update(['bundle_id' => $studentBundle->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Bundle::where('slug', 'like', 'bundle-student-english-friends-%')->delete();
    }
};
