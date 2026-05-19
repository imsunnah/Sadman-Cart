<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Add is_active check to credentials
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Merge session cart to database cart after login
            // This is handled by a listener or directly here
            app(\App\Http\Controllers\CartController::class)->mergeSessionCartToUser(Auth::user());

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or account is inactive.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhere('mobile', $request->identifier)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'identifier' => 'No account found with this email or mobile number.',
            ]);
        }

        // Put a simulated OTP 123456 in the session
        session([
            'reset_otp' => '123456',
            'reset_user_id' => $user->id,
        ]);

        return back()->with('success', 'Verification code generated. Use code 123456 to reset.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $savedOtp = session('reset_otp');
        $userId = session('reset_user_id');

        if (!$savedOtp || !$userId || $request->otp !== $savedOtp) {
            return back()->withErrors([
                'otp' => 'Invalid or expired verification code.',
            ]);
        }

        $user = User::findOrFail($userId);
        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['reset_otp', 'reset_user_id']);

        $loginRoute = $user->role === 'admin' ? '/admin/login' : '/login';

        return redirect($loginRoute)->with('success', 'Password reset successfully. Please login.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided current password does not match.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
