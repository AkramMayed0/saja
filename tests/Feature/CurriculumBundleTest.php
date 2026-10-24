<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculumBundleTest extends TestCase
{
    use RefreshDatabase;

    protected function createCurriculumWithBundle(): Bundle
    {
        $category = Category::create([
            'name' => 'سلسلة English Gate',
            'slug' => 'slsl-english-gate',
        ]);

        $bundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة منهج English Gate 1',
            'slug' => 'bundle-english-gate-1',
            'level' => '1',
            'description' => 'باقة متكاملة لمنهج المستوى الأول تشمل كافة الملحقات.',
            'price_sar' => 45.00,
            'price_usd' => 12.00,
            'is_active' => true,
        ]);

        // 1. Pupil's Book
        Product::create([
            'title' => 'English Gate Pupil\'s Book 1',
            'slug' => 'english-gate-pupils-book-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'level' => '1',
            'book_type' => 'student',
            'price' => 10.00,
            'price_sar' => 10.00,
            'price_usd' => 2.67,
            'stock' => 100,
            'language' => 'English',
            'sku' => 'SKU-' . uniqid(),
        ]);

        // 2. Activity Book
        Product::create([
            'title' => 'English Gate Activity Book 1',
            'slug' => 'english-gate-activity-book-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'level' => '1',
            'book_type' => 'workbook',
            'price' => 10.00,
            'price_sar' => 10.00,
            'price_usd' => 2.67,
            'stock' => 100,
            'language' => 'English',
            'sku' => 'SKU-' . uniqid(),
        ]);

        // 3. Teacher's Book
        Product::create([
            'title' => 'English Gate Teacher\'s Book 1',
            'slug' => 'english-gate-teachers-book-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'level' => '1',
            'book_type' => 'teacher',
            'price' => 10.00,
            'price_sar' => 10.00,
            'price_usd' => 2.67,
            'stock' => 100,
            'language' => 'English',
            'sku' => 'SKU-' . uniqid(),
        ]);

        // 4. Flashcards
        Product::create([
            'title' => 'English Gate Cards 1',
            'slug' => 'english-gate-cards-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'level' => '1',
            'book_type' => 'flashcard',
            'price' => 10.00,
            'price_sar' => 10.00,
            'price_usd' => 2.67,
            'stock' => 100,
            'language' => 'English',
            'sku' => 'SKU-' . uniqid(),
        ]);

        // 5. Audio Files
        Product::create([
            'title' => 'English Gate Audio 1 - Listening Tracks',
            'slug' => 'english-gate-audio-1',
            'category_id' => $category->id,
            'bundle_id' => $bundle->id,
            'level' => '1',
            'book_type' => 'audio',
            'price' => 10.00,
            'price_sar' => 10.00,
            'price_usd' => 2.67,
            'stock' => 100,
            'language' => 'English',
            'sku' => 'SKU-' . uniqid(),
        ]);

        return $bundle;
    }

    public function test_shop_index_displays_curriculum_bundles()
    {
        $bundle = $this->createCurriculumWithBundle();

        $response = $this->get(route('shop.index'));

        $response->assertStatus(200);
        $response->assertSeeText('باقة منهج English Gate 1');
        $response->assertSeeText('سلسلة English Gate');
    }

    public function test_bundle_detail_page_displays_all_five_components()
    {
        $bundle = $this->createCurriculumWithBundle();

        $response = $this->get(route('bundles.show', $bundle->slug));

        $response->assertStatus(200);
        $response->assertSeeText($bundle->name);
        $response->assertSeeText('شراء الباقة كاملة');

        // Check the 5 components
        $response->assertSeeText('كتاب الطالب');
        $response->assertSeeText('كتاب الأنشطة');
        $response->assertSeeText('دليل المعلم');
        $response->assertSeeText('البطاقات التعليمية');
        $response->assertSeeText('الملفات الصوتية');
        $response->assertSeeText('English Gate Audio 1 - Listening Tracks');
    }

    public function test_can_add_entire_bundle_to_cart_in_single_action()
    {
        $bundle = $this->createCurriculumWithBundle();

        $response = $this->post(route('cart.add-bundle'), [
            'bundle_id' => $bundle->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // All 5 products from this bundle should now be in the cart
        $cart = Cart::first();
        $this->assertNotNull($cart);
        $this->assertEquals(5, $cart->cartItems()->count());
    }

    public function test_can_add_individual_audio_item_to_cart()
    {
        $bundle = $this->createCurriculumWithBundle();
        $audioProduct = $bundle->products()->where('book_type', 'audio')->first();

        $response = $this->post(route('cart.add'), [
            'product_id' => $audioProduct->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cart = Cart::first();
        $this->assertNotNull($cart);
        $this->assertEquals(1, $cart->cartItems()->count());
        $this->assertEquals($audioProduct->id, $cart->cartItems()->first()->product_id);
    }

    public function test_student_and_teacher_bundle_separation_and_companion()
    {
        $category = Category::create([
            'name' => 'سلسلة تجريبية',
            'slug' => 'test-curriculum-series',
        ]);

        $studentBundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة الطالب والتمارين - تجريبية 1',
            'slug' => 'bundle-student-demo-1',
            'level' => '1',
            'bundle_type' => 'student',
            'price_sar' => 18.00,
            'is_active' => true,
        ]);

        $teacherBundle = Bundle::create([
            'category_id' => $category->id,
            'name' => 'باقة المعلم والوسائط - تجريبية 1',
            'slug' => 'bundle-teacher-demo-1',
            'level' => '1',
            'bundle_type' => 'teacher',
            'price_sar' => 25.00,
            'is_active' => true,
        ]);

        $this->assertTrue($studentBundle->isStudentBundle());
        $this->assertFalse($studentBundle->isTeacherBundle());
        $this->assertEquals(__('باقة الطالب والتمارين'), $studentBundle->bundle_type_label);

        $this->assertTrue($teacherBundle->isTeacherBundle());
        $this->assertFalse($teacherBundle->isStudentBundle());
        $this->assertEquals(__('باقة المعلم والوسائط'), $teacherBundle->bundle_type_label);

        // Companion links
        $this->assertEquals($teacherBundle->id, $studentBundle->companion_bundle?->id);
        $this->assertEquals($studentBundle->id, $teacherBundle->companion_bundle?->id);
    }

    public function test_category_with_no_bundles_automatically_displays_individual_books()
    {
        $category = Category::create([
            'name' => 'مناهج الحوسبة التجريبية',
            'slug' => 'test-computer-curriculum',
        ]);

        $book = Product::create([
            'title' => 'كتاب الحوسبة والذكاء الاصطناعي 1',
            'slug' => 'computer-ai-book-1',
            'category_id' => $category->id,
            'level' => '1',
            'price' => 15.00,
            'price_sar' => 15.00,
            'price_usd' => 4.00,
            'stock' => 50,
            'sku' => 'SKU-COMP-01',
        ]);

        // When navigating to shop for this category, viewMode automatically switches to books
        $response = $this->get(route('shop.index', ['category' => $category->slug]));
        $response->assertStatus(200);
        $response->assertSeeText('كتاب الحوسبة والذكاء الاصطناعي 1');
        $response->assertDontSeeText('لم يتم العثور على باقات تطابق البحث');
    }

    public function test_shop_index_displays_unified_curriculum_catalog_with_all_books_and_bundles()
    {
        // 1. Create a bundle in English
        $englishCat = Category::create([
            'name' => 'سلسلة English Gate تجريبية',
            'slug' => 'slsl-english-gate-unified',
        ]);
        $bundle = Bundle::create([
            'category_id' => $englishCat->id,
            'name' => 'باقة الطالب - English Gate 1',
            'slug' => 'bundle-student-gate-unified-1',
            'level' => '1',
            'bundle_type' => 'student',
            'price_sar' => 18.00,
            'is_active' => true,
        ]);

        // 2. Create standalone Computer Science book
        $computerCat = Category::create([
            'name' => 'سلسلة الحوسبة والأمن السيبراني تجريبية',
            'slug' => 'slsl-computer-unified',
        ]);
        $computerBook = Product::create([
            'title' => 'سلسلة الحوسبة والذكاء الاصطناعي 1',
            'slug' => 'comp-book-unified-1',
            'category_id' => $computerCat->id,
            'level' => '1',
            'price' => 20.00,
            'price_sar' => 20.00,
            'price_usd' => 5.33,
            'stock' => 50,
            'sku' => 'SKU-UNIFIED-COMP',
        ]);

        // 3. Create standalone Ashbal Kindergarten book
        $ashbalCat = Category::create([
            'name' => 'سلسلة اشبال التعليمية تجريبية',
            'slug' => 'slsl-ashbal-unified',
        ]);
        $ashbalBook = Product::create([
            'title' => 'سلسلة اشبال التعليمية (تمهيدي 2) 1',
            'slug' => 'ashbal-book-unified-1',
            'category_id' => $ashbalCat->id,
            'level' => '1',
            'price' => 12.00,
            'price_sar' => 12.00,
            'price_usd' => 3.20,
            'stock' => 50,
            'sku' => 'SKU-UNIFIED-ASHBAL',
        ]);

        // Main shop page without category filter must display ALL of them
        $response = $this->get(route('shop.index'));
        $response->assertStatus(200);
        $response->assertSeeText('باقة الطالب - English Gate 1');
        $response->assertSeeText('سلسلة الحوسبة والذكاء الاصطناعي 1');
        $response->assertSeeText('سلسلة اشبال التعليمية (تمهيدي 2) 1');
    }
}


