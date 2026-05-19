<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $messages = $order->messages()
            ->with(['user', 'admin'])
            ->oldest()
            ->get();
            
        return response()->json($messages);
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        $order->messages()->create([
            'user_id' => $order->user_id ?? Auth::id(),
            'admin_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => true,
        ]);
        
        return back()->with('success', 'Message sent successfully.');
    }
}
