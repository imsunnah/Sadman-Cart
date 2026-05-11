<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $productId = $request->query('product_id');
        
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
        
        $orders = $query->paginate(15)->withQueryString();
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        
        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
            'categories' => $categories,
            'products' => $products,
            'currentStatus' => $status ?? 'all',
            'currentCategoryId' => $categoryId ?? 'all',
            'currentProductId' => $productId ?? 'all'
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
            'order' => $order->load('items')
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
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Deduct stock if it was cancelled previously
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
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
}
