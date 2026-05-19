<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Chat/Index');
    }

    public function getThreads()
    {
        $userIds = CustomerMessage::distinct()->pluck('user_id');
            
        $threads = User::whereIn('id', $userIds)
            ->get()
            ->map(function($user) {
                $latestMessage = CustomerMessage::where('user_id', $user->id)
                    ->latest()
                    ->first();
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'latest_message' => $latestMessage ? $latestMessage->message : '',
                    'latest_message_time' => $latestMessage ? $latestMessage->created_at->toIso8601String() : null,
                ];
            })
            ->sortByDesc('latest_message_time')
            ->values();
            
        return response()->json($threads);
    }

    public function getMessages($userId)
    {
        $user = User::findOrFail($userId);
        
        $messages = CustomerMessage::where('user_id', $user->id)
            ->with(['user', 'admin'])
            ->oldest()
            ->get();
            
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        CustomerMessage::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => true,
        ]);
        
        return response()->json(['success' => true]);
    }
}
