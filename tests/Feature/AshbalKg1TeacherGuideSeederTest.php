<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\AshbalKg1TeacherGuideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AshbalKg1TeacherGuideSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_six_kg1_teacher_guides_with_english_series_pricing(): void
    {
        Storage::fake('public');

        $englishCategory = Category::firstOrCreate(
            ['slug' => 'slsl-english-gate'],
            ['name' => 'سلسلة English Gate']
        );
        Category::firstOrCreate(
            ['slug' => 'manahij-riyad-al-atfal'],
            ['name' => 'مناهج رياض الأطفال']
        );
        Product::create([
            'category_id' => $englishCategory->id,
            'title' => "English Gate Teacher's Book 1",
            'slug' => 'english-gate-teacher-book-1',
            'description' => 'Teacher guide price reference',
            'level' => '1',
            'book_type' => 'teacher',
            'price' => 7.5,
            'price_sar' => 7.5,
            'price_usd' => 21,
            'stock' => 100,
            'language' => 'English',
            'format' => 'ورقي',
        ]);

        $seeder = new AshbalKg1TeacherGuideSeeder();
        $seeder->run();
        $seeder->run();

        $category = Category::where('slug', 'slsl-ashbal-altaalymy-tmhydy-1')->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('book_type', 'teacher')
            ->orderBy('slug')
            ->get();

        $this->assertSame(6, $products->count());

        foreach ($products as $index => $product) {
            $number = $index + 1;
            $this->assertSame('KG1', $product->level);
            $this->assertSame(7.5, (float) $product->price_sar);
            $this->assertSame(21.0, (float) $product->price_usd);
            $this->assertStringContainsString('دليل المعلمة للأنشطة', $product->getRawOriginal('title'));
            Storage::disk('public')->assertExists($product->image);
            $this->assertSame('ashbal-kg1-teacher-guide-' . $number, $product->slug);
        }

        $this->get(route('shop.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('ashbal-kg1-teacher-guide-1');
    }
}
