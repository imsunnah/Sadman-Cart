<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use Inertia\Inertia;
use App\Http\Controllers\LanguageController;

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Setup route for Hostinger/Shared Hosting (Run this once on live server to fix images)
Route::get('/setup-production', function () {
    try {
        $storagePublic = storage_path('app/public');
        $publicStorage = public_path('storage');
        
        // If public/storage is a symlink, delete it
        if (file_exists($publicStorage) && is_link($publicStorage)) {
            @unlink($publicStorage);
        }
        
        // Ensure public/storage is a real directory
        if (!file_exists($publicStorage)) {
            @mkdir($publicStorage, 0755, true);
        }
        
        // Copy any existing files from storage/app/public into the physical public/storage
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
        $copyDir($storagePublic, $publicStorage);
        
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'Production setup completed: All images successfully moved to physical public/storage! Cache cleared.';
    } catch (\Throwable $e) {
        return 'Error during setup: ' . $e->getMessage();
    }
});

Route::get('/test-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    $exists = file_exists($link);
    $isLink = is_link($link);
    $type = @filetype($link);
    
    $galleryFiles = [];
    $galleryPath = storage_path('app/public/uploads/gallery');
    if (file_exists($galleryPath)) {
        $galleryFiles = scandir($galleryPath);
    }
    
    $publicStorageFiles = [];
    if (file_exists($link) && is_dir($link)) {
        $publicStorageFiles = scandir($link);
    }

    return response()->json([
        'link_exists' => $exists,
        'link_is_symlink' => $isLink,
        'link_type' => $type,
        'gallery_files' => $galleryFiles,
        'public_storage_files' => $publicStorageFiles,
        'storage_path_app_public' => storage_path('app/public'),
        'public_path_storage' => public_path('storage'),
    ]);
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
Route::get('/reviews', [StoreController::class, 'reviewsPage'])->name('reviews');
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
Route::get('/api/search-suggestions', [StoreController::class, 'searchSuggestions'])->name('search.suggestions');
Route::delete('/api/cart/remove/{itemId}', [\App\Http\Controllers\CartController::class, 'removeItem']);

// Auth Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [\App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\AuthController::class, 'sendResetOtp'])->name('password.otp');
Route::post('/reset-password', [\App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset.submit');

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

// Customer Panel Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('customer.orders');
    Route::get('/customer/orders/{order}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('customer.orders.show');
    Route::get('/api/customer/orders/{order}/messages', [\App\Http\Controllers\Customer\MessageController::class, 'index'])->name('customer.orders.messages');
    Route::post('/customer/orders/{order}/messages', [\App\Http\Controllers\Customer\MessageController::class, 'store'])->name('customer.orders.messages.store');
    
    // Support Chat
    Route::get('/customer/chat', function () {
        return redirect()->route('customer.orders');
    })->name('customer.chat');
    Route::get('/api/customer/chat/messages', [\App\Http\Controllers\Customer\ChatController::class, 'getMessages'])->name('customer.chat.messages');
    Route::post('/api/customer/chat/messages', [\App\Http\Controllers\Customer\ChatController::class, 'sendMessage'])->name('customer.chat.send');
    
    // Change Password
    Route::post('/change-password', [\App\Http\Controllers\AuthController::class, 'changePassword'])->name('password.change');
});


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

    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::put('/brands/{brand}/toggle-active', [\App\Http\Controllers\Admin\BrandController::class, 'toggleActive'])->name('brands.toggle-active');
    
    Route::get('/customers', function () {
        $customers = \App\Models\User::where('role', 'user')
            ->withCount('orders')
            ->latest()
            ->paginate(10);
            
        $repeatedCustomers = \App\Models\User::where('role', 'user')
            ->has('orders', '>=', 2)
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->get();

        return Inertia::render('Admin/Customer/Index', [
            'customers' => $customers,
            'repeatedCustomers' => $repeatedCustomers
        ]);
    })->name('customers.index');
    
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::put('/products/{product}/toggle-active', [\App\Http\Controllers\Admin\ProductController::class, 'toggleActive'])->name('products.toggle-active');
    
    Route::resource('combos', \App\Http\Controllers\Admin\ComboController::class);
    Route::put('/combos/{combo}/toggle-active', [\App\Http\Controllers\Admin\ComboController::class, 'toggleActive'])->name('combos.toggle-active');
    
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('/orders/courier/status', [\App\Http\Controllers\Admin\OrderController::class, 'courierOrders'])->name('orders.courier');
    Route::put('/orders/{order}/toggle-active', [\App\Http\Controllers\Admin\OrderController::class, 'toggleActive'])->name('orders.toggle-active');
    Route::post('/orders/{order}/send-to-courier', [\App\Http\Controllers\Admin\OrderController::class, 'sendToCourier'])->name('orders.send-to-courier');
    Route::post('/orders/{order}/sync-courier-status', [\App\Http\Controllers\Admin\OrderController::class, 'syncCourierStatus'])->name('orders.sync-courier-status');
    Route::post('/orders/bulk-send-to-courier', [\App\Http\Controllers\Admin\OrderController::class, 'bulkSendToCourier'])->name('orders.bulk-send-to-courier');
    Route::get('/steadfast-balance', [\App\Http\Controllers\Admin\OrderController::class, 'getSteadfastBalance'])->name('steadfast-balance');

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

    // Admin Chat/Message Routes
    Route::get('/api/orders/{order}/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('orders.messages');
    Route::post('/orders/{order}/messages', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('orders.messages.store');
    
    // General Support Chat Box
    Route::get('/chat', [\App\Http\Controllers\Admin\AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/api/chat/threads', [\App\Http\Controllers\Admin\AdminChatController::class, 'getThreads'])->name('chat.threads');
    Route::get('/api/chat/threads/{user}/messages', [\App\Http\Controllers\Admin\AdminChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/api/chat/threads/{user}/messages', [\App\Http\Controllers\Admin\AdminChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/steadfast-webhook', [\App\Http\Controllers\Admin\SteadFastWebhookController::class, 'handleSteadFastWebhook'])->name('courier.webhook');
});
