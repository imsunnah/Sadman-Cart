<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use Inertia\Inertia;
use App\Http\Controllers\LanguageController;

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Setup route for Hostinger/Shared Hosting (Run this once on live server to fix images)
Route::get('/setup-production', function () {
    try {
        $target = storage_path('app/public');
        $link = public_path('storage');
        
        if (file_exists($link)) {
            if (is_link($link)) {
                @unlink($link);
            } else {
                // If public/storage is a real directory, copy its files to storage/app/public so no files are lost
                $copyDir = function ($src, $dst) use (&$copyDir) {
                    if (!file_exists($src)) return;
                    @mkdir($dst, 0755, true);
                    foreach (scandir($src) as $item) {
                        if ($item == '.' || $item == '..') continue;
                        $srcPath = $src . '/' . $item;
                        $dstPath = $dst . '/' . $item;
                        if (is_dir($srcPath)) {
                            $copyDir($srcPath, $dstPath);
                        } else {
                            if (!file_exists($dstPath)) {
                                @copy($srcPath, $dstPath);
                            }
                        }
                    }
                };
                $copyDir($link, $target);

                // Recursively delete the physical public/storage folder
                $deleteDir = function ($dir) use (&$deleteDir) {
                    if (!file_exists($dir)) return true;
                    if (!is_dir($dir)) return @unlink($dir);
                    foreach (scandir($dir) as $item) {
                        if ($item == '.' || $item == '..') continue;
                        if (!$deleteDir($dir . DIRECTORY_SEPARATOR . $item)) return false;
                    }
                    return @rmdir($dir);
                };
                $deleteDir($link);
            }
        }
        
        @symlink($target, $link);
        
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'Production setup completed: Storage linked and cache cleared successfully!';
    } catch (\Throwable $e) {
        return 'Error during setup: ' . $e->getMessage();
    }
});

// Fallback Route for Hostinger Images (Fixes broken symlink issues)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

// Storefront routes
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/categories', [StoreController::class, 'categories'])->name('categories');
Route::get('/products/{product:slug}', [StoreController::class, 'product'])->name('product.show');
Route::post('/products/{product:slug}/reviews', [StoreController::class, 'storeProductReview'])->name('product.reviews.store');
Route::get('/combos/{combo:slug}', [StoreController::class, 'combo'])->name('combo.show');
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart');

// Cart API Routes
Route::get('/api/cart', [\App\Http\Controllers\CartController::class, 'getCart']);
Route::post('/api/cart/add', [\App\Http\Controllers\CartController::class, 'addItem']);
Route::put('/api/cart/update/{itemId}', [\App\Http\Controllers\CartController::class, 'updateItem']);
Route::delete('/api/cart/remove/{itemId}', [\App\Http\Controllers\CartController::class, 'removeItem']);

// Auth Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Checkout routes (no login required — works for both guests and logged-in users)
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StoreController::class, 'processCheckout'])->name('checkout.process');
Route::get('/thank-you/{order}', [StoreController::class, 'thankYou'])->name('thank-you');

// Policy Routes
Route::get('/shipping-policy', [StoreController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/returns-refunds', [StoreController::class, 'returnsRefunds'])->name('returns-refunds');
Route::get('/privacy-policy', [StoreController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [StoreController::class, 'terms'])->name('terms');

// Dynamic Custom Pages
Route::get('/pages/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Admin Auth Routes
Route::get('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login']);
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalProducts = \App\Models\Product::count();
        $totalCustomers = \App\Models\User::where('role', 'user')->count();

        // Calculate Total Profit
        $totalProfit = \App\Models\OrderItem::whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
            ->get()
            ->sum(function ($item) {
                return ($item->price - $item->buying_price - $item->package_cost) * $item->quantity;
            });

        // Monthly Profit Analytics (Last 6 Months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = \App\Models\Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');

            $profit = \App\Models\OrderItem::whereHas(
                'order',
                fn($q) =>
                $q->where('status', '!=', 'cancelled')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
            )
                ->get()
                ->sum(function ($item) {
                    return ($item->price - $item->buying_price - $item->package_cost) * $item->quantity;
                });

            $monthlyStats[] = [
                'month' => $month->format('M'),
                'revenue' => $revenue,
                'profit' => $profit,
            ];
        }

        $recentOrders = \App\Models\Order::latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'totalProfit' => $totalProfit,
                'totalProducts' => $totalProducts,
                'totalCustomers' => $totalCustomers,
            ],
            'monthlyStats' => $monthlyStats,
            'recentOrders' => $recentOrders,
        ]);
    })->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::put('/categories/{category}/toggle-active', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    
    Route::resource('subcategories', \App\Http\Controllers\Admin\SubCategoryController::class);
    Route::put('/subcategories/{subcategory}/toggle-active', [\App\Http\Controllers\Admin\SubCategoryController::class, 'toggleActive'])->name('subcategories.toggle-active');
    
    Route::get('/customers', function () {
        $customers = \App\Models\User::where('role', 'user')->latest()->paginate(15);
        return Inertia::render('Admin/Customer/Index', ['customers' => $customers]);
    })->name('customers.index');
    
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::put('/products/{product}/toggle-active', [\App\Http\Controllers\Admin\ProductController::class, 'toggleActive'])->name('products.toggle-active');
    
    Route::resource('combos', \App\Http\Controllers\Admin\ComboController::class);
    Route::put('/combos/{combo}/toggle-active', [\App\Http\Controllers\Admin\ComboController::class, 'toggleActive'])->name('combos.toggle-active');
    
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::put('/orders/{order}/toggle-active', [\App\Http\Controllers\Admin\OrderController::class, 'toggleActive'])->name('orders.toggle-active');

    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->except(['show', 'edit', 'create']);
    Route::put('/reviews/{review}/toggle-active', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleActive'])->name('reviews.toggle-active');

    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
    Route::put('/pages/{page}/toggle-active', [\App\Http\Controllers\Admin\PageController::class, 'toggleActive'])->name('pages.toggle-active');

    // Media Gallery
    Route::get('/gallery', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('gallery.index');
    Route::get('/api/media', [App\Http\Controllers\Admin\MediaController::class, 'apiIndex']);
    Route::post('/gallery', [App\Http\Controllers\Admin\MediaController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('gallery.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
