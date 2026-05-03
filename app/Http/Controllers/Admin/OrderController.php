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
        
        $query = Order::with('items')->latest();
        
        if ($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }
        
        $orders = $query->paginate(15)->withQueryString();
        
        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
            'currentStatus' => $status ?? 'all'
        ]);
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
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

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

        $order->update($validated);

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
