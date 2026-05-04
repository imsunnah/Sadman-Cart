<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function home()
    {
        $topSelling = Product::with('category')->where('is_active', true)->inRandomOrder()->take(8)->get();
        $justForYou = Product::with('category')->where('is_active', true)->latest()->take(12)->get();
        $featuredCategories = Category::withCount('products')->orderBy('products_count', 'desc')->take(6)->get();

        // Fetch 2 specific categories with their products for "category-wise data"
        $categorySections = Category::with([
            'products' => function ($query) {
                $query->where('is_active', true)->take(4);
            }
        ])->has('products', '>=', 4)->inRandomOrder()->take(2)->get();

        return Inertia::render('Home', [
            'topSelling' => $topSelling,
            'justForYou' => $justForYou,
            'featuredCategories' => $featuredCategories,
            'categorySections' => $categorySections
        ]);
    }


    public function shop(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('subcategory')) {
            $query->whereHas('subCategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Shop', [
            'products' => $products,
            'filters' => $request->only(['category', 'subcategory', 'search'])
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
            'product' => $product->load('category'),
            'relatedProducts' => $relatedProducts
        ]);
    }

    public function checkout()
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart');
        }

        return Inertia::render('Checkout', [
            'cart' => $cart
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
        ]);

        $cart = $this->getCart();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $totalAmount = 0;
        foreach ($cart->items as $item) {
            $totalAmount += ($item->product->price * $item->quantity);
        }

        $order = \App\Models\Order::create([
            'user_id' => Auth::id(), // null for guests — column is nullable
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'village' => $validated['village'],
            'total_amount' => $totalAmount,
            'payment_method' => 'cod',
            'status' => 'pending',
            'is_active' => true
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->product->price,
                'buying_price' => $item->product->buying_price,
                'package_cost' => $item->product->package_cost,
                'quantity' => $item->quantity
            ]);

            // Reduce stock
            if ($item->product->stock >= $item->quantity) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        // Clear the cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('thank-you', $order->id);
    }

    public function thankYou(\App\Models\Order $order)
    {
        return Inertia::render('ThankYou', [
            'order' => $order->load('items')
        ]);
    }

    /**
     * Retrieve the cart for the current user or guest session.
     */
    private function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('items.product')->first();
        }

        $sessionId = Session::getId();
        return Cart::where('session_id', $sessionId)->whereNull('user_id')->with('items.product')->first();
    }
}
