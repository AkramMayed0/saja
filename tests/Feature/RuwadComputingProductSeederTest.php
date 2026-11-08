<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\RuwadComputingProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RuwadComputingProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_optimized_covers_and_products_for_all_ten_levels_idempotently(): void
    {
        Storage::fake('public');

        $referenceCategory = Category::create([
            'name' => 'سلسلة الحوسبة والذكاء الاصطناعي الأمن السيبراني (عربي)',
            'slug' => 'slsl-alhosb-oalthkaaa-alastnaaay-alamn-alsybrany-aarby',
        ]);

        foreach (range(1, 10) as $level) {
            Product::create([
                'category_id' => $referenceCategory->id,
                'title' => "Computing Series {$level}",
                'slug' => "computing-series-{$level}",
                'description' => 'Price reference',
                'level' => (string) $level,
                'book_type' => 'combined',
                'price' => 25,
                'price_sar' => 25,
                'price_usd' => 6.67,
                'stock' => 100,
                'language' => 'Arabic',
                'format' => 'ورقي',
            ]);
        }

        $seeder = new RuwadComputingProductSeeder();
        $seeder->run();
        $seeder->run();

        $category = Category::where('slug', 'slsl-road-alhosb-oalthkaaa-alastnaaay-aarby-mbst')->firstOrFail();
        $products = Product::where('category_id', $category->id)->orderBy('level')->get();

        $this->assertSame(10, $products->count());
        $this->assertSame('manahij-al-hawsabah-wa-al-tiqniyah', $category->parent->slug);

        foreach ($products as $product) {
            $this->assertSame('combined', $product->book_type);
            $this->assertSame(25.0, (float) $product->price_sar);
            Storage::disk('public')->assertExists($product->image);
        }

        $this->get(route('shop.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee($products->first()->slug);
    }
}
