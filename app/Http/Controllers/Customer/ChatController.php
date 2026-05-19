<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Chat');
    }

    public function getMessages()
    {
        $messages = CustomerMessage::where('user_id', Auth::id())
            ->with(['user', 'admin'])
            ->oldest()
            ->get();
            
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        CustomerMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => false,
        ]);
        
        return response()->json(['success' => true]);
    }
}
