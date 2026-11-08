<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\AshbalKg1ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AshbalKg1ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_six_numbered_kg1_products_with_kg2_prices_and_covers(): void
    {
        Storage::fake('public');

        $kindergarten = Category::firstOrCreate(
            ['slug' => 'manahij-riyad-al-atfal'],
            ['name' => 'مناهج رياض الأطفال']
        );
        $kg2 = Category::firstOrCreate(
            ['slug' => 'slsl-ashbal-altaalymy-tmhydy-2'],
            ['name' => 'سلسلة اشبال التعليمية (تمهيدي 2)', 'parent_id' => $kindergarten->id]
        );

        foreach (range(1, 7) as $number) {
            Product::create([
                'category_id' => $kg2->id,
                'title' => "KG2 Book {$number}",
                'slug' => "kg2-book-{$number}",
                'description' => 'KG2 price reference',
                'level' => 'KG2',
                'book_type' => 'combined',
                'price' => 5 + $number,
                'price_sar' => 5 + $number,
                'price_usd' => 2 + $number,
                'stock' => 100,
                'language' => 'Arabic',
                'format' => 'ورقي',
            ]);
        }

        $seeder = new AshbalKg1ProductSeeder();
        $seeder->run();
        $seeder->run();

        $kg1 = Category::where('slug', 'slsl-ashbal-altaalymy-tmhydy-1')->firstOrFail();
        $products = Product::where('category_id', $kg1->id)->orderBy('slug')->get();

        $this->assertSame(6, $products->count());
        $this->assertSame($kindergarten->id, $kg1->parent_id);

        foreach ($products as $index => $product) {
            $this->assertSame('KG1', $product->level);
            $this->assertSame('combined', $product->book_type);
            $this->assertSame(6.0 + $index, (float) $product->price_sar);
            Storage::disk('public')->assertExists($product->image);
        }

        $this->get(route('shop.index', ['category' => $kg1->slug]))
            ->assertOk()
            ->assertSee('ashbal-kg1-book-1');
    }
}
