<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function getCart()
    {
        $cart = $this->getOrCreateCart();
        return response()->json($cart->load('items.product'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Item added to cart', 'cart' => $cart->load('items.product')]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'cart' => $cartItem->cart->load('items.product')]);
    }

    public function removeItem($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;
        $cartItem->delete();

        return response()->json(['message' => 'Item removed', 'cart' => $cart->load('items.product')]);
    }

    public function mergeSessionCartToUser($user)
    {
        $sessionId = Session::getId();
        $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        
        if ($sessionCart) {
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            foreach ($sessionCart->items as $item) {
                $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();
                if ($existingItem) {
                    $existingItem->increment('quantity', $item->quantity);
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }
            $sessionCart->delete();
        }
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $this->mergeSessionCartToUser(Auth::user());
            return $cart->fresh('items.product');
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }
}
