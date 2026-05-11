<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function getCart()
    {
        $cart = $this->getOrCreateCart();
        return response()->json($cart->load(['items.product', 'items.combo.products']));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'combo_id' => 'nullable|exists:combos,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$request->product_id && !$request->combo_id) {
            return response()->json(['message' => 'Product or Combo ID is required'], 422);
        }

        $cart = $this->getOrCreateCart();

        if ($request->product_id) {
            $product = Product::findOrFail($request->product_id);
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            $newQuantity = ($cartItem ? $cartItem->quantity : 0) + $request->quantity;

            if ($newQuantity > $product->stock) {
                return response()->json(['message' => 'Cannot add more than available stock (' . $product->stock . ')'], 422);
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                ]);
            }
        } else {
            $combo = Combo::findOrFail($request->combo_id);
            $cartItem = $cart->items()->where('combo_id', $combo->id)->first();
            $newQuantity = ($cartItem ? $cartItem->quantity : 0) + $request->quantity;

            // Check stock for all products in combo
            foreach ($combo->products as $p) {
                if ($newQuantity > $p->stock) {
                    return response()->json(['message' => 'Cannot add combo. One or more products exceed available stock.'], 422);
                }
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'combo_id' => $combo->id,
                    'quantity' => $request->quantity,
                ]);
            }
        }

        return response()->json(['message' => 'Item added to cart', 'cart' => $cart->load(['items.product', 'items.combo.products'])]);
    }

    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        
        if ($cartItem->product_id) {
            if ($request->quantity > $cartItem->product->stock) {
                return response()->json(['message' => 'Cannot exceed available stock (' . $cartItem->product->stock . ')'], 422);
            }
        } else if ($cartItem->combo_id) {
            foreach ($cartItem->combo->products as $p) {
                if ($request->quantity > $p->stock) {
                    return response()->json(['message' => 'Cannot exceed available stock for combo components.'], 422);
                }
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'cart' => $cartItem->cart->load(['items.product', 'items.combo.products'])]);
    }

    public function removeItem($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cart = $cartItem->cart;
        $cartItem->delete();

        return response()->json(['message' => 'Item removed', 'cart' => $cart->load(['items.product', 'items.combo.products'])]);
    }

    public function mergeSessionCartToUser($user)
    {
        $sessionId = Session::getId();
        $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        
        if ($sessionCart) {
            $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            foreach ($sessionCart->items as $item) {
                if ($item->product_id) {
                    $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();
                } else {
                    $existingItem = $userCart->items()->where('combo_id', $item->combo_id)->first();
                }

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
            return $cart->fresh(['items.product', 'items.combo.products']);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }
}
