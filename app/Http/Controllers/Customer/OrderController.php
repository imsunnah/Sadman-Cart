<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Adjust/link any orders matching the registered customer's email that don't have a user_id
        Order::whereNull('user_id')
            ->where('customer_email', $user->email)
            ->update(['user_id' => $user->id]);

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $reviews = \App\Models\Review::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->get();

        return Inertia::render('Customer/Orders/Index', [
            'orders' => $orders,
            'reviews' => $reviews,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        // Link this specific order if it matches their email and currently has no user_id
        Order::where('id', $id)
            ->whereNull('user_id')
            ->where('customer_email', $user->email)
            ->update(['user_id' => $user->id]);

        $order = Order::where('user_id', $user->id)
            ->with(['items.product', 'items.combo.products', 'statusHistories.changer', 'messages' => function($q) {
                $q->with(['user', 'admin'])->oldest();
            }])
            ->findOrFail($id);

        return Inertia::render('Customer/Orders/Show', [
            'order' => $order,
        ]);
    }
}
