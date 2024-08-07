<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends Controller
{
    // Add a product to the cart
    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = auth()->user();
        $product = Product::find($request->product_id);

        // Check if the cart exists for the user, if not create one
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id]
        );

        // Check if the product is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update the quantity if the product is already in the cart
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create a new cart item if the product is not in the cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return response()->json(['message' => 'Product added to cart'], 201);
    }

    // Update a cart item
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::find($id);

        if (is_null($cartItem)) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated successfully']);
    }

    // Remove a cart item
    public function delete($id)
    {
        $cartItem = CartItem::find($id);

        if (is_null($cartItem)) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed successfully'], 204);
    }
}