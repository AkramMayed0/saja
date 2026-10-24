<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryHierarchyTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    public function test_category_parent_and_children_relationships(): void
    {
        $parent = Category::where('slug', 'manahij-al-lughah-al-injiliziyah')->first()
            ?? Category::create(['name' => 'مناهج اللغة الإنجليزية', 'slug' => 'manahij-al-lughah-al-injiliziyah']);

        $child1 = Category::create([
            'name' => 'سلسلة English Gate Test',
            'slug' => 'slsl-english-gate-test',
            'parent_id' => $parent->id,
        ]);

        $child2 = Category::create([
            'name' => 'سلسلة English Friends Test',
            'slug' => 'slsl-english-friends-test',
            'parent_id' => $parent->id,
        ]);

        $this->assertTrue($parent->is_parent);
        $this->assertFalse($child1->is_parent);
        $this->assertTrue($parent->children()->count() >= 2);
        $this->assertEquals($parent->id, $child1->parent->id);
        $this->assertEquals($parent->id, $child2->parent->id);
    }

    public function test_admin_can_create_category_with_parent(): void
    {
        $admin = $this->createAdmin();

        $parent = Category::where('slug', 'manahij-al-hawsabah-wa-al-tiqniyah')->first()
            ?? Category::create(['name' => 'مناهج الحوسبة والتقنية', 'slug' => 'manahij-al-hawsabah-wa-al-tiqniyah']);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'سلسلة جديدة فرعية للحوسبة',
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'سلسلة جديدة فرعية للحوسبة',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_cannot_delete_category_with_children(): void
    {
        $admin = $this->createAdmin();

        $parent = Category::create([
            'name' => 'قسم تجريبي رئيسي',
            'slug' => 'test-parent-cat',
        ]);

        Category::create([
            'name' => 'قسم تجريبي فرعي',
            'slug' => 'test-child-cat',
            'parent_id' => $parent->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $parent->id]);
    }

    public function test_shop_filters_by_parent_category_and_returns_child_bundles(): void
    {
        $parent = Category::where('slug', 'manahij-al-lughah-al-injiliziyah')->first();

        $child = Category::create([
            'name' => 'سلسلة English Gate تجريبية',
            'slug' => 'slsl-english-gate-demo',
            'parent_id' => $parent->id,
        ]);

        $bundle = Bundle::create([
            'category_id' => $child->id,
            'name' => 'باقة English Gate تجريبية',
            'slug' => 'bundle-english-gate-demo',
            'level' => '1',
            'price_sar' => 50,
            'price_usd' => 13.33,
            'is_active' => true,
        ]);

        // Filter by child slug
        $resChild = $this->get(route('shop.index', ['category' => $child->slug]));
        $resChild->assertStatus(200);
        $resChild->assertSeeText('باقة English Gate تجريبية');

        // Filter by parent slug
        $resParent = $this->get(route('shop.index', ['category' => $parent->slug]));
        $resParent->assertStatus(200);
        $resParent->assertSeeText('باقة English Gate تجريبية');
    }

    public function test_home_page_displays_main_categories_and_subcategory_chips(): void
    {
        $parent = Category::where('slug', 'manahij-al-lughah-al-injiliziyah')->first();

        $child = Category::create([
            'name' => 'سلسلة English Gate الفريدة',
            'slug' => 'slsl-english-gate-unique',
            'parent_id' => $parent->id,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSeeText('مناهج اللغة الإنجليزية');
        $response->assertSeeText('English Gate الفريدة');
        $response->assertSee(route('shop.index', ['category' => $child->slug]));
        $response->assertSee(route('shop.index', ['category' => $parent->slug]));
    }
}
