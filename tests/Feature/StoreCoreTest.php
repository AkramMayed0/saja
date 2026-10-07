<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;

class StoreCoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
    }

    public function test_can_create_category_and_product_in_database(): void
    {
        $category = Category::create([
            'name' => 'Books',
            'slug' => 'books',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Learn Laravel',
            'slug' => 'learn-laravel',
            'description' => 'A great book about Laravel',
            'price' => 29.99,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Books',
            'slug' => 'books',
        ]);

        $this->assertDatabaseHas('products', [
            'title' => 'Learn Laravel',
            'price' => 29.99,
        ]);
    }

    public function test_cart_service_adds_product_for_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::create([
            'name' => 'Books',
            'slug' => 'books',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Learn PHP',
            'slug' => 'learn-php',
            'description' => 'A great book about PHP',
            'price' => 19.99,
            'stock' => 5,
        ]);

        $cartService = new CartService();
        $cartService->addToCart($product->id, 2);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_frontend_routes_return_successful_response(): void
    {
        // Add minimal views for the routes so they don't fail if view is missing
        \Illuminate\Support\Facades\View::addLocation(resource_path('views'));

        $response = $this->get('/');
        // The default route returns a view, but our controller might not have it yet.
        // Let's just assert it doesn't return 500. Wait, 200 is expected.
        // We'll see if it fails.
        $response->assertStatus(200);

        $response = $this->get('/shop');
        $response->assertStatus(200);
    }
}
