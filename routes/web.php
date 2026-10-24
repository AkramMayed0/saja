<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BundleController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CourseController as AdminCourseController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReviewController as AdminReviewController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ShopController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/bundle/{bundle:slug}', [ShopController::class, 'showBundle'])->name('bundles.show');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop/{product:slug}/sample', [ShopController::class, 'downloadSample'])->name('shop.sample');

// Static Pages
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/shipping', [PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/vision', [PageController::class, 'vision'])->name('pages.vision');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('throttle:30,1')->name('cart.add');
Route::post('/cart/add-bundle', [CartController::class, 'addBundle'])->middleware('throttle:30,1')->name('cart.add-bundle');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

// Courses (Public)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Checkout & Reviews
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('throttle:10,1')->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->middleware('throttle:10,1')->name('reviews.store');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking', [ProfileController::class, 'tracking'])->name('orders.tracking');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->middleware('throttle:10,1')->name('orders.cancel');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('bundles', BundleController::class)->except(['show']);
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/toggle', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::post('/customers/{user}/toggle-driver', [AdminController::class, 'toggleDriver'])->name('customers.toggle-driver');
    Route::resource('courses', AdminCourseController::class);
    Route::get('/tools/webp-converter', [AdminController::class, 'webpConverter'])->name('tools.webp-converter');
});

// Driver
Route::middleware(['auth', 'driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/orders', [DriverController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/location', [DriverController::class, 'updateLocation'])->name('orders.location');
    Route::patch('/orders/{order}/status', [DriverController::class, 'updateStatus'])->name('orders.status');
});

// Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Language switcher
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Deployment Artisan Runner (For environments like shared hosting without Terminal/SSH)
Route::get('/deploy-artisan', function (\Illuminate\Http\Request $request) {
    if ($request->query('secret') !== 'saja2026deploy') {
        abort(403, 'Unauthorized deployment key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migration = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $migration = "ERROR RUNNING MIGRATIONS:\n" . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $cache = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $cache = "ERROR CLEARING CACHE:\n" . $e->getMessage();
    }

    // Ensure public_html/storage symlink exists so images display properly
    $storageStatus = 'Storage link already active.';
    $publicHtmlStorage = dirname(base_path()) . '/public_html/storage';
    $targetStorage = storage_path('app/public');

    if (!file_exists($publicHtmlStorage)) {
        if (@symlink($targetStorage, $publicHtmlStorage)) {
            $storageStatus = "Created symlink: {$publicHtmlStorage} -> {$targetStorage}";
        } else {
            // Fallback via artisan
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            $storageStatus = \Illuminate\Support\Facades\Artisan::output() ?: 'Attempted artisan storage:link';
        }
    }

    $mailStatus = 'Default Mailer: ' . config('mail.default') . ' (Host: ' . config('mail.mailers.smtp.host') . ')';

    return response("<pre style='background:#0f172a;color:#38bdf8;padding:24px;font-family:monospace;border-radius:8px;font-size:14px;line-height:1.6;'>=== MIGRATIONS ===\n" . htmlspecialchars($migration ?: 'Nothing to migrate.') . "\n\n=== OPTIMIZATION CLEAR ===\n" . htmlspecialchars($cache) . "\n\n=== STORAGE LINK ===\n" . htmlspecialchars($storageStatus) . "\n\n=== MAIL CONFIG ===\n" . htmlspecialchars($mailStatus) . "\n\n🚀 UPDATE DEPLOYED SUCCESSFULLY!</pre>");
});


require __DIR__.'/auth.php';