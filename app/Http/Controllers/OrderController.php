<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    // Create a new order
    public function create(Request $request)
    {
        $this->validate($request, [
            'shipping_address' => 'required|string|max:255'
        ]);

        $user = auth()->user();

        // Retrieve the user's cart
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $cart->cartItems->sum(function ($cartItem) {
                return $cartItem->price * $cartItem->quantity;
            }),
            'shipping_address' => $request->shipping_address,
            'status' => 'pending'
        ]);

        // Create order items from cart items
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price
            ]);
        }

        // Clear the cart
        $cart->cartItems()->delete();

        return response()->json(['message' => 'Order created successfully'], 201);
    }

    // Get all orders (admin only)
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    // Get orders for the current user
    public function userOrders()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();
        return response()->json($orders);
    }

    // Get a specific order (admin and user who owns the order)
    public function showById($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $user = auth()->user();

        if ($user->id !== $order->user_id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    // Update order status (admin only)
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|string'
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }

    // Delete an order (admin only)
    public function delete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 204);
    }
}