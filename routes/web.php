<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use Inertia\Inertia;

// Storefront routes
Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/categories', [StoreController::class, 'categories'])->name('categories');
Route::get('/products/{product:slug}', [StoreController::class, 'product'])->name('product.show');
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
    Route::resource('subcategories', \App\Http\Controllers\Admin\SubCategoryController::class);
    Route::get('/customers', function () {
        $customers = \App\Models\User::where('role', 'user')->latest()->paginate(15);
        return Inertia::render('Admin/Customer/Index', ['customers' => $customers]);
    })->name('customers.index');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
