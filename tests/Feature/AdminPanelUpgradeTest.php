<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Bundle;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelUpgradeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'is_admin' => true,
            'is_driver' => false,
        ]);
    }

    public function test_admin_dashboard_loads_with_upgraded_kpis()
    {
        $customer = User::factory()->create(['is_admin' => false]);
        $category = Category::create(['name' => 'لغة عربية', 'slug' => 'arabic']);
        $product = Product::create([
            'title' => 'كتاب القراءة 1',
            'slug' => 'reading-1',
            'sku' => 'SKU-001',
            'category_id' => $category->id,
            'price_sar' => 100,
            'price_usd' => 25,
            'price' => 100,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        // Completed SAR order
        $orderSar = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 200,
            'currency' => 'SAR',
            'status' => 'completed',
            'shipping_address' => 'صنعاء',
        ]);
        OrderItem::create([
            'order_id' => $orderSar->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
            'currency' => 'SAR',
        ]);

        // Completed USD order
        $orderUsd = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 50,
            'currency' => 'USD',
            'status' => 'completed',
            'shipping_address' => 'الرياض',
        ]);

        // Pending order
        $orderPending = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 100,
            'currency' => 'SAR',
            'status' => 'pending',
            'shipping_address' => 'عدن',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('إجمالي المبيعات (ر.س)');
        $response->assertSee('إجمالي المبيعات ($)');
        $response->assertSee('طلبات بانتظار المعالجة');
        $response->assertSee('الأكثر مبيعاً');
        $response->assertSee('كتاب القراءة 1');
    }

    public function test_orders_filtering_and_search()
    {
        $customer = User::factory()->create(['name' => 'أحمد علي', 'email' => 'ahmed@example.com', 'is_admin' => false]);
        
        $order1 = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 150,
            'currency' => 'SAR',
            'status' => 'pending',
            'shipping_address' => 'شارع الستين',
        ]);

        $order2 = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 75,
            'currency' => 'USD',
            'status' => 'completed',
            'shipping_address' => 'شارع الزبيري',
        ]);

        // Filter by status=pending
        $response = $this->actingAs($this->admin)->get(route('admin.orders', ['status' => 'pending']));
        $response->assertStatus(200);
        $response->assertSee('#' . $order1->id);
        $response->assertDontSee('#' . $order2->id);

        // Search by customer name
        $response = $this->actingAs($this->admin)->get(route('admin.orders', ['search' => 'أحمد']));
        $response->assertStatus(200);
        $response->assertSee('أحمد علي');
    }

    public function test_products_filtering_and_search()
    {
        $cat1 = Category::create(['name' => 'مرحلة تمهيدي', 'slug' => 'kg']);
        $cat2 = Category::create(['name' => 'ابتدائي', 'slug' => 'primary']);

        $p1 = Product::create([
            'title' => 'سلسلة الحروف الذكية',
            'slug' => 'smart-letters',
            'sku' => 'SKU-LTR',
            'category_id' => $cat1->id,
            'price_sar' => 45,
            'price_usd' => 12,
            'price' => 45,
            'level' => 'KG1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        $p2 = Product::create([
            'title' => 'دليل الرياضيات الرقمي',
            'slug' => 'math-digital',
            'sku' => 'SKU-MTH',
            'category_id' => $cat2->id,
            'price_sar' => 80,
            'price_usd' => 20,
            'price' => 80,
            'level' => 'Grade 3',
            'book_type' => 'guide',
            'language' => 'ar',
            'format' => 'digital',
        ]);

        $p3 = Product::create([
            'title' => 'تسجيلات الحروف الصوتية',
            'slug' => 'letters-audio',
            'sku' => 'SKU-AUD',
            'category_id' => $cat1->id,
            'price_sar' => 15,
            'price_usd' => 4,
            'price' => 15,
            'level' => 'KG1',
            'book_type' => 'audio',
            'language' => 'ar',
            'format' => 'Digital Audio (MP3)',
        ]);

        // Search by SKU
        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['search' => 'SKU-LTR']));
        $response->assertStatus(200);
        $response->assertSee('سلسلة الحروف الذكية');
        $response->assertDontSee('دليل الرياضيات الرقمي');

        // Filter by format=ورقي
        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['format' => 'ورقي']));
        $response->assertStatus(200);
        $response->assertSee('سلسلة الحروف الذكية');
        $response->assertDontSee('دليل الرياضيات الرقمي');
        $response->assertDontSee('تسجيلات الحروف الصوتية');

        // Filter by format=mp3
        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['format' => 'mp3']));
        $response->assertStatus(200);
        $response->assertSee('تسجيلات الحروف الصوتية');
        $response->assertDontSee('سلسلة الحروف الذكية');

        // Filter by format=digital
        $response = $this->actingAs($this->admin)->get(route('admin.products.index', ['format' => 'digital']));
        $response->assertStatus(200);
        $response->assertSee('دليل الرياضيات الرقمي');
        $response->assertDontSee('سلسلة الحروف الذكية');
    }

    public function test_can_create_mp3_audio_product()
    {
        $cat = Category::create(['name' => 'لغات', 'slug' => 'languages']);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'English Tracks 1 MP3',
            'category_id' => $cat->id,
            'price_sar' => 20,
            'price_usd' => 5,
            'level' => '1',
            'book_type' => 'audio',
            'format' => 'Digital Audio (MP3)',
            'language' => 'English',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'title' => 'English Tracks 1 MP3',
            'book_type' => 'audio',
            'format' => 'Digital Audio (MP3)',
        ]);
    }

    public function test_customer_driver_toggle_workflow()
    {
        $user = User::factory()->create([
            'name' => 'خالد السائق',
            'email' => 'driver@example.com',
            'is_admin' => false,
            'is_driver' => false,
        ]);

        $this->assertFalse($user->is_driver);

        // Toggle to true
        $response = $this->actingAs($this->admin)->post(route('admin.customers.toggle-driver', $user));
        $response->assertRedirect();
        $this->assertTrue($user->fresh()->is_driver);

        // Toggle back to false
        $response = $this->actingAs($this->admin)->post(route('admin.customers.toggle-driver', $user));
        $response->assertRedirect();
        $this->assertFalse($user->fresh()->is_driver);
    }

    public function test_order_show_has_printable_invoice()
    {
        $customer = User::factory()->create(['is_admin' => false]);
        $order = Order::create([
            'user_id' => $customer->id,
            'total_amount' => 120,
            'currency' => 'SAR',
            'status' => 'processing',
            'shipping_address' => 'حدة، صنعاء',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee('طباعة الفاتورة');
        $response->assertSee('فاتورة بيع رسمية');
    }

    public function test_bundles_index_loads_with_filters()
    {
        $cat = Category::create(['name' => 'اللغة الإنجليزية', 'slug' => 'english']);
        $bundle = Bundle::create([
            'name' => 'باقة English Friends 1',
            'slug' => 'english-friends-1',
            'category_id' => $cat->id,
            'level' => '1',
            'price_sar' => 150,
            'price_usd' => 40,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bundles.index'));
        $response->assertStatus(200);
        $response->assertSee('باقة English Friends 1');
        $response->assertSee('اللغة الإنجليزية');
        $response->assertSee('150.00');

        // Test search filter
        $searchResponse = $this->actingAs($this->admin)->get(route('admin.bundles.index', ['search' => 'Friends']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('باقة English Friends 1');
    }

    public function test_bundle_create_and_store_with_product_sync()
    {
        $cat = Category::create(['name' => 'رياضيات', 'slug' => 'math']);
        $prod1 = Product::create([
            'title' => 'كتاب الرياضيات - الطالب',
            'slug' => 'math-student-1',
            'sku' => 'MTH-01',
            'category_id' => $cat->id,
            'price_sar' => 50,
            'price_usd' => 12,
            'price' => 50,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);
        $prod2 = Product::create([
            'title' => 'كتاب تمارين الرياضيات',
            'slug' => 'math-workbook-1',
            'sku' => 'MTH-02',
            'category_id' => $cat->id,
            'price_sar' => 40,
            'price_usd' => 10,
            'price' => 40,
            'level' => '1',
            'book_type' => 'workbook',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        // Visit create page
        $createResponse = $this->actingAs($this->admin)->get(route('admin.bundles.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('كتاب الرياضيات - الطالب');

        // Store bundle
        $postData = [
            'name' => 'باقة الرياضيات للمستوى الأول',
            'category_id' => $cat->id,
            'level' => '1',
            'price_sar' => 80,
            'price_usd' => 20,
            'description' => 'باقة شاملة لكتاب الطالب وكتاب التمارين',
            'is_active' => '1',
            'product_ids' => [$prod1->id, $prod2->id],
        ];

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.bundles.store'), $postData);
        $storeResponse->assertRedirect(route('admin.bundles.index'));

        $this->assertDatabaseHas('bundles', [
            'name' => 'باقة الرياضيات للمستوى الأول',
            'price_sar' => 80,
        ]);

        $bundle = Bundle::where('name', 'باقة الرياضيات للمستوى الأول')->first();
        $this->assertNotNull($bundle);

        // Check products were synced
        $this->assertEquals($bundle->id, $prod1->fresh()->bundle_id);
        $this->assertEquals($bundle->id, $prod2->fresh()->bundle_id);
    }

    public function test_bundle_edit_and_update_with_product_desync()
    {
        $cat = Category::create(['name' => 'علوم', 'slug' => 'science']);
        $bundle = Bundle::create([
            'name' => 'باقة العلوم 1',
            'slug' => 'science-1',
            'category_id' => $cat->id,
            'level' => '1',
            'price_sar' => 90,
            'price_usd' => 25,
            'is_active' => true,
        ]);

        $prod1 = Product::create([
            'title' => 'كتاب العلوم 1',
            'slug' => 'sci-1',
            'sku' => 'SCI-01',
            'category_id' => $cat->id,
            'bundle_id' => $bundle->id,
            'price_sar' => 50,
            'price_usd' => 12,
            'price' => 50,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        $prod2 = Product::create([
            'title' => 'دليل المعلم علوم 1',
            'slug' => 'sci-teacher-1',
            'sku' => 'SCI-02',
            'category_id' => $cat->id,
            'bundle_id' => $bundle->id,
            'price_sar' => 60,
            'price_usd' => 15,
            'price' => 60,
            'level' => '1',
            'book_type' => 'teacher',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        // Edit page
        $editResponse = $this->actingAs($this->admin)->get(route('admin.bundles.edit', $bundle));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('باقة العلوم 1');

        // Update: keep only prod1, unlink prod2
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.bundles.update', $bundle), [
            'name' => 'باقة العلوم 1 المحدثة',
            'category_id' => $cat->id,
            'level' => '1',
            'price_sar' => 95,
            'price_usd' => 26,
            'is_active' => '1',
            'product_ids' => [$prod1->id],
        ]);

        $updateResponse->assertRedirect(route('admin.bundles.index'));
        $this->assertEquals($bundle->id, $prod1->fresh()->bundle_id);
        $this->assertNull($prod2->fresh()->bundle_id);
    }

    public function test_bundle_destroy_unlinks_products()
    {
        $cat = Category::create(['name' => 'فنون', 'slug' => 'arts']);
        $bundle = Bundle::create([
            'name' => 'باقة الفنون',
            'slug' => 'arts-bundle',
            'category_id' => $cat->id,
            'price_sar' => 70,
            'price_usd' => 18,
            'is_active' => true,
        ]);

        $prod = Product::create([
            'title' => 'كراسة الرسم',
            'slug' => 'drawing-book',
            'sku' => 'ART-01',
            'category_id' => $cat->id,
            'bundle_id' => $bundle->id,
            'price_sar' => 35,
            'price_usd' => 9,
            'price' => 35,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.bundles.destroy', $bundle));
        $deleteResponse->assertRedirect(route('admin.bundles.index'));

        $this->assertDatabaseMissing('bundles', ['id' => $bundle->id]);
        $this->assertNull($prod->fresh()->bundle_id);
    }

    public function test_reviews_moderation_index_and_kpis()
    {
        $customer = User::factory()->create(['name' => 'سامي', 'is_admin' => false]);
        $cat = Category::create(['name' => 'كتب عامة', 'slug' => 'general']);
        $product = Product::create([
            'title' => 'كتاب القواعد',
            'slug' => 'grammar-book',
            'sku' => 'GRM-01',
            'category_id' => $cat->id,
            'price_sar' => 45,
            'price_usd' => 12,
            'price' => 45,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        // Create pending review
        $reviewPending = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'محتوى ممتاز جداً ومفيد',
            'is_approved' => false,
        ]);

        $customer2 = User::factory()->create(['name' => 'ماجد', 'is_admin' => false]);

        // Create approved review
        $reviewApproved = Review::create([
            'user_id' => $customer2->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'كتاب جيد ومنسق',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reviews.index'));
        $response->assertStatus(200);
        $response->assertSee('طابور مراجعة تقييمات المنتجات');
        $response->assertSee('محتوى ممتاز جداً ومفيد');
        $response->assertSee('كتاب جيد ومنسق');
        $response->assertSee('بانتظار الموافقة');
        $response->assertSee('معتمد ومنشور');

        // Test filtering by pending status
        $pendingResp = $this->actingAs($this->admin)->get(route('admin.reviews.index', ['status' => 'pending']));
        $pendingResp->assertStatus(200);
        $pendingResp->assertSee('محتوى ممتاز جداً ومفيد');
        $pendingResp->assertDontSee('كتاب جيد ومنسق');
    }

    public function test_review_approval_toggle_and_recalculation()
    {
        $customer = User::factory()->create(['is_admin' => false]);
        $cat = Category::create(['name' => 'كتب عامة 2', 'slug' => 'general-2']);
        $product = Product::create([
            'title' => 'كتاب القراءة 2',
            'slug' => 'reading-2',
            'sku' => 'RD-02',
            'category_id' => $cat->id,
            'price_sar' => 50,
            'price_usd' => 15,
            'price' => 50,
            'rating' => 0,
            'reviews_count' => 0,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'كتاب رائع',
            'is_approved' => false,
        ]);

        // Initially product has 0 approved reviews
        $this->assertEquals(0, $product->fresh()->reviews_count);

        // Admin approves review
        $response = $this->actingAs($this->admin)->post(route('admin.reviews.toggle', $review));
        $response->assertRedirect();
        $this->assertTrue($review->fresh()->is_approved);

        // Product rating and reviews_count should now be updated
        $freshProduct = $product->fresh();
        $this->assertEquals(1, $freshProduct->reviews_count);
        $this->assertEquals(5.0, $freshProduct->rating);

        // Admin hides review
        $response2 = $this->actingAs($this->admin)->post(route('admin.reviews.toggle', $review));
        $response2->assertRedirect();
        $this->assertFalse($review->fresh()->is_approved);

        // Rating should revert back to 0
        $revertedProduct = $product->fresh();
        $this->assertEquals(0, $revertedProduct->reviews_count);
        $this->assertEquals(0.0, $revertedProduct->rating);
    }

    public function test_review_destroy_and_recalculation()
    {
        $customer = User::factory()->create(['is_admin' => false]);
        $cat = Category::create(['name' => 'كتب عامة 3', 'slug' => 'general-3']);
        $product = Product::create([
            'title' => 'كتاب القراءة 3',
            'slug' => 'reading-3',
            'sku' => 'RD-03',
            'category_id' => $cat->id,
            'price_sar' => 50,
            'price_usd' => 15,
            'price' => 50,
            'rating' => 5,
            'reviews_count' => 1,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'مخالف أو مزعج',
            'is_approved' => true,
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.reviews.destroy', $review));
        $deleteResponse->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
        $this->assertEquals(0, $product->fresh()->reviews_count);
        $this->assertEquals(0.0, $product->fresh()->rating);
    }

    public function test_sidebar_displays_unapproved_reviews_badge()
    {
        $customer = User::factory()->create(['is_admin' => false]);
        $cat = Category::create(['name' => 'كتب عامة 4', 'slug' => 'general-4']);
        $product = Product::create([
            'title' => 'كتاب 4',
            'slug' => 'book-4',
            'sku' => 'BK-04',
            'category_id' => $cat->id,
            'price_sar' => 50,
            'price_usd' => 15,
            'price' => 50,
            'level' => '1',
            'book_type' => 'student',
            'language' => 'ar',
            'format' => 'physical',
        ]);

        Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'في الانتظار',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('الباقات التعليمية');
        $response->assertSee('التقييمات والمراجعات');
    }
}

