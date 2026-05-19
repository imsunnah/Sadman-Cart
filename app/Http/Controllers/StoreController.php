<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function home()
    {
        $topSelling = Product::with('category')->where('is_active', true)->inRandomOrder()->take(8)->get();
        $latestProducts = Product::with('category')->where('is_active', true)->latest()->take(10)->get();
        $discountedProducts = Product::with('category')->where('is_active', true)->whereNotNull('discount_type')->latest()->take(10)->get();
        $combos = \App\Models\Combo::with('products')->where('is_active', true)->latest()->take(6)->get();
        $featuredCategories = Category::withCount('products')->orderBy('products_count', 'desc')->take(8)->get();
        $reviews = Review::where('is_active', true)->latest()->take(9)->get();

        $categorySections = Category::with([
            'products' => function ($query) {
                $query->where('is_active', true)->take(8);
            }
        ])->has('products', '>', 0)->get();

        return Inertia::render('Home', [
            'topSelling'         => $topSelling,
            'latestProducts'     => $latestProducts,
            'discountedProducts' => $discountedProducts,
            'combos'             => $combos,
            'featuredCategories' => $featuredCategories,
            'categorySections'   => $categorySections,
            'reviews'            => $reviews,
        ]);
    }


    public function shop(Request $request)
    {
        $query = Product::with(['category', 'brand'])->where('is_active', true);

        if ($request->has('category') && $request->category) {
            $categories = explode(',', $request->category);
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        if ($request->has('subcategory') && $request->subcategory) {
            $query->whereHas('subCategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('brand') && $request->brand) {
            $brandSlugs = explode(',', $request->brand);
            $query->whereHas('brand', function ($q) use ($brandSlugs) {
                $q->whereIn('slug', $brandSlugs);
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply Sorting
        if ($request->has('sort')) {
            if ($request->sort === 'Price: Low to High') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'Price: High to Low') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $brandsQuery = \App\Models\Brand::where('is_active', true)->orderBy('name', 'asc');
        if ($request->has('category') && $request->category) {
            $categoriesList = explode(',', $request->category);
            $brandsQuery->where(function($q) use ($categoriesList) {
                $q->whereHas('categories', function ($sq) use ($categoriesList) {
                    $sq->whereIn('slug', $categoriesList);
                })->orDoesntHave('categories');
            });
        }
        $brands = $brandsQuery->get();

        return Inertia::render('Shop', [
            'products' => $products,
            'brands' => $brands,
            'filters' => $request->only(['category', 'subcategory', 'search', 'min_price', 'max_price', 'sort', 'brand'])
        ]);
    }

    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return Inertia::render('Categories', [
            'categories' => $categories
        ]);
    }

    public function product(Product $product)
    {
        abort_if(!$product->is_active, 404);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(5)
            ->get();

        return Inertia::render('ProductData', [
            'product' => $product->load(['category', 'gallery', 'reviews']),
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function storeProductReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $product->reviews()->create([
            'customer_name' => $validated['customer_name'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Thank you! Your remark/review has been submitted.');
    }

    public function combo(\App\Models\Combo $combo)
    {
        abort_if(!$combo->is_active, 404);

        $relatedProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return Inertia::render('ComboData', [
            'combo' => $combo->load('products'),
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart');
        }

        $selectedIds = $request->query('items') ? explode(',', $request->query('items')) : null;

        if ($selectedIds) {
            // Re-load the cart items with only selected ones
            $cart->setRelation('items', $cart->items->whereIn('id', $selectedIds));
        }

        if ($cart->items->count() === 0) {
            return redirect()->route('cart')->with('error', 'Please select at least one item to checkout.');
        }

        $delivery_charges = [
            'inside_dhaka' => \App\Models\Setting::where('key', 'delivery_charge_inside_dhaka')->first()->value ?? 60,
            'outside_dhaka' => \App\Models\Setting::where('key', 'delivery_charge_outside_dhaka')->first()->value ?? 120,
        ];

        return Inertia::render('Checkout', [
            'cart' => $cart,
            'delivery_charges' => $delivery_charges,
            'selectedItemIds' => $selectedIds // Pass back so we can include in form
        ]);
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'delivery_location' => 'required|string|in:Inside Dhaka,Outside Dhaka',
            'delivery_charge' => 'required|numeric',
            'selected_items' => 'nullable|string', // Added to filter items
            'customer_remarks' => 'nullable|string|max:1000',
        ]);

        $cart = $this->getCart();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $selectedIds = $validated['selected_items'] ? explode(',', $validated['selected_items']) : null;
        $itemsToProcess = $selectedIds ? $cart->items->whereIn('id', $selectedIds) : $cart->items;

        if ($itemsToProcess->count() === 0) {
            return redirect()->route('cart')->with('error', 'No items selected for checkout.');
        }

        $totalAmount = 0;
        foreach ($itemsToProcess as $item) {
            $price = $item->product_id ? $item->product->discounted_price : $item->combo->price;
            $totalAmount += ($price * $item->quantity);
        }

        // Add delivery charge
        $totalAmount += $validated['delivery_charge'];

        $userId = Auth::id();
        if (!$userId && !empty($validated['customer_email'])) {
            $user = \App\Models\User::where('email', $validated['customer_email'])->first();
            if ($user) {
                $userId = $user->id;
            }
        }

        $order = \App\Models\Order::create([
            'id' => \App\Models\Order::generateUniqueId(),
            'user_id' => $userId,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'village' => $validated['village'],
            'total_amount' => $totalAmount,
            'delivery_charge' => $validated['delivery_charge'],
            'delivery_location' => $validated['delivery_location'],
            'payment_method' => 'cod',
            'status' => 'pending',
            'is_active' => true,
            'customer_remarks' => $validated['customer_remarks'] ?? null,
        ]);

        foreach ($itemsToProcess as $item) {
            if ($item->product_id) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->discounted_price,
                    'buying_price' => $item->product->buying_price,
                    'package_cost' => $item->product->package_cost,
                    'quantity' => $item->quantity
                ]);

                // Reduce stock
                if ($item->product->stock >= $item->quantity) {
                    $item->product->decrement('stock', $item->quantity);
                }
            } else if ($item->combo_id) {
                $order->items()->create([
                    'combo_id' => $item->combo_id,
                    'product_name' => $item->combo->name . ' (Bundle)',
                    'price' => $item->combo->price,
                    'buying_price' => $item->combo->products->sum('buying_price'),
                    'package_cost' => $item->combo->products->sum('package_cost'),
                    'quantity' => $item->quantity
                ]);

                // Reduce stock for each product in the combo
                foreach ($item->combo->products as $p) {
                    if ($p->stock >= $item->quantity) {
                        $p->decrement('stock', $item->quantity);
                    }
                }
            }
        }

        // Clear only selected items or entire cart if all were selected
        if ($selectedIds) {
            $cart->items()->whereIn('id', $selectedIds)->delete();
        } else {
            $cart->items()->delete();
            $cart->delete();
        }

        return redirect()->route('thank-you', $order->id);
    }

    public function thankYou(\App\Models\Order $order)
    {
        return Inertia::render('ThankYou', [
            'order' => $order->load('items')
        ]);
    }

    public function shippingPolicy()
    {
        return Inertia::render('Policies/ShippingPolicy');
    }

    public function returnsRefunds()
    {
        return Inertia::render('Policies/ReturnsRefunds');
    }

    public function privacyPolicy()
    {
        return Inertia::render('Policies/PrivacyPolicy');
    }

    public function terms()
    {
        return Inertia::render('Policies/Terms');
    }

    public function reviewsPage()
    {
        $reviews = Review::with('product')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        $averageRating = Review::where('is_active', true)->avg('rating') ?? 5;
        $totalCount = Review::where('is_active', true)->count();

        // Calculate stars breakdown
        $starsBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('is_active', true)->where('rating', $i)->count();
            $starsBreakdown[$i] = $totalCount > 0 ? ($count / $totalCount) * 100 : 0;
        }

        return Inertia::render('Reviews', [
            'reviews' => $reviews,
            'averageRating' => round($averageRating, 1),
            'totalCount' => $totalCount,
            'starsBreakdown' => $starsBreakdown
        ]);
    }

    /**
     * Retrieve the cart for the current user or guest session.
     */
    private function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with(['items.product', 'items.combo.products'])->first();
        }

        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)->whereNull('user_id')->with(['items.product', 'items.combo.products'])->first();
    }
}
