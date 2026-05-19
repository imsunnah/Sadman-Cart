<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        
        $messages = $order->messages()
            ->with(['user', 'admin'])
            ->oldest()
            ->get();
            
        return response()->json($messages);
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        $order->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => false,
        ]);
        
        return back()->with('success', 'Message sent successfully.');
    }
}
