<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Frontend\PageController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Static Pages
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/shipping', [PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/vision', [PageController::class, 'vision'])->name('pages.vision');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('throttle:30,1')->name('cart.add');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('throttle:10,1')->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Reviews
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\Frontend\ReviewController::class, 'store'])->middleware('throttle:10,1')->name('reviews.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Backend\AdminController::class, 'index'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\Backend\AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [\App\Http\Controllers\Backend\AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Backend\AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Categories
    Route::resource('categories', \App\Http\Controllers\Backend\CategoryController::class)->except(['show']);

    // Products
    Route::resource('products', \App\Http\Controllers\Backend\ProductController::class)->except(['show']);

    // Customers
    Route::get('/customers', [\App\Http\Controllers\Backend\AdminController::class, 'customers'])->name('customers');

    // Courses
    Route::resource('courses', \App\Http\Controllers\Backend\CourseController::class);
});

// Courses (Public)
Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Frontend\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [\App\Http\Controllers\Frontend\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders/{order}/tracking', [\App\Http\Controllers\Frontend\ProfileController::class, 'tracking'])->name('orders.tracking');
});

// Driver Routes
Route::middleware(['auth', 'driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\DriverController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/location', [\App\Http\Controllers\DriverController::class, 'updateLocation'])->name('orders.location');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\DriverController::class, 'updateStatus'])->name('orders.status');
});

// Google Auth Routes
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback']);

require __DIR__.'/auth.php';
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
    }

    // Only redirect back to our own site (the Referer header can point anywhere)
    $previous = url()->previous();
    if (parse_url($previous, PHP_URL_HOST) !== request()->getHost()) {
        $previous = route('home');
    }

    return redirect($previous);
})->name('lang.switch');