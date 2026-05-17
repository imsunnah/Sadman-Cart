<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Combo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $productId = $request->query('product_id');
        $comboId = $request->query('combo_id');
        
        $query = Order::with('items')->latest();
        
        if ($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->whereHas('items.product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($productId && $productId !== 'all') {
            $query->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        if ($comboId && $comboId !== 'all') {
            $query->whereHas('items', function($q) use ($comboId) {
                $q->where('combo_id', $comboId);
            });
        }
        
        $orders = $query->paginate(15)->withQueryString();
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        $combos = \App\Models\Combo::all();
        
        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
            'categories' => $categories,
            'products' => $products,
            'combos' => $combos,
            'currentStatus' => $status ?? 'all',
            'currentCategoryId' => $categoryId ?? 'all',
            'currentProductId' => $productId ?? 'all',
            'currentComboId' => $comboId ?? 'all'
        ]);
    }

    public function toggleActive(Order $order)
    {
        $order->update(['is_active' => !$order->is_active]);
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function show(Order $order)
    {
        return Inertia::render('Admin/Order/Show', [
            'order' => $order->load(['items.product', 'items.combo.products'])
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled',
            'delivery_charge' => 'nullable|numeric|min:0',
            'delivery_location' => 'nullable|string|in:Inside Dhaka,Outside Dhaka',
        ]);

        if ($request->has('delivery_location')) {
            $settingKey = $validated['delivery_location'] === 'Inside Dhaka' 
                ? 'delivery_charge_inside_dhaka' 
                : 'delivery_charge_outside_dhaka';
            
            $charge = \App\Models\Setting::where('key', $settingKey)->first()->value ?? ($validated['delivery_location'] === 'Inside Dhaka' ? 60 : 120);
            
            $order->delivery_location = $validated['delivery_location'];
            $order->delivery_charge = $charge;
            
            $itemsTotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $order->total_amount = $itemsTotal + $charge;
        }

        if ($request->has('delivery_charge')) {
            $itemsTotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $order->total_amount = $itemsTotal + $validated['delivery_charge'];
            $order->delivery_charge = $validated['delivery_charge'];
        }

        if ($request->has('status')) {
            $oldStatus = $order->status;
            $newStatus = $validated['status'];
            $order->status = $newStatus;

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = $item->product;
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    } elseif ($item->combo_id) {
                        foreach ($item->combo->products as $p) {
                            $p->increment('stock', $item->quantity);
                        }
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Deduct stock if it was cancelled previously
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = $item->product;
                        if ($product) {
                            $product->decrement('stock', $item->quantity);
                        }
                    } elseif ($item->combo_id) {
                        foreach ($item->combo->products as $p) {
                            $p->decrement('stock', $item->quantity);
                        }
                    }
                }
            }
        }

        $order->save();

        // Send Email to customer
        if ($order->customer_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Silently fail or log
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    public function create()
    {
        return Inertia::render('Admin/Order/Create', [
            'products' => Product::where('is_active', true)->get(),
            'combos' => Combo::where('is_active', true)->with('products')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'delivery_location' => 'required|string',
            'delivery_charge' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string|in:product,combo',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = $validated['delivery_charge'];
        
        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'shipping_address' => $validated['shipping_address'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'delivery_location' => $validated['delivery_location'],
            'delivery_charge' => $validated['delivery_charge'],
            'status' => $validated['status'],
            'total_amount' => 0,
            'payment_method' => 'Cash on Delivery',
        ]);

        foreach ($validated['items'] as $itemData) {
            if ($itemData['type'] === 'product') {
                $product = Product::findOrFail($itemData['id']);
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->discounted_price,
                    'buying_price' => $product->buying_price,
                    'package_cost' => $product->package_cost,
                    'quantity' => $itemData['quantity'],
                ]);
                $totalAmount += $product->discounted_price * $itemData['quantity'];
                if ($validated['status'] !== 'cancelled') {
                    $product->decrement('stock', $itemData['quantity']);
                }
            } else {
                $combo = Combo::with('products')->findOrFail($itemData['id']);
                $order->items()->create([
                    'combo_id' => $combo->id,
                    'product_name' => $combo->name . ' (Bundle)',
                    'price' => $combo->price,
                    'buying_price' => $combo->products->sum('buying_price'),
                    'package_cost' => $combo->products->sum('package_cost'),
                    'quantity' => $itemData['quantity'],
                ]);
                $totalAmount += $combo->price * $itemData['quantity'];
                if ($validated['status'] !== 'cancelled') {
                    foreach ($combo->products as $p) {
                        $p->decrement('stock', $itemData['quantity']);
                    }
                }
            }
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.orders.index')->with('success', 'Order created manually.');
    }
}
