<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_views_load_successfully()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_standard_user_redirected_to_home_after_login()
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_user_redirected_to_dashboard_after_login()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_standard_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    public function test_checkout_process_creates_order_and_clears_cart()
    {
        $user = User::factory()->create();
        
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 10.00
        ]);
        
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);
        
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);
        
        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'name' => 'John Doe',
            'phone' => '123456789',
            'city' => 'Test City',
            'address' => 'Test Address, 123',
        ]);
        
        $response->assertRedirect(route('checkout.success'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 20.00,
            'status' => 'pending',
            'shipping_address' => 'John Doe, Test Address, 123, Test City, 123456789',
        ]);
        
        $this->assertDatabaseEmpty('cart_items');
    }
}
