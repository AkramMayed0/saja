<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\CurrencyService;
use App\Services\ImageOptimizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BugfixesVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Test sample_pdf column exists in products table
     */
    public function test_sample_pdf_column_exists_in_products_table(): void
    {
        $this->assertTrue(Schema::hasColumn('products', 'sample_pdf'));
    }

    /**
     * 2. Test inactive bundles are hidden from storefront
     */
    public function test_inactive_bundles_are_hidden_from_storefront(): void
    {
        $category = Category::create([
            'name' => 'منهج تجريبي',
            'slug' => 'test-curriculum',
        ]);

        $activeBundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة نشطة',
            'slug' => 'active-bundle',
            'level' => 'المستوى 1',
            'price_sar' => 50.0,
            'price_usd' => 15.0,
            'is_active' => true,
        ]);

        $inactiveBundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة غير نشطة',
            'slug' => 'inactive-bundle',
            'level' => 'المستوى السري',
            'price_sar' => 50.0,
            'price_usd' => 15.0,
            'is_active' => false,
        ]);

        // Verify shop index listing
        $response = $this->get(route('shop.index'));
        $response->assertStatus(200);
        $response->assertSeeText('باقة نشطة');
        $response->assertDontSeeText('باقة غير نشطة');
        $response->assertDontSeeText('المستوى السري');

        // Verify category count excludes inactive bundles
        $categories = Category::withCount(['bundles' => fn($q) => $q->active()])->get();
        $this->assertEquals(1, $categories->firstWhere('id', $category->id)->bundles_count);

        // Verify direct view of inactive bundle aborts 404
        $inactiveResponse = $this->get(route('bundles.show', $inactiveBundle->slug));
        $inactiveResponse->assertStatus(404);

        // Verify active bundle page does not show inactive bundle in sibling/other bundles
        $activeResponse = $this->get(route('bundles.show', $activeBundle->slug));
        $activeResponse->assertStatus(200);
        $activeResponse->assertDontSeeText('باقة غير نشطة');
    }

    /**
     * 3. Test manual currency/region selection is preserved
     */
    public function test_manual_region_selection_is_preserved(): void
    {
        $currencyService = app(CurrencyService::class);

        // Explicit manual selection to GLOBAL (USD)
        $currencyService->setRegion(CurrencyService::REGION_GLOBAL);

        $this->assertEquals(CurrencyService::REGION_GLOBAL, Session::get('user_region'));
        $this->assertTrue(Session::get('region_manually_set'));

        // Next page load resolves user region
        $resolved = $currencyService->resolveUserRegion();

        $this->assertEquals(CurrencyService::REGION_GLOBAL, $resolved);
        $this->assertEquals(CurrencyService::REGION_GLOBAL, Session::get('user_region'));
        $this->assertTrue(Session::get('region_manually_set'));
    }

    /**
     * 4. Test discounted bundle pricing pro-ration in cart
     */
    public function test_discounted_bundle_pricing_proration_in_cart(): void
    {
        $category = Category::create([
            'name' => 'سلسلة الرياضيات',
            'slug' => 'math-series',
        ]);

        $bundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة الرياضيات مخفضة',
            'slug' => 'math-bundle-discounted',
            'level' => '1',
            'price_sar' => 70.00, // Bundle price is 70
            'price_usd' => 20.00,
            'is_active' => true,
        ]);

        $p1 = Product::create([
            'title' => 'كتاب الرياضيات 1',
            'slug' => 'math-book-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'price' => 60.00,
            'price_sar' => 60.00,
            'price_usd' => 16.00,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'stock' => 10,
        ]);

        $p2 = Product::create([
            'title' => 'كتاب التمارين 1',
            'slug' => 'math-workbook-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'price' => 40.00,
            'price_sar' => 40.00,
            'price_usd' => 11.00,
            'level' => '1',
            'book_type' => 'workbook',
            'language' => 'ar',
            'stock' => 10,
        ]);

        // Total original = 100 SAR, Target = 70 SAR
        // P1 ratio = 60/100 * 70 = 42.00
        // P2 ratio = 70 - 42 = 28.00
        $cartService = app(CartService::class);
        $cartService->addBundleToCart($bundle->id, 1);

        $cart = \App\Models\Cart::with('cartItems')->first();
        $this->assertNotNull($cart);
        $this->assertCount(2, $cart->cartItems);

        $item1 = $cart->cartItems->firstWhere('product_id', $p1->id);
        $item2 = $cart->cartItems->firstWhere('product_id', $p2->id);

        $this->assertEquals(42.00, (float) $item1->price);
        $this->assertEquals(28.00, (float) $item2->price);
        $this->assertEquals(70.00, (float) ($item1->price + $item2->price));
    }

    /**
     * 4b. Test odd cents rounding remainder in bundle pro-ration
     */
    public function test_odd_cents_rounding_remainder_in_bundle_proration(): void
    {
        $category = Category::create([
            'name' => 'سلسلة العلوم',
            'slug' => 'science-series',
        ]);

        // Target bundle price = 20.00, original components = 10 + 10 + 10 = 30.00
        // P1: round(10/30 * 20, 2) = 6.67
        // P2: round(10/30 * 20, 2) = 6.67
        // P3: 20 - (6.67 + 6.67) = 6.66
        // Total = 20.00 exact cent
        $bundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة العلوم 3 أجزاء',
            'slug' => 'science-3-parts',
            'level' => '1',
            'price_sar' => 20.00,
            'price_usd' => 6.00,
            'is_active' => true,
        ]);

        $products = [];
        for ($i = 1; $i <= 3; $i++) {
            $products[] = Product::create([
                'title' => "كتاب العلوم جزء {$i}",
                'slug' => "science-part-{$i}",
                'category_id' => $category->id,
                'bundle_id' => $bundle->id,
                'price' => 10.00,
                'price_sar' => 10.00,
                'price_usd' => 3.00,
                'level' => '1',
                'book_type' => 'student',
                'language' => 'ar',
                'stock' => 10,
            ]);
        }

        $cartService = app(CartService::class);
        $cartService->addBundleToCart($bundle->id, 1);

        $cart = \App\Models\Cart::with('cartItems')->first();
        $this->assertCount(3, $cart->cartItems);

        $sum = 0.0;
        foreach ($cart->cartItems as $item) {
            $sum += (float) $item->price;
        }

        $this->assertEquals(20.00, round($sum, 2));
    }

    /**
     * 5. Test ImageOptimizationService converts to WebP and enforces memory limit
     */
    public function test_image_optimization_service_stores_webp_and_sets_memory_limit(): void
    {
        Storage::fake('public');

        $service = app(ImageOptimizationService::class);
        $file = UploadedFile::fake()->image('cover.jpg', 1200, 600);

        $path = $service->storeAsWebp($file, 'products', 'book_');

        $this->assertStringEndsWith('.webp', $path);
        $this->assertStringStartsWith('products/book_', $path);
        $this->assertEquals('256M', ini_get('memory_limit'));
    }

    /**
     * 6. Test Admin Dashboard status counts with single grouped query
     */
    public function test_admin_dashboard_order_status_counts(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_driver' => false,
        ]);

        $user = User::create([
            'name' => 'Customer',
            'email' => 'customer_test@saja.edu.ye',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        Order::create(['user_id' => $user->id, 'status' => 'pending', 'total_amount' => 100, 'currency' => 'SAR']);
        Order::create(['user_id' => $user->id, 'status' => 'pending', 'total_amount' => 150, 'currency' => 'SAR']);
        Order::create(['user_id' => $user->id, 'status' => 'processing', 'total_amount' => 200, 'currency' => 'SAR']);
        Order::create(['user_id' => $user->id, 'status' => 'completed', 'total_amount' => 300, 'currency' => 'SAR']);
        Order::create(['user_id' => $user->id, 'status' => 'cancelled', 'total_amount' => 50, 'currency' => 'SAR']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Verify status counts in the view
        $response->assertViewHas('totalOrders', 5);
        $response->assertViewHas('pendingOrdersCount', 2);
        $response->assertViewHas('statusCounts', [
            'pending' => 2,
            'processing' => 1,
            'completed' => 1,
            'cancelled' => 1,
        ]);
    }
}
